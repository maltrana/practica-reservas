import { parseTimeToMinutes, addMinutes } from '../lib/calculos_minutos';

describe('parseTimeToMinutes', () => {
  it('convierte una hora a minutos', () => {
    expect(parseTimeToMinutes('14:00')).toBe(840);
    expect(parseTimeToMinutes('00:00')).toBe(0);
    expect(parseTimeToMinutes('23:59')).toBe(1439);
  });
});

describe('addMinutes', () => {
  it('suma minutos a una hora y devuelve el resultado en HH:MM', () => {
    expect(addMinutes('14:00', 15)).toBe('14:15');
    expect(addMinutes('23:50', 15)).toBe('00:05');
    expect(addMinutes('10:30', 30)).toBe('11:00');
  });
});