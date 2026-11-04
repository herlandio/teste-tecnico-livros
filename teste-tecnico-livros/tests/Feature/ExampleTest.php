<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_pagina_inicial_redireciona_para_lista_de_livros(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('livros.index'));
    }
}
