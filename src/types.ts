export interface Reserva {
  id: string;
  restauranteId: string;
  fecha: string;
  hora: string;
  personas: number;
  nombreCliente: string;
  duracion: number;
}

export interface Restaurante {
  id: string;
  nombre: string;
  capacidad: number;
  mesas: number;
  plazasPorMesa: number;
  turno: string;
}