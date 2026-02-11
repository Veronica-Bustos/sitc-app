<?php

namespace App\Http\Controllers\Settings;

use App\Enums\PermissionEnum;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AppearanceController extends Controller
{
    public function edit(): View
    {
        Gate::authorize(PermissionEnum::SETTINGS_APPEARANCE->value);

        return view('settings.appearance');
    }
}
