<?php

use App\Models\User;

it('can switch language to spanish', function () {
    $response = $this->get('/language/es');

    $response->assertRedirect();
    expect(session('locale'))->toBe('es');
});

it('can switch language to english', function () {
    $response = $this->get('/language/en');

    $response->assertRedirect();
    expect(session('locale'))->toBe('en');
});

it('returns 404 for invalid locale', function () {
    $response = $this->get('/language/fr');

    $response->assertNotFound();
});

it('persists language for authenticated users', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/language/es')
        ->assertRedirect();

    expect(session('locale'))->toBe('es');

    // Language should persist across requests
    $this->actingAs($user)
        ->get('/dashboard');

    expect(app()->getLocale())->toBe('es');
});

it('auto-detects language from browser on first visit', function () {
    // Test with Spanish browser preference
    $this->withHeaders(['Accept-Language' => 'es-ES,es;q=0.9'])
        ->get('/');

    expect(app()->getLocale())->toBe('es');
});

it('sets locale in html tag', function () {
    $this->get('/language/es');

    $this->get('/login')
        ->assertSee('<html lang="es">', false);
});
