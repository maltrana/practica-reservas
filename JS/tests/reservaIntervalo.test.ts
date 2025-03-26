import { calcularOcupacionPorIntervalo } from '../src/reserva';

describe('calcularOcupacionPorIntervalo con duración', () => {
  it('calcula correctamente la ocupación activa en cada intervalo', () => {
    const resultado = calcularOcupacionPorIntervalo('rest1', '2025-03-25');
    expect(resultado).toEqual([
      { hora: '14:00', ocupacion: 4 },
      { hora: '14:15', ocupacion: 4 },
      { hora: '14:30', ocupacion: 9 },
      { hora: '14:45', ocupacion: 11 },
      { hora: '15:00', ocupacion: 11 },
      { hora: '15:15', ocupacion: 14 }, // corregido
      { hora: '15:30', ocupacion: 10 },
      { hora: '15:45', ocupacion: 8 },  // corregido
      { hora: '16:00', ocupacion: 5 }   // corregido
    ]);
    
  });
});
