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
            ->assertSee('css/styles.css', false)
            ->assertSee('/img/user-check-large.png', false);
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

    public function test_registration_redirects_to_login_and_allows_login_with_temporary_credentials(): void
    {
        $this->get('/registrarse')->assertRedirect('/datos');

        $datosRegistro = [
            'curp' => 'GOPV850101HDFXXX01',
            'nombres' => 'Victor',
            'rfc' => 'GOPV850101ABC',
            'apellido_p' => 'Godinez',
            'password' => 'clave-segura',
            'password_conf' => 'clave-segura',
            'cedula' => '12345678',
            'calle' => 'Calle Uno',
            'estado' => 'Ciudad de Mexico',
            'num_ext' => '10',
            'colonia' => 'Centro',
            'municipio' => 'Cuauhtemoc',
            'cp' => '06000',
            'correo' => 'victor@example.com',
            'tel_particular' => '5555555555',
            'tel_celular' => '5512345678',
        ];

        $this->post('/datos', $datosRegistro)
            ->assertRedirect('/index')
            ->assertSessionHas('registro_temporal.correo', 'victor@example.com')
            ->assertSessionMissing('registro_iniciado');

        $this->from('/index')->post('/login', [
            'usuario' => 'victor@example.com',
            'contrasena' => 'incorrecta',
        ])->assertRedirect('/index')->assertSessionHasErrors('usuario');

        $this->post('/login', [
            'usuario' => 'victor@example.com',
            'contrasena' => 'clave-segura',
        ])->assertRedirect('/bienvenida');
    }

    public function test_registration_rejects_mismatched_password_confirmation(): void
    {
        $this->get('/registrarse');

        $this->from('/datos')->post('/datos', [
            'password' => 'clave-segura',
            'password_conf' => 'otra-clave',
        ])->assertRedirect('/datos')->assertSessionHasErrors('password_conf');
    }

    public function test_certification_page_renders_with_a_valid_form_action(): void
    {
        $this->get('/certificacion')
            ->assertOk()
            ->assertSee('action="'.route('certificacion.guardar').'"', false);
    }

    public function test_certification_submission_requires_an_art_14_answer(): void
    {
        $this->from('/certificacion')->post(route('certificacion.guardar'), [])
            ->assertRedirect('/certificacion')
            ->assertSessionHasErrors([
                'aplica_art_14' => 'Selecciona si corresponde a Refrendo por Art. 14.',
            ]);
    }

    public function test_art_14_submission_requires_an_inciso(): void
    {
        $this->from('/certificacion')->post(route('certificacion.guardar'), [
            'aplica_art_14' => 'si',
        ])->assertRedirect('/certificacion')
            ->assertSessionHasErrors([
                'inciso_art_14' => 'Selecciona el inciso del Art. 14.',
            ]);
    }

    public function test_art_14_submission_rejects_an_invalid_inciso(): void
    {
        $this->from('/certificacion')->post(route('certificacion.guardar'), [
            'aplica_art_14' => 'si',
            'inciso_art_14' => '14.5',
        ])->assertRedirect('/certificacion')
            ->assertSessionHasErrors([
                'inciso_art_14' => 'El inciso seleccionado no es válido.',
            ]);
    }

    public function test_valid_art_14_submission_is_saved_in_the_session(): void
    {
        $this->post(route('certificacion.guardar'), [
            'aplica_art_14' => 'si',
            'inciso_art_14' => '14.2',
        ])->assertRedirect(route('certificacion'))
            ->assertSessionHas('certificacion', [
                'aplica_art_14' => 'si',
                'inciso_art_14' => '14.2',
            ])
            ->assertSessionHas('status', 'La certificación se guardó correctamente.');
    }

    public function test_valid_non_art_14_submission_does_not_require_an_inciso(): void
    {
        $this->post(route('certificacion.guardar'), [
            'aplica_art_14' => 'no',
        ])->assertRedirect(route('certificacion'))
            ->assertSessionHas('certificacion.aplica_art_14', 'no')
            ->assertSessionHas('status', 'La certificación se guardó correctamente.');
    }
}
