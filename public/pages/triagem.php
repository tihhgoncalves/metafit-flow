<?php

declare(strict_types=1);
?><!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#e7236d">
    <meta name="description" content="Triagem inicial do MetaFit.">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Triagem inicial | MetaFit Flow">
    <meta property="og:description" content="Conte um pouco sobre você para personalizarmos sua experiência no MetaFit.">
    <meta property="og:image" content="https://flow.usemetafit.com/public/assets/images/social.png">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Triagem inicial | MetaFit Flow">
    <meta name="twitter:description" content="Conte um pouco sobre você para personalizarmos sua experiência no MetaFit.">
    <meta name="twitter:image" content="https://flow.usemetafit.com/public/assets/images/social.png">
    <title>Triagem | MetaFit Flow</title>
    <link rel="icon" type="image/png" href="/public/assets/images/favico.png">
    <link rel="stylesheet" href="/public/assets/css/style.css?v=<?= urlencode($appVersion) ?>">
</head>
<body>
    <main class="page-shell">
        <div class="flow-wrapper">
            <section class="flow-card" aria-labelledby="flow-title">
            <header class="flow-header">
                <a class="brand" href="/" aria-label="MetaFit Flow, início"><img src="/public/assets/images/metafit-logo.svg" alt="MetaFit"></a>
                <span class="flow-label">Triagem inicial</span>
            </header>
            <div class="progress" aria-label="Progresso da triagem"><div class="progress__bar"><span id="progress-bar"></span></div><span id="progress-text">1 de 4</span></div>
            <div id="question-screen">
                <p class="eyebrow" id="question-category">Vamos começar</p>
                <h1 id="flow-title">Quero conhecer você melhor.</h1>
                <p class="description" id="question-description">Essas respostas nos ajudam a preparar uma experiência mais adequada para o seu momento.</p>
                <form id="triage-form" novalidate>
                    <fieldset id="answers" class="answers"></fieldset>
                    <p class="field-error" id="field-error" role="alert" hidden>Escolha uma opção para continuar.</p>
                    <div class="actions"><button class="button button--secondary" id="previous-button" type="button" hidden>Voltar</button><button class="button" id="next-button" type="submit">Continuar</button></div>
                </form>
            </div>
            <div id="completion-screen" class="completion" hidden>
                <div class="completion__icon" aria-hidden="true">✓</div><p class="eyebrow">Tudo certo</p><h1>Obrigado por compartilhar.</h1>
                <p class="description">Recebemos suas respostas. Em breve, você continuará a conversa pelo WhatsApp.</p><a class="button" href="/">Voltar ao início</a>
            </div>
            </section>
            <footer class="flow-footer">MetaFit Flow <span aria-label="Versão">v<?= htmlspecialchars($appVersion, ENT_QUOTES, 'UTF-8') ?></span></footer>
        </div>
    </main>
    <script src="/public/assets/js/triage.js" defer></script>
</body>
</html>
