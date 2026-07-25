<?php

declare(strict_types=1);

function metafitApiBaseUrl(): string
{
    $configuredUrl = getenv('METAFIT_API_BASE_URL');

    if (is_string($configuredUrl) && $configuredUrl !== '') {
        return rtrim($configuredUrl, '/');
    }

    $host = $_SERVER['HTTP_HOST'] ?? '';

    return str_contains($host, 'localhost')
        ? 'http://localhost:3333'
        : 'https://metafit-api.rocket.srv.br';
}

/**
 * @return array<string, mixed>|null
 */
function metafitCurrentUser(string $token): ?array
{
    $request = curl_init(metafitApiBaseUrl() . '/users/me');

    if ($request === false) {
        return null;
    }

    curl_setopt_array($request, [
        CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $token, 'Accept: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_CONNECTTIMEOUT => 5,
    ]);

    $response = curl_exec($request);
    $status = (int) curl_getinfo($request, CURLINFO_RESPONSE_CODE);
    curl_close($request);

    if (!is_string($response) || $status < 200 || $status >= 300) {
        return null;
    }

    $user = json_decode($response, true);

    return is_array($user) ? $user : null;
}
