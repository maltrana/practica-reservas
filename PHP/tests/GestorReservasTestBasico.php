<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Before;
 

require_once __DIR__ . '/../src/GestorReservas.php';

class OcupacionBasicaTest extends TestCase
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

    public function testCalcularOcupacionBasico(): void
    {
        $this->assertEquals(14, $this->gestor->calcularOcupacion('rest1', '2025-03-25'));
        $this->assertEquals(4, $this->gestor->calcularOcupacion('rest1', '2025-03-26'));
    }

    public function testEstaCompletoBasico(): void
    {
        $this->assertFalse($this->gestor->estaCompleto('rest1', '2025-03-25', 5));
        $this->assertTrue($this->gestor->estaCompleto('rest1', '2025-03-25', 30));
    }
}
