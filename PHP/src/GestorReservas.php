<?php

class GestorReservas
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
	
	/**
	 * Esta función calcula la ocupación de un restaurante en un día concreto.
	 *
	 * @param string $restauranteId
	 * @param string $fecha
	 *
	 * @return int
	 */
	public function calcularOcupacion( string $restauranteId, string $fecha ): int
	{
		$totalPersonas = 0;
		
		foreach ( $this->reservas as $reserva ) {
			if ( ( $reserva['restauranteId'] == $restauranteId ) && ( $reserva['fecha'] == $fecha ) ) {
				$totalPersonas = $totalPersonas + $reserva['personas'];
			}
		}
		
		return $totalPersonas;
	}
	
	/**
	 *  Esta función calcula si un restaurante está completo en un día y para unas personas concretas.
	 *
	 * @param string $restauranteId
	 * @param string $fecha
	 * @param int    $personas
	 *
	 * @return bool
	 */
	public function estaCompleto( string $restauranteId, string $fecha, int $personas ): bool
	{
		// primero calculamos la ocupación actual
		$ocupacion = $this->calcularOcupacion($restauranteId, $fecha);
		
		// luego calculamos como va a quedar la ocupación si añadimos las personas
		$ocupacion_final = $ocupacion + $personas;
		
		// estas son las plazas del restaurante
		$plazas_resturante = $this->restaurante['capacidad'];
		
		// miro si me entran
		if ( $ocupacion_final > $plazas_resturante ) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Esta función calcula la ocupación de un restaurante en un día concreto en intervalos de 15 minutos.
	 *
	 * @param string $restauranteId
	 * @param string $fecha
	 *
	 * @return array
	 */
	public function calcularOcupacionPorIntervalo( string $restauranteId, string $fecha ): array
	{
		$intervalos = [];
		
		// haces el bucle de 14:00 a 16:00 en intervalos de 15 minutos
		$hora = "14:00";
		while ( $hora <= '16:00' ) {
			
			// convierto a minutos la hora actual y pongo la ocupación a cero
			$el_intervalo_actual = $this->horaMinutos($hora);
			$ocupacion = 0;
			
			// miro reserva por reserva
			foreach ($this->reservas as $reserva) {
				
				// Con esto me voy a la siguiente reserva si no estoy en el restaurante ni en la fecha
				if (($reserva['restauranteId'] != $restauranteId) || ($reserva['fecha'] != $fecha)) {
					continue;
				}
				
				$inicia_reserva   = $this->horaMinutos($reserva['hora']);
				$finaliza_reserva = $inicia_reserva + $reserva['duracion'];
				
				
				// compruebo si el intervalo actual está dentro de la reserva
				// 14   15   16
				// -----[---|----
				if (($inicia_reserva <= $el_intervalo_actual) && ($el_intervalo_actual< $finaliza_reserva))
				{
					$ocupacion = $ocupacion + $reserva['personas']; // incremento la ocupación
				}
			}
			
			// añado el intervalo al array de salida
			$intervalos[] = [
				'hora'      => $hora,
				'ocupacion' => $ocupacion
			];
			
			// sumo 15 minutos
			$hora = $this->anadeMinutos($hora, 15);
		}
		
		return $intervalos;
	}
}


