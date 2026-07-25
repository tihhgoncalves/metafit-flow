<?php

declare(strict_types=1);

$route = rtrim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/', '/') ?: '/';

if ($route === '/triagem') {
    require __DIR__ . '/pages/triagem.php';
    return;
}

http_response_code($route === '/' ? 200 : 404);
?><!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#13352d">
    <meta name="description" content="Experiências web do MetaFit.">
    <title>MetaFit Flow</title>
    <link rel="stylesheet" href="/public/assets/css/app.css">
</head>
<body>
    <main class="page-shell">
        <section class="flow-card flow-card--home" aria-labelledby="flow-title">
            <header class="flow-header">
                <a class="brand" href="/" aria-label="MetaFit Flow, início"><img src="/public/assets/images/metafit-logo.svg" alt="MetaFit"></a>
                <span class="flow-label">Experiências digitais</span>
            </header>
            <?php if ($route === '/'): ?>
                <div class="home-content">
                    <p class="eyebrow">Olá</p>
                    <h1 id="flow-title">Seu próximo passo começa aqui.</h1>
                    <p class="description">Quando o MetaFit enviar um link no WhatsApp, você chegará a uma experiência preparada para aquele momento.</p>
                    <a class="button" href="/triagem">Iniciar triagem</a>
                </div>
            <?php else: ?>
                <div class="home-content">
                    <p class="eyebrow">Página não encontrada</p>
                    <h1 id="flow-title">Não encontramos esta experiência.</h1>
                    <p class="description">Verifique o link recebido ou volte para o início.</p>
                    <a class="button" href="/">Voltar ao início</a>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
