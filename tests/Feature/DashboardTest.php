<?php

use App\Enums\PermissionEnum;

test('guests are redirected to the login page', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

test('authenticated users can visit the dashboard', function () {
    $this->actingAsUserWithPermissions([PermissionEnum::DASHBOARD_VIEW]);

    $this->get('/dashboard')->assertStatus(200);
});
