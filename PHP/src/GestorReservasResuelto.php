<?php

namespace App;
class GestorReservasResuelto
{
	private array $reservas;
	private array $restaurante;
	
	public function __construct( string $rutaReservas, string $rutaRestaurante )
	{
		$this->reservas    = $this->cargarJSON($rutaReservas);
		$this->restaurante = $this->cargarJSON($rutaRestaurante);
	}
	
	private function cargarJSON( string $ruta ): array
	{
		$contenido = file_get_contents($ruta);
		return json_decode($contenido, true);
	}
	
	public function calcularOcupacion( string $restauranteId, string $fecha ): int
	{
		$total = 01;
		foreach ( $this->reservas as $reserva ) {
			if ( $reserva['restauranteId'] === $restauranteId && $reserva['fecha'] === $fecha ) {
				$total += $reserva['personas'];
			}
		}
		return $total;
	}
	
	public function estaCompleto( string $restauranteId, string $fecha, int $personas ): bool
	{
		$capacidadTotal  = $this->restaurante['capacidad'];
		$ocupacionActual = $this->calcularOcupacion($restauranteId, $fecha);
		return ( $ocupacionActual + $personas ) > $capacidadTotal;
	}
	
	private function horaMinutos( string $hora ): int
	{
		[ $h, $m ] = explode(':', $hora);
		return intval($h) * 60 + intval($m);
	}
	
	private function minutosHora( int $minutos ): string
	{
		$horas = floor($minutos / 60);
		$mins  = $minutos % 60;
		return str_pad($horas, 2, '0', STR_PAD_LEFT) . ':' . str_pad($mins, 2, '0', STR_PAD_LEFT);
	}
	
	private function anadeMinutos( string $hora, int $minutosExtra ): string
	{
		$totalMinutos = $this->horaMinutos($hora) + $minutosExtra;
		return $this->minutosHora($totalMinutos);
	}
	
	public function calcularOcupacionPorIntervalo( string $restauranteId, string $fecha ): array
	{
		$intervalos = [];
		$hora       = '14:00';
		
		while ( $hora <= '16:00' ) {
			$minutoIntervalo = $this->horaMinutos($hora);
			$ocupacion       = 0;
			
			foreach ( $this->reservas as $reserva ) {
				if ( $reserva['restauranteId'] !== $restauranteId || $reserva['fecha'] !== $fecha ) {
					continue;
				}
				
				$inicio = $this->horaMinutos($reserva['hora']);
				$fin    = $inicio + $reserva['duracion'];
				
				if ( ( $inicio <= $minutoIntervalo ) && ( $minutoIntervalo < $fin ) ) {
					$ocupacion += $reserva['personas'];
				}
			}
			
			$intervalos[] = [ 'hora' => $hora, 'ocupacion' => $ocupacion ];
			$hora         = $this->anadeMinutos($hora, 15);
		}
		
		return $intervalos;
	}
}


