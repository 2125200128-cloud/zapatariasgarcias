<?php

namespace App\View\Composers;

use Illuminate\View\View;

class MenuComposer
{
    public function compose(View $view): void
    {
        $apiUser = session('api_user');

        $esAdmin = (bool) ($apiUser['esAdministrador'] ?? false);
        $esMatriz = (bool) ($apiUser['esMatriz'] ?? false);

        $view->with([
            'apiUser' => $apiUser,
            'esAdmin' => $esAdmin,
            'esMatriz' => $esMatriz,
            'puedeGestionar' => $esAdmin || $esMatriz,
        ]);
    }
}