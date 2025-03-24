# Ejercicio: Gestión de Reservas y Ocupación por Intervalos en TypeScript

## Descripción

Este ejercicio consiste en implementar una función en TypeScript que calcule la ocupación de un restaurante en intervalos de 15 minutos, teniendo en cuenta un conjunto de reservas existentes, cada una con una hora de inicio y una duración.

Se trabaja sobre un restaurante con las siguientes características:
- **Nombre**: Restaurante Demo
- **Capacidad total**: 40 personas (10 mesas de 4 plazas)
- **Turno**: comida (de 14:00 a 16:00)

---

## Datos de entrada

### Restaurante (`data/restaurante.json`):
```json
{
  "id": "rest1",
  "nombre": "Restaurante Demo",
  "capacidad": 40,
  "mesas": 10,
  "plazasPorMesa": 4,
  "turno": "comida"
}
```

### Reservas (`data/reservas.json`):
```json
[
  { "id": "r1", "restauranteId": "rest1", "fecha": "2025-03-25", "hora": "14:00", "personas": 4, "nombreCliente": "Juan", "duracion": 90 },
  { "id": "r2", "restauranteId": "rest1", "fecha": "2025-03-25", "hora": "14:45", "personas": 2, "nombreCliente": "Ana", "duracion": 60 },
  { "id": "r3", "restauranteId": "rest1", "fecha": "2025-03-25", "hora": "15:15", "personas": 3, "nombreCliente": "Luis", "duracion": 45 },
  { "id": "r4", "restauranteId": "rest1", "fecha": "2025-03-25", "hora": "14:30", "personas": 5, "nombreCliente": "María", "duracion": 120 },
  { "id": "r5", "restauranteId": "rest1", "fecha": "2025-03-26", "hora": "14:00", "personas": 4, "nombreCliente": "Pepe", "duracion": 60 }
]
```

---

## Funciones a desarrollar

- `calcularOcupacion(restauranteId, fecha): number`
- `estaCompleto(restauranteId, fecha, personas): boolean`
- `calcularOcupacionPorIntervalo(restauranteId, fecha): { hora: string, ocupacion: number }[]`

---

## Lógica del cálculo por intervalos

La ocupación en cada intervalo de 15 minutos entre las 14:00 y las 16:00 es la suma de las personas de todas las reservas **activas** en ese momento (desde la hora de inicio hasta el final de la duración).

### ✅ Tabla de ocupación real por intervalo:

| Hora   | Reservas activas                                                                                  | Ocupación total |
|--------|---------------------------------------------------------------------------------------------------|-----------------|
| 14:00  | Juan (4)                                                                                          | 4               |
| 14:15  | Juan (4)                                                                                          | 4               |
| 14:30  | Juan (4), María (5)                                                                               | 9               |
| 14:45  | Juan (4), María (5), Ana (2)                                                                      | 11              |
| 15:00  | Juan (4), María (5), Ana (2)                                                                      | 11              |
| 15:15  | Juan (4), María (5), Ana (2), Luis (3)                                                            | 14              |
| 15:30  | María (5), Ana (2), Luis (3)                                                                      | 10              |
| 15:45  | María (5), Luis (3)                                                                               | 8               |
| 16:00  | María (5)                                                                                         | 5               |

---

## Estructura del proyecto

```
📁 proyecto-reservas
│
├─ 📁 data
│   ├─ restaurante.json
│   └─ reservas.json
│
├─ 📁 src
│   ├─ reserva.ts
│   ├─ types.ts
│
├─ 📁 tests
│   ├─ reserva.test.ts
│   ├─ reservaIntervalo.test.ts
│
├─ package.json
├─ tsconfig.json
├─ jest.config.js
├─ README.md
```

---

## Instrucciones de uso

1. Clonar el repositorio o descomprimir el ZIP.
2. Instalar las dependencias:
```bash
npm install
```
3. Ejecutar los tests:
```bash
npm test
```

---

## Objetivos del ejercicio

- Practicar el manejo de tipos en TypeScript.
- Aprender a trabajar con fechas y horas en intervalos.
- Escribir funciones puras y testearlas con Jest.
- Pensar en lógica de negocio aplicada a casos reales.

---

¡A divertirse y aprender! 😄