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

function metafitUserId(array $user): ?string
{
    $id = $user['id'] ?? $user['_id'] ?? $user['user']['id'] ?? $user['user']['_id'] ?? null;

    return is_string($id) && $id !== '' ? $id : null;
}

/**
 * @param array<string, mixed> $answers
 */
function metafitSubmitTriage(string $token, string $userId, array $answers): bool
{
    $request = curl_init(metafitApiBaseUrl() . '/users/' . rawurlencode($userId) . '/triagem');

    if ($request === false) {
        return false;
    }

    $payload = json_encode(['respostas' => $answers], JSON_UNESCAPED_UNICODE);

    curl_setopt_array($request, [
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $token,
            'Accept: application/json',
            'Content-Type: application/json',
        ],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_CONNECTTIMEOUT => 5,
    ]);

    curl_exec($request);
    $status = (int) curl_getinfo($request, CURLINFO_RESPONSE_CODE);
    curl_close($request);

    return $status >= 200 && $status < 300;
}

/** @return array<string, mixed>|null */
function metafitNutritionPlanSuggestion(string $token, array $data): ?array
{
    $request = curl_init(metafitApiBaseUrl() . '/ai/nutrition-plan-suggestion');
    if ($request === false) return null;
    curl_setopt_array($request, [
        CURLOPT_CUSTOMREQUEST => 'POST', CURLOPT_POSTFIELDS => json_encode(['dados' => $data], JSON_UNESCAPED_UNICODE),
        CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $token, 'Accept: application/json', 'Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 30, CURLOPT_CONNECTTIMEOUT => 5,
    ]);
    $response = curl_exec($request);
    $status = (int) curl_getinfo($request, CURLINFO_RESPONSE_CODE);
    curl_close($request);
    if (!is_string($response) || $status < 200 || $status >= 300) return null;
    $result = json_decode($response, true);
    return is_array($result['metas'] ?? null) ? $result['metas'] : null;
}
