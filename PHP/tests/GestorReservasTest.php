<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Before;
 

require_once __DIR__ . '/../src/GestorReservas.php';

class GestorReservasTest extends TestCase
{
    private GestorReservas $gestor;

    #[Before]
    protected function setUp(): void
    {
        $this->gestor = new GestorReservas(
            __DIR__ . '/../data/reservas.json',
            __DIR__ . '/../data/restaurante.json'
        );
    }

    public function testCalcularOcupacion(): void
    {
        $ocupacion = $this->gestor->calcularOcupacion('rest1', '2025-03-25');
        $this->assertEquals(14, $ocupacion); // 4+2+3+5
    }

    public function testEstaCompleto(): void
    {
        $this->assertFalse($this->gestor->estaCompleto('rest1', '2025-03-25', 10));
        $this->assertTrue($this->gestor->estaCompleto('rest1', '2025-03-25', 30)); // 14+30 = 44 > 40
    }

    public function testOcupacionPorIntervaloEsperada(): void
    {
        $esperado = [
            ['hora' => '14:00', 'ocupacion' => 4],
            ['hora' => '14:15', 'ocupacion' => 4],
            ['hora' => '14:30', 'ocupacion' => 9],
            ['hora' => '14:45', 'ocupacion' => 11],
            ['hora' => '15:00', 'ocupacion' => 11],
            ['hora' => '15:15', 'ocupacion' => 14],
            ['hora' => '15:30', 'ocupacion' => 10],
            ['hora' => '15:45', 'ocupacion' => 8],
            ['hora' => '16:00', 'ocupacion' => 5]
        ];

        $resultado = $this->gestor->calcularOcupacionPorIntervalo('rest1', '2025-03-25');

        $this->assertCount(count($esperado), $resultado, "Comprueba el total de intervalos");

        foreach ($esperado as $i => $intervaloEsperado) {
            $this->assertEquals($intervaloEsperado['hora'], $resultado[$i]['hora'], "Comprueba la hora");
            $this->assertEquals($intervaloEsperado['ocupacion'], $resultado[$i]['ocupacion'], "Fallo en la hora {$intervaloEsperado['hora']}");
        }
    }
}
