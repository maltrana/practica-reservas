import reservasJson from '../data/reservas.json';
import restauranteJson from '../data/restaurante.json';
import { Reserva, Restaurante } from './types';
import { parseTimeToMinutes, addMinutes } from '../lib/calculos_minutos';

const reservas: Reserva[] = reservasJson;
const restaurante: Restaurante = restauranteJson;


/**
 * Calcula la ocupación total de un restaurante para un día concreto sumando el total de personas de todas las reservas.
 * 
 * @param restauranteId - El identificador del restaurante.
 * @param fecha - La fecha en formato YYYY-MM-DD para la cual calcular la ocupación.
 * @returns El número total de personas reservadas en esa fecha.
 */
export function calcularOcupacion(restauranteId: string, fecha: string): number {
  return reservas
    .filter((r: Reserva) => r.restauranteId === restauranteId && r.fecha === fecha)
    .reduce((acc: number, curr: Reserva) => acc + curr.personas, 0);
}



/**
 * Verifica si un restaurante está completo para una fecha concreta, considerando las reservas existentes y la capacidad total.
 * 
 * @param restauranteId - El identificador del restaurante.
 * @param fecha - La fecha en formato YYYY-MM-DD.
 * @param personas - El número de personas que se desea añadir a la reserva.
 * @returns `true` si la suma de ocupación actual más las nuevas personas excede la capacidad del restaurante, `false` en caso contrario.
 */
export function estaCompleto(restauranteId: string, fecha: string, personas: number): boolean {
  const capacidadTotal = restaurante.capacidad;
  const ocupacionActual = calcularOcupacion(restauranteId, fecha);
  return ocupacionActual + personas > capacidadTotal;
}



/**
 * Calcula la ocupación activa de un restaurante en intervalos de 15 minutos entre las 14:00 y las 16:00 horas, teniendo en cuenta las reservas activas (inicio + duración).
 * 
 * @param restauranteId - El identificador del restaurante.
 * @param fecha - La fecha en formato YYYY-MM-DD para la cual calcular la ocupación por intervalos.
 * @returns Un array de objetos con la hora (en formato HH:MM) y la ocupación correspondiente a ese intervalo.
 */
export function calcularOcupacionPorIntervalo(restauranteId: string, fecha: string): { hora: string; ocupacion: number }[] {

  const intervalos: { hora: string; ocupacion: number }[] = [];
  let hora = '14:00';

  while (hora <= '16:00') {
    const minutoIntervalo = parseTimeToMinutes(hora);
    const ocupacion = reservas
      .filter((r: Reserva) => {
        if (r.restauranteId !== restauranteId || r.fecha !== fecha) return false;
        const inicio = parseTimeToMinutes(r.hora);
        const fin = inicio + r.duracion;
        return ((inicio <= minutoIntervalo) && (minutoIntervalo < fin));
      })
      .reduce((acc, curr) => acc + curr.personas, 0);

    intervalos.push({ hora, ocupacion });
    hora = addMinutes(hora, 15);
  }

  return intervalos;
}
