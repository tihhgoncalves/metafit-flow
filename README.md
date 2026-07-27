# MetaFit Flow

Experiências web complementares ao atendimento do MetaFit no WhatsApp.

## Ambiente local

Com o PHP instalado, inicie o servidor local:

```powershell
php -S metafit-flow.localhost:8000 router.php
```

Depois, acesse `http://metafit-flow.localhost:8000`.

> Para usar exatamente `http://metafit-flow.localhost`, configure o domínio no seu ambiente local (por exemplo, Herd, Valet ou um virtual host apontando para a pasta do projeto).

Em servidores Apache, os arquivos `.htaccess` da raiz e de `public/` encaminham URLs como `/triagem/{token}` para a aplicação. O módulo `mod_rewrite` precisa estar habilitado e o virtual host deve permitir `AllowOverride FileInfo` (ou `AllowOverride All`).

## Estrutura

- `index.php`: ponto de entrada principal do projeto, adequado para configurações em que a raiz do repositório é o diretório público.
- `public/pages/`: uma página PHP para cada interação (por exemplo, `triagem.php`).
- `public/assets/`: estilos e scripts compartilhados ou específicos de cada interação.
- `public/index.php`: ponto central das rotas públicas, chamado pelo `index.php` raiz.
- `router.php`: roteador temporário para o servidor embutido do PHP.

## Rotas

| URL | Interação |
| --- | --- |
| `/triagem/{token}` | Ponto de partida do usuário. |

Para adicionar uma nova experiência, crie uma página em `public/pages/` e associe a rota no início de `public/index.php`. Assim, uma próxima interação pode ficar disponível em `/assunto2` sem interferir no ponto de partida.

## API

O ponto de partida exige um token na URL: `/triagem/{token}`. O servidor consulta `GET /users/me` com o cabeçalho `Authorization: Bearer {token}` antes de exibir o fluxo. Ao concluir, o Flow envia `POST /users/{id}/triagem` com as respostas no objeto `respostas`; o token permanece no servidor e é reutilizado nessa chamada.

As respostas são enviadas como uma lista de objetos com `pergunta` e `resposta`, preservando o texto de cada pergunta exibida no fluxo.

A API é selecionada automaticamente conforme o host:

- Local: `http://localhost:3333`
- Produção: `https://metafit-api.rocket.srv.br`

Para substituir essa seleção, defina a variável de ambiente `METAFIT_API_BASE_URL` no servidor.

O ponto de partida carrega os dados do usuário e envia as respostas pela API.

## Estilos e versão

Os estilos-fonte ficam em `scss/`. Para gerar o CSS público, instale as dependências e execute:

```powershell
npm install
npm run build
```

O número em `package.json` é a versão da aplicação: ele aparece no rodapé e é usado no parâmetro de cache do `style.css`.

## Deploy

O workflow do GitHub Actions em `.github/workflows/deploy.yml` é executado a cada push para `main` (ou manualmente) e publica o projeto em `/public_html/flow/` via FTP. Ele usa os secrets `FTP_HOST`, `FTP_USER` e `FTP_PASSWORD` já cadastrados no repositório.
