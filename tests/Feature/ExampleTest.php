<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_demo_login_redirects_to_welcome_page(): void
    {
        $this->post('/login', [
            'usuario' => 'profegodi',
            'contrasena' => '676767',
        ])->assertRedirect('/bienvenida');
    }

    public function test_welcome_page_loads_with_shared_stylesheet(): void
    {
        $this->get('/bienvenida')
            ->assertOk()
            ->assertSee('bienvenida-main', false)
            ->assertSee('css/styles.css', false);
    }

    public function test_invalid_login_does_not_redirect_to_registration_data(): void
    {
        $this->from('/index')->post('/login', [
            'usuario' => 'profegodi',
            'contrasena' => 'incorrecta',
        ])->assertRedirect('/index')->assertSessionHasErrors('usuario');
    }

    public function test_registration_is_the_only_link_to_registration_data(): void
    {
        $this->get('/index')
            ->assertOk()
            ->assertSee('/registrarse"', false)
            ->assertDontSee("window.location.href='/datos'", false);

        $this->get('/editarDatos')->assertRedirect('/index');
    }

    public function test_registration_data_requires_the_registration_link(): void
    {
        $this->get('/datos')->assertRedirect('/index');

        $this->get('/registrarse')->assertRedirect('/datos');
        $this->get('/datos')->assertOk();
    }
}
