<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class ZapatariaApiClient
{
    protected string $baseUrl;

    public function __construct(?string $baseUrl = null)
    {
        $this->baseUrl = rtrim($baseUrl ?? env('ZAPATERIA_API_URL', 'http://localhost/ZAPATERIA_API/public'), '/');
    }

    public function login(string $usuario, string $password): array
    {
        return $this->post('/api/auth/login', [
            'usuario' => $usuario,
            'password' => $password,
        ]);
    }

    public function logout(string $token): array
    {
        return $this->post('/api/auth/logout', [], $token);
    }

    public function get(string $path, ?string $token = null): array
    {
        return $this->request('get', $path, [], $token);
    }

    public function post(string $path, array $data = [], ?string $token = null): array
    {
        return $this->request('post', $path, $data, $token);
    }

    public function put(string $path, array $data = [], ?string $token = null): array
    {
        return $this->request('put', $path, $data, $token);
    }

    public function delete(string $path, ?string $token = null): array
    {
        return $this->request('delete', $path, [], $token);
    }

    // Para endpoints que NO devuelven JSON (ej. el PDF de un pedido).
    // Devuelve la respuesta HTTP tal cual, sin intentar parsear el body.
    public function getRaw(string $path, ?string $token = null): Response
    {
        $request = Http::baseUrl($this->baseUrl);

        if ($token) {
            $request = $request->withToken($token);
        }

        return $request->get($path);
    }

    protected function request(string $method, string $path, array $data = [], ?string $token = null): array
{
    $request = Http::acceptJson()->baseUrl($this->baseUrl);

    if ($token) {
        $request = $request->withToken($token);
    }

    // 1. Detectar si hay algún archivo subido en el array $data
    $hasFiles = false;
    foreach ($data as $value) {
        if ($value instanceof \Illuminate\Http\UploadedFile) {
            $hasFiles = true;
            break;
        }
    }

    // 2. Si hay archivos, los enviamos como multipart/form-data
    if ($hasFiles) {
        // PHP no lee archivos en peticiones HTTP PUT directas.
        // Si el método es PUT y lleva archivo, enviamos por POST con _method = PUT (Method Spoofing).
        if (strtolower($method) === 'put') {
            $method = 'post';
            $data['_method'] = 'PUT';
        }

        // Adjuntamos cada archivo encontrado
        foreach ($data as $key => $value) {
            if ($value instanceof \Illuminate\Http\UploadedFile) {
                $request->attach(
                    $key,
                    file_get_contents($value->getPathname()),
                    $value->getClientOriginalName()
                );
                unset($data[$key]); // Lo quitamos del array plano para no duplicarlo
            }
        }

        // Ejecutamos la petición POST multipart
        $response = $request->post($path, $data);
    } else {
        // Petición normal JSON sin archivos
        $response = $request->{$method}($path, $data);
    }

    if (!$response->successful()) {
        $payload = $response->json() ?? [];
        $message = $payload['message'] ?? null;
        if (!$message && isset($payload['errors'])) {
            $message = collect($payload['errors'])
                ->flatten()
                ->filter(fn ($item) => is_string($item) && $item !== '')
                ->implode(' ');
        }

        $message = $message ?: 'No fue posible completar la petición en la API.';
        throw new RuntimeException($message);
    }

    return $response->json() ?? [];
}
}