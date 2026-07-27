<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/MetaFitApi.php';

$package = json_decode((string) file_get_contents(__DIR__ . '/../package.json'), true);
$appVersion = is_array($package) && isset($package['version']) ? (string) $package['version'] : '0.0.0';
$route = rtrim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/', '/') ?: '/';

if (preg_match('#^/triagem/([A-Za-z0-9_-]{32,512})/sugestao-plano$#', $route, $matches) === 1 && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $payload = json_decode((string) file_get_contents('php://input'), true);
    $data = is_array($payload['dados'] ?? null) ? $payload['dados'] : [];
    $metas = metafitNutritionPlanSuggestion($matches[1], $data);
    header('Content-Type: application/json; charset=utf-8');
    http_response_code($metas === null ? 502 : 200);
    echo json_encode(['metas' => $metas], JSON_UNESCAPED_UNICODE);
    return;
}

if (preg_match('#^/triagem/([A-Za-z0-9_-]{32,512})$#', $route, $matches) === 1) {
    $triageToken = $matches[1];
    $triageUser = metafitCurrentUser($triageToken);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json; charset=utf-8');
        $requestBody = json_decode((string) file_get_contents('php://input'), true);
        $answers = is_array($requestBody['respostas'] ?? null) ? $requestBody['respostas'] : null;
        $initialData = is_array($requestBody['dados_iniciais'] ?? null) ? $requestBody['dados_iniciais'] : [];
        $userId = $triageUser !== null ? metafitUserId($triageUser) : null;

        if ($answers === null || $userId === null) {
            http_response_code(422);
            echo json_encode(['ok' => false]);
            return;
        }

        $submitted = metafitSubmitTriage($triageToken, $userId, $answers);
        $initialDataRegistered = $submitted ? metafitRegisterInitialData($triageToken, $initialData) : false;
        http_response_code($submitted ? 200 : 502);
        echo json_encode(['ok' => $submitted, 'dados_iniciais_registrados' => $initialDataRegistered]);
        return;
    }

    require __DIR__ . '/pages/triagem.php';
    return;
}

http_response_code(404);
?><!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#e7236d">
    <meta name="description" content="Página indisponível.">
    <meta name="robots" content="noindex, nofollow">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Página indisponível | MetaFit Flow">
    <meta property="og:description" content="Esta página não pode ser exibida.">
    <meta property="og:image" content="https://flow.usemetafit.com/public/assets/images/social.png">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Página indisponível | MetaFit Flow">
    <meta name="twitter:description" content="Esta página não pode ser exibida.">
    <meta name="twitter:image" content="https://flow.usemetafit.com/public/assets/images/social.png">
    <title>Página indisponível | MetaFit Flow</title>
    <link rel="icon" type="image/png" href="/public/assets/images/favico.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/public/assets/css/style.css?v=<?= urlencode($appVersion) ?>">
</head>
<body>
    <main class="page-shell">
        <div class="flow-wrapper">
            <section class="flow-card flow-card--home" aria-labelledby="flow-title">
            <header class="flow-header">
                <a class="brand" href="/" aria-label="MetaFit Flow, início"><img src="/public/assets/images/metafit-logo.svg" alt="MetaFit"></a>
                <span class="flow-label">Sua saúde, com inteligência.</span>
            </header>
            <div class="home-content">
                <p class="eyebrow">Página indisponível</p>
                <h1 id="flow-title">Esta página não pode ser exibida.</h1>
                <p class="description">Verifique o link recebido pelo WhatsApp e tente novamente.</p>
            </div>
            </section>
            <footer class="flow-footer">MetaFit Flow <span aria-label="Versão">v<?= htmlspecialchars($appVersion, ENT_QUOTES, 'UTF-8') ?></span></footer>
        </div>
    </main>
</body>
</html>
