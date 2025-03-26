import { calcularOcupacion, estaCompleto } from '../src/reserva';

describe('calcularOcupacion', () => {
  it('calcula la ocupación correctamente para una fecha', () => {
    expect(calcularOcupacion('rest1', '2025-03-25')).toBe(14);
    expect(calcularOcupacion('rest1', '2025-03-26')).toBe(4);
  });
});

describe('estaCompleto', () => {
  it('devuelve false si hay capacidad suficiente', () => {
    expect(estaCompleto('rest1', '2025-03-25', 5)).toBe(false);
  });

  it('devuelve true si se sobrepasa la capacidad', () => {
    expect(estaCompleto('rest1', '2025-03-25', 30)).toBe(true);
  });
});