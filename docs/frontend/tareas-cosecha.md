# Tareas de Cosecha — Guía de integración con el Frontend

Módulo `WeeklyPlanTaskCrop` (tabla `task_crop_weekly_plans`). A diferencia de las tareas normales
(`/weekly-plan-tasks`), donde el pago es un presupuesto fijo repartido entre los empleados que marcaron en el
biométrico, en cosecha **el pago se calcula con una fórmula configurable por cultivo** a partir de las libras
que cosechó cada empleado.

> Las tareas de cosecha **no** consultan el biométrico. Un empleado cobra si tiene libras registradas en la tarea.

---

## 1. Modelo de dominio

```
Finca ── WeeklyPlan (semana/año)
              │
              └── WeeklyPlanTaskCrop  ← "la tarea de cosecha" (1 CDP + 1 tarea + 1 fecha)
                       │
                       ├── employees  (employee_task_crops)          name, code, lbs
                       ├── inputs     (task_crop_weekly_plan_inputs) crop_input_id, value
                       └── payments   (employee_payment_weekly_summaries.task_crop_id)

CDP (plantation_controls) ── crop_id ──► Crop
                                          ├── crop_inputs      valores que captura el usuario al cerrar
                                          ├── crop_parameters  constantes del cultivo
                                          ├── crop_ranges      tablas de búsqueda (look_up)
                                          └── crop_steps       la fórmula, paso a paso
```

Una tarea de cosecha **requiere que su CDP tenga un cultivo asignado** (`plantation_controls.crop_id`).
Sin cultivo no se puede crear ni cerrar la tarea.

---

## 2. Estados

| `status` | `status_label` | Significado | Cómo se llega |
|---|---|---|---|
| `1` | `Pendiente` | Creada, sin empleados trabajando | al crear, o tras `cleanTask` |
| `2` | `En Progreso` | Iniciada; se capturan libras por empleado | `POST startTask/{id}` |
| `3` | `Cerrada` | Cerrada por finca; libras y parámetros capturados, **sin pagos todavía** | `POST closeTask/{id}` |
| `4` | `Calculada` | El motor de cálculo ya generó los pagos | `POST calculate/{id}` |

```
        startTask              closeTask                calculate
  (1) ─────────────► (2) ─────────────────────► (3) ─────────────────► (4)
  Pendiente      En Progreso                 Cerrada               Calculada
   ▲                                            │                      │
   └──────────────── cleanTask ◄─────────────────┴──────────────────────┘
                                                            │
                                              calculate (recálculo, admin)
                                                            └──► sigue en (4)
```

- El cierre y el cálculo son **dos pasos distintos y de dos roles distintos**: los operarios de finca cierran
  (`closeTask`, cualquier autenticado) y los operarios administrativos generan los pagos (`calculate`, admin).
- `closeTask` **no calcula nada**: guarda los parámetros, valida empleados/libras/obligatorios y deja la tarea
  en `3`. Al reintentar, los parámetros se reemplazan.
- `calculate/{id}` corre el motor sobre una tarea en `3` o `4` y la deja en `4`. Es idempotente: borra los
  pagos anteriores y los vuelve a generar, así que sirve igual para el primer cálculo y para el recálculo.
- La migración `2026_09_07_000000_change_status_on_task_crop_weekly_plans_table` normaliza los registros
  históricos: las tareas en `3` que ya tenían pagos pasan a `4`; las que no, se quedan en `3` esperando el
  paso administrativo.

---

## 3. El motor de cálculo

Al cerrar, el backend arma un **contexto** (un diccionario de `clave → número`) y ejecuta los `crop_steps`
ordenados por `step_order`.

**Contexto inicial:**

1. Los `crop_parameters` del cultivo (`key → value`).
2. Los valores que envía el frontend en `closeTask`, mapeados por la `key` de su `crop_input`.
3. Por cada empleado se inyecta `employee_lbs` con sus libras.

**Cada paso** (`crop_steps`) toma dos operandos y escribe un resultado en el contexto:

| Campo | Significado |
|---|---|
| `step_order` | orden de ejecución (empezando en 1) |
| `operation` | `addition`, `sustraction`, `divide`, `multiplication`, `look_up` |
| `left` / `right` | una clave del contexto **o** un número literal (`"8"`, `"0.5"`) |
| `result_key` | la clave donde se guarda el resultado |

`look_up` busca en `crop_ranges` la fila cuyo `key` coincide con `left` y cuyo rango
`min_value ≤ valor ≤ max_value` contiene el valor; el resultado es el campo `result` de esa fila.
(El campo `right` se ignora en `look_up`; envíe `"0"`.)

**Claves reservadas:**

| Clave | Rol |
|---|---|
| `employee_lbs` | entrada — libras del empleado en turno |
| `hours` | **salida obligatoria** — horas que se registran en el pago |
| `amount` | **salida obligatoria** — monto que se le paga al empleado |

Si al terminar los pasos no existen `hours` y `amount`, el cierre falla con **406**.

### Ejemplo

Cultivo con:

- parámetro `lbs_por_hora = 10`
- rangos sobre `employee_lbs`: `0–50 → 1.5`, `50.01–500 → 2.0`
- pasos:
  1. `look_up` `left=employee_lbs` → `tarifa`
  2. `multiplication` `left=employee_lbs` `right=tarifa` → `amount`
  3. `divide` `left=employee_lbs` `right=lbs_por_hora` → `hours`

Empleado con 30 lbs → `tarifa = 1.5`, `amount = 45`, `hours = 3`.
Empleado con 120 lbs → `tarifa = 2.0`, `amount = 240`, `hours = 12`.

El contexto se reinicia para cada empleado, así que los resultados nunca se contaminan entre uno y otro.

---

## 4. Contrato de respuesta y errores

Todas las respuestas usan el envelope estándar:

```json
{ "statusCode": 200, "message": "Tareas de Cosecha Obtenidas Correctamente", "data": [] }
```

En error, `data` es `null` y `message` trae el texto en español listo para mostrarse al usuario.

| Código | Clase | Cuándo |
|---|---|---|
| `400` | `BadRequestError` | falta un parámetro obligatorio de la query (ej. `weeklyPlanId`) |
| `404` | `NotFoundError` | la tarea, el CDP o el plan semanal no existen |
| `406` | `NotAcceptable` | la operación no es válida en el estado actual, o el cultivo está mal configurado |
| `422` | validación de Laravel | el payload no cumple las reglas del request |
| `500` | — | error inesperado |

### Mensajes de 406 que puede recibir el frontend

**Flujo de la tarea**

| Mensaje | Qué pasó |
|---|---|
| `La tarea ya fue iniciada` | `startTask` sobre una tarea que no está en `1` |
| `La tarea no cuenta con empleados asignados` | `startTask`, `closeTask` o `calculate` sin empleados |
| `La tarea no ha sido iniciada` | `closeTask` sobre una tarea en `1` |
| `La tarea ya fue cerrada` | `closeTask`, `update` o `destroy` sobre una tarea en `3` o `4` |
| `La tarea aún no ha sido cerrada` | `calculate` sobre una tarea en `1` o `2` |
| `La tarea cuenta con empleados sin libras registradas` | algún empleado tiene `lbs = null` |
| `Las tareas de cosecha indicadas ya existen` | todas las fechas del `store` ya tenían tarea |
| `Los valores enviados no pertenecen al cultivo {cultivo}` | un `crop_input_id` es de otro cultivo |

**Configuración del cultivo**

| Mensaje | Qué revisar |
|---|---|
| `El CDP {nombre} no tiene un cultivo asignado` | asignar `crop_id` al CDP |
| `El cultivo {nombre} no tiene pasos de cálculo configurados` | crear `crops-calculation-steps` |
| `Faltan los valores para: {etiquetas}` | el payload de `closeTask` no trae un `crop_input` marcado como obligatorio |
| `El paso '{result_key}' referencia la clave '{clave}' que no existe` | `left`/`right` apunta a algo que ningún paso, parámetro o input produce |
| `El paso '{result_key}' intenta dividir entre cero` | el divisor resultó 0 |
| `No existe un rango configurado para '{clave}' con el valor {valor}` | falta cubrir ese valor en `crops-ranges` |
| `La operación '{op}' no es soportada` | `operation` fuera de la lista permitida |
| `El cálculo del cultivo no produjo los valores de horas y monto` | ningún paso escribe `hours` y/o `amount` |

---

## 5. Endpoints

Todos van bajo el prefijo `/api` y requieren `Authorization: Bearer <jwt>`.
La columna **Rol** indica si además se exige el middleware `administrate_agricola`
(roles `admin` o `adminagricola`).

### 5.1 Configuración del cultivo

Todos son `apiResource` (`index`, `store`, `show`, `update`, `destroy`) y **solo admin**.
El `index` de cada uno filtra por `?cropId=`.

| Recurso | Ruta | Campos |
|---|---|---|
| Cultivo | `/crops` | `name`, `code` |
| Inputs | `/crops-inputs` | `crop_id`, `key`, `label`, `required` (bool), `default_value` |
| Parámetros | `/crops-parameters` | `crop_id`, `key`, `value` |
| Rangos | `/crops-ranges` | `crop_id`, `key`, `min_value`, `max_value` (≥ `min_value`), `result` |
| Pasos | `/crops-calculation-steps` | `crop_id`, `step_order` (≥1), `result_key`, `operation`, `left`, `right` |

> `required` en `/crops-inputs` ahora se respeta tal cual lo envía el frontend (antes se forzaba a `true`)
> y viene incluido en las respuestas de `index` y `show`.

`operation` solo acepta: `addition`, `sustraction`, `divide`, `multiplication`, `look_up`.

### 5.2 Tareas de cosecha — `/weekly-plan-tasks-crops`

| Método | Ruta | Rol |
|---|---|---|
| GET | `/weekly-plan-tasks-crops?weeklyPlanId={id}` | autenticado |
| POST | `/weekly-plan-tasks-crops` | autenticado |
| GET | `/weekly-plan-tasks-crops/{id}` | autenticado |
| PUT | `/weekly-plan-tasks-crops/{id}` | autenticado |
| DELETE | `/weekly-plan-tasks-crops/{id}` | autenticado |

**`GET /weekly-plan-tasks-crops`** — `weeklyPlanId` es obligatorio (400 si falta).
Filtros opcionales: `cdp` (id del CDP), `task` (`tarea_id`), `status`, `operation_date` (`Y-m-d`).
Ordenado por `id` descendente.

```json
{
  "statusCode": 200,
  "message": "Tareas de Cosecha Obtenidas Correctamente",
  "data": [
    {
      "id": 12,
      "plantation_control_id": 3,
      "cdp": "CDP-2026-03",
      "lote": "Lote A",
      "crop_id": 1,
      "tarea_id": 7,
      "task": "Cosecha",
      "weekly_plan_id": 45,
      "operation_date": "2026-09-07",
      "status": 2,
      "status_label": "En Progreso",
      "total_employees": 8,
      "total_lbs": 640.5
    }
  ]
}
```

**`POST /weekly-plan-tasks-crops`** — crea **una tarea por cada fecha**.

```json
{
  "plantation_control_id": 3,
  "tarea_id": 7,
  "weekly_plan_id": 45,
  "dates": ["2026-09-07", "2026-09-08"]
}
```

- `dates.*` debe venir en formato `Y-m-d`, sin repetidos.
- Las fechas duplicadas dentro del arreglo se ignoran, y las combinaciones `(CDP, tarea, fecha)` que ya existan
  se saltan. Si **ninguna** fecha es nueva, responde 406.
- El `weekly_plan_id` que envíe el front sirve para identificar la finca; **cada tarea se asocia al plan semanal
  que realmente corresponde a su fecha** (semana ISO + año + finca). Si no existe ese plan responde 404
  `No existe un plan semanal para la semana {n} del año {a}`.
- Respuesta `201` con `data: true`.

**`PUT /weekly-plan-tasks-crops/{id}`** — todos los campos son opcionales:
`plantation_control_id`, `tarea_id`, `operation_date` (`Y-m-d`).
Si cambia `operation_date`, la tarea se reasigna al plan semanal de esa fecha.
Responde 406 si la tarea ya está cerrada.

**`DELETE /weekly-plan-tasks-crops/{id}`** — borra empleados, parámetros y pagos junto con la tarea.
Responde 406 si la tarea ya está cerrada (use `cleanTask` primero).

### 5.3 Empleados — `/weekly-plan-task-crop-employees`

| Método | Ruta | Rol |
|---|---|---|
| GET | `/weekly-plan-task-crop-employees?taskId={id}` | autenticado |
| POST | `/weekly-plan-task-crop-employees` | autenticado |
| GET/PUT/DELETE | `/weekly-plan-task-crop-employees/{id}` | autenticado |

```json
// POST / PUT
{ "name": "Juan Pérez", "code": "1234", "lbs": 82.5, "task_crop_weekly_plan_id": 12 }
```

```json
// respuesta
{ "id": 501, "name": "Juan Pérez", "code": "1234", "lbs": 82.5, "task_crop_weekly_plan_id": 12 }
```

- `code` es único dentro de la misma tarea (422 `El empleado ya fue agregado a esta tarea de cosecha.`).
- `lbs` es opcional al agregar (se captura después) pero **no puede ser negativo**, y debe estar lleno en
  todos los empleados para poder cerrar.
- Para obtener el listado de empleados de la finca use
  `GET /fincas/employees/{weeklyPlanId}` (solo admin), que consulta el biométrico.

### 5.4 Parámetros de la tarea

| Método | Ruta | Rol |
|---|---|---|
| GET | `/weekly-plan-tasks-crops/getCropInputs/{id}` | autenticado |
| GET | `/weekly-plan-tasks-crop-inputs?taskId={id}` | admin |
| POST/PUT/DELETE | `/weekly-plan-tasks-crop-inputs[/{id}]` | admin |

**`getCropInputs/{id}`** es el endpoint que debe usar la pantalla de cierre: devuelve los `crop_inputs`
del cultivo del CDP, con el valor ya guardado si la tarea se cerró antes.

```json
{
  "statusCode": 200,
  "message": "Parametros del Cultivo Obtenidos Correctamente",
  "data": [
    {
      "task_crop_weekly_plan_id": 12,
      "crop_input_id": 4,
      "key": "precio_base",
      "label": "Precio base por libra",
      "required": true,
      "default_value": 1.5,
      "value": null
    }
  ]
}
```

Arme el formulario con `label`, precargue `value ?? default_value`, y marque como obligatorios los
`required: true`.

### 5.5 Operaciones

| Método | Ruta | Rol | Qué hace |
|---|---|---|---|
| GET | `/weekly-plan-tasks-crops/getTasksForCalendar/{weeklyPlanId}` | autenticado | eventos para el calendario |
| GET | `/weekly-plan-tasks-crops/getTasksGroupedByCdp/{weeklyPlanId}` | autenticado | resumen por CDP |
| GET | `/weekly-plan-tasks-crops/getTasksByCdp/{weeklyPlanId}/{cdp}` | autenticado | tareas de un CDP (por **nombre** de CDP) |
| GET | `/weekly-plan-tasks-crops/getCropInputs/{id}` | autenticado | parámetros para el formulario de cierre |
| POST | `/weekly-plan-tasks-crops/startTask/{id}` | autenticado | `1 → 2` |
| POST | `/weekly-plan-tasks-crops/closeTask/{id}` | autenticado | `2 → 3`, guarda los parámetros (no calcula) |
| POST | `/weekly-plan-tasks-crops/calculate/{id}` | **admin** | `3 → 4`, genera (o regenera) los pagos |
| POST | `/weekly-plan-tasks-crops/cleanTask/{id}` | **admin** | borra pagos, parámetros y empleados; vuelve a `1` |
| GET | `/weekly-plan-tasks-crops/getPayments/{id}` | **admin** | pagos generados |

**`getTasksByCdp`** aplica una regla por rol: para usuarios que **no** son `admin` ni `adminagricola` solo
devuelve las tareas cuya `operation_date` es hoy o que están En Progreso. Para los admins devuelve todas.

**`closeTask/{id}`** — el `{id}` de la ruta identifica la tarea; el cuerpo solo lleva los parámetros:

```json
{
  "inputs": [
    { "crop_input_id": 4, "value": 1.75 },
    { "crop_input_id": 5, "value": 40 }
  ]
}
```

- `inputs` debe traer al menos un elemento y no repetir `crop_input_id`.
- Cada `crop_input_id` debe pertenecer al cultivo del CDP de la tarea, y deben venir **todos los marcados
  como `required`** (después ya no hay quién los pida: el cálculo corre en otra pantalla).
- Al reintentar un cierre fallido los parámetros se **reemplazan**, no se duplican.
- Respuesta: `200` con `data: true`. **No genera pagos**; `getPayments/{id}` viene vacío hasta `calculate`.

**`getPayments/{id}`**

```json
{
  "statusCode": 200,
  "message": "Pagos Obtenidos Correctamente",
  "data": [
    { "id": 900, "name": "Juan Pérez", "code": "1234", "hours": 8.2, "amount": 143.5, "date": "07-09-2026", "theorical_hours": 0 }
  ]
}
```

`theorical_hours` siempre es `0` en cosecha: es un campo del flujo de tareas normales.
En el reporte de planilla las horas de cosecha se suman desde `hours`.

**Calendario** (`getTasksForCalendar`) — formato pensado para FullCalendar:

```json
{
  "id": 12, "title": "Cosecha - Lote A", "start": "2026-09-07", "end": "2026-09-07",
  "backgroundColor": "orange", "editable": false, "blocked": true,
  "task": "Cosecha", "lote": "Lote A", "cdp": "CDP-2026-03"
}
```

**Resumen por CDP** (`getTasksGroupedByCdp`):

```json
[ { "cdp": "CDP-2026-03", "lote": "Lote A", "total_tasks": 5 } ]
```

---

## 6. Secuencias recomendadas

### Pantalla del operador

```
1. GET  /weekly-plan-tasks-crops/getTasksByCdp/{weeklyPlanId}/{cdp}
2. GET  /weekly-plan-task-crop-employees?taskId={id}         → empleados ya asignados
   POST /weekly-plan-task-crop-employees                     → agregar los que falten
3. POST /weekly-plan-tasks-crops/startTask/{id}              → status 2
4. PUT  /weekly-plan-task-crop-employees/{empId}             → capturar lbs de cada uno
5. GET  /weekly-plan-tasks-crops/getCropInputs/{id}          → armar el formulario de cierre
6. POST /weekly-plan-tasks-crops/closeTask/{id}              → status 3 (sin pagos)
```

Bloquee el botón de cerrar mientras algún empleado tenga `lbs` vacía: el backend responde 406, pero es mejor
evitar el viaje.

### Pantalla de administración

```
1. GET  /weekly-plan-tasks-crops?weeklyPlanId={id}&status=3  → tareas cerradas pendientes de cálculo
2. POST /weekly-plan-tasks-crops/calculate/{id}              → status 4 + pagos
3. GET  /weekly-plan-tasks-crops/getPayments/{id}            → revisar los pagos
4a. POST /weekly-plan-tasks-crops/calculate/{id}             → recalcular (tras corregir el cultivo)
4b. POST /weekly-plan-tasks-crops/cleanTask/{id}             → reabrir por completo (borra empleados)
```

`status=3` es la bandeja de trabajo del administrativo. Antes de calcular puede corregir los parámetros con
`/weekly-plan-tasks-crop-inputs` (admin) sin tocar a la finca. `calculate` sirve tanto para el primer cálculo
como para volver a generar los pagos cuando se corrigió un rango, un parámetro o un paso del cultivo, sin
perder los empleados ni las libras. `cleanTask` es destructivo: deja la tarea vacía en estado `Pendiente`.
