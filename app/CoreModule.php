<?php

/**
 * Please Don't delete this file.
 * if you expert in PHP you can delete this file
 */

namespace App;

use App\Controllers\WelcomeController;
use Jauhar\System\Module;

class CoreModule extends Module
{
    public function getControllers(): array
    {
        return [
            WelcomeController::class
        ];
    }
}
