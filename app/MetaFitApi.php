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

function metafitApiRequest(string $token, string $method, string $path, array $payload): bool
{
    $request = curl_init(metafitApiBaseUrl() . $path);
    if ($request === false) return false;
    curl_setopt_array($request, [
        CURLOPT_CUSTOMREQUEST => $method, CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
        CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $token, 'Accept: application/json', 'Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 15, CURLOPT_CONNECTTIMEOUT => 5,
    ]);
    curl_exec($request);
    $status = (int) curl_getinfo($request, CURLINFO_RESPONSE_CODE);
    curl_close($request);
    return $status >= 200 && $status < 300;
}

/** @param array<string, mixed> $answers */
function metafitRegisterInitialData(string $token, array $answers): bool
{
    $success = true;
    $sexes = ['Masculino' => 'masculino', 'Feminino' => 'feminino', 'Prefiro não informar' => 'nao_informado'];
    $userData = [];
    if (!empty($answers['birthDate'])) $userData['data_nascimento'] = $answers['birthDate'];
    if (isset($sexes[$answers['sex'] ?? ''])) $userData['sexo'] = $sexes[$answers['sex']];
    if ($userData !== []) $success = metafitApiRequest($token, 'PATCH', '/users/me', $userData) && $success;

    if (!empty($answers['currentWeight']) && (float) $answers['currentWeight'] > 0) $success = metafitApiRequest($token, 'POST', '/events', ['event' => 'peso', 'value' => (float) $answers['currentWeight'], 'source' => 'api']) && $success;
    if (!empty($answers['height']) && (float) $answers['height'] > 0) {
        $height = (float) $answers['height'];
        $success = metafitApiRequest($token, 'POST', '/events', ['event' => 'altura', 'value' => $height > 3 ? $height / 100 : $height, 'source' => 'api']) && $success;
    }

    if (($answers['hasGoalWeight'] ?? null) === 'Sim' && !empty($answers['goalWeight']) && (float) $answers['goalWeight'] > 0) {
        $goal = ['goal' => 'peso', 'type' => 'objetivo', 'value' => (float) $answers['goalWeight']];
        if (preg_match('/^(\d+) (mês|meses|ano|anos)$/u', (string) ($answers['goalDeadline'] ?? ''), $match) === 1) {
            $goal['target_date'] = (new DateTimeImmutable('today'))->modify('+' . $match[1] . (str_starts_with($match[2], 'ano') ? ' years' : ' months'))->format('Y-m-d');
        }
        $success = metafitApiRequest($token, 'POST', '/goals', $goal) && $success;
    }

    $dailyGoals = is_array($answers['nutritionPlan'] ?? null) ? $answers['nutritionPlan'] : [];
    foreach (['agua', 'calorias', 'proteinas', 'carboidratos', 'gorduras'] as $goalName) {
        if (!empty($dailyGoals[$goalName]) && (float) $dailyGoals[$goalName] > 0) {
            $success = metafitApiRequest($token, 'POST', '/goals', ['goal' => $goalName, 'type' => 'diaria', 'value' => (float) $dailyGoals[$goalName]]) && $success;
        }
    }
    return $success;
}
