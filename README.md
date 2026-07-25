# MetaFit Flow

Experiências web complementares ao atendimento do MetaFit no WhatsApp.

## Ambiente local

Com o PHP instalado, inicie o servidor local:

```powershell
php -S metafit-flow.localhost:8000 router.php
```

Depois, acesse `http://metafit-flow.localhost:8000`.

> Para usar exatamente `http://metafit-flow.localhost`, configure o domínio no seu ambiente local (por exemplo, Herd, Valet ou um virtual host apontando para a pasta do projeto).

## Estrutura

- `index.php`: ponto de entrada principal do projeto, adequado para configurações em que a raiz do repositório é o diretório público.
- `public/pages/`: uma página PHP para cada interação (por exemplo, `triagem.php`).
- `public/assets/`: estilos e scripts compartilhados ou específicos de cada interação.
- `public/index.php`: ponto central das rotas públicas, chamado pelo `index.php` raiz.
- `router.php`: roteador temporário para o servidor embutido do PHP.

## Rotas

| URL | Interação |
| --- | --- |
| `/triagem` | Questionário inicial do usuário. |

Para adicionar uma nova experiência, crie uma página em `public/pages/` e associe a rota no início de `public/index.php`. Assim, uma próxima interação pode ficar disponível em `/assunto2` sem interferir na triagem.

Nesta primeira versão a triagem é somente demonstrativa. O envio e o carregamento de dados serão conectados a uma API futuramente.
