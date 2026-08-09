<?php

namespace App\Http\Controllers;

use App\Services\ZapatariaApiClient;
use Illuminate\Support\Collection;

abstract class ApiFrontController extends Controller
{
    protected function client(): ZapatariaApiClient
    {
        return app(ZapatariaApiClient::class);
    }

    protected function token(): ?string
    {
        return session('api_token');
    }

    protected function currentUser(): ?array
    {
        $user = session('api_user');

        return is_array($user) ? $user : null;
    }

    // Reflejan tal cual lo que ahora devuelve /api/auth/login. No hay que
    // volver a adivinar el rol aquí — esAdministrador/esMatriz ya vienen
    // calculados en la API con Empleado::esAdministrador()/esMatriz().
    // puedeGestionar() es el mismo criterio que puedeAsignar() usa en
    // todos los controladores de la API (admin o encargado de la matriz).
    protected function esAdmin(): bool
    {
        return (bool) ($this->currentUser()['esAdministrador'] ?? false);
    }

    protected function esMatriz(): bool
    {
        return (bool) ($this->currentUser()['esMatriz'] ?? false);
    }

    protected function puedeGestionar(): bool
    {
        return $this->esAdmin() || $this->esMatriz();
    }

    protected function normalizePayload(mixed $value): mixed
    {
        if (is_array($value)) {
            if (array_is_list($value)) {
                return collect(array_map(fn ($item) => $this->normalizePayload($item), $value));
            }

            $object = new \stdClass();
            foreach ($value as $key => $item) {
                $object->{$key} = $this->normalizePayload($item);
            }

            return $object;
        }

        return $value;
    }

    protected function normalizeCollection(mixed $value): Collection
    {
        return collect($this->normalizePayload($value));
    }
}