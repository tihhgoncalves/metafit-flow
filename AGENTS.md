# Workflow Git

## Visão geral

Este projeto utiliza duas branches permanentes:

| Branch | Finalidade |
| --- | --- |
| `main` | Código oficialmente publicado em produção. |
| `develop` | Integração e homologação. |

O trabalho é separado entre branches de tarefa, que contêm somente a implementação, e branches de release, que preparam uma versão publicável.

```text
main → task/<slug> → release/<versão> → Pull Request → ambiente
```

`develop` nunca é base para novas tarefas nem origem de uma publicação em produção. Nunca faça `develop → main`.

## Branches

### Branches permanentes

- `main` contém somente alterações oficialmente publicadas em produção.
- `develop` reúne tarefas e releases destinados à homologação. Pode conter mais de uma tarefa em validação.

### Branches de tarefa

Use `task/<slug>` para implementar uma demanda:

```text
task/login-google
task/site-fora-do-ar
task/erro-no-checkout
```

Crie toda tarefa a partir de `main`. Não use prefixos técnicos, como `feat/`, `fix/` ou `refactor/`, no nome da branch.

As branches de tarefa são criadas e gerenciadas manualmente pelo usuário. O agente não deve criar, renomear ou excluir `task/<slug>`; ele atua a partir de uma branch de tarefa já existente.

A branch da tarefa não é usada diretamente para abrir Pull Request de homologação ou produção. Ela deve conter apenas a implementação necessária para ser incorporada em uma release.

### Branches de release

Use `release/<versão>` para preparar e publicar uma versão:

| Formato | Tipo | Base | Destino do PR |
| --- | --- | --- | --- |
| `release/X.Y.Z.beta-N` | Homologação | `develop` | `develop` |
| `release/X.Y.Z` | Produção | `main` | `main` |

Exemplos:

```text
release/1.2.3.beta-1
release/1.2.3
```

A presença de `.beta-N` identifica uma release de homologação; versões estáveis identificam releases de produção.

## Desenvolvimento de uma tarefa

O usuário cria manualmente a `task/<slug>` a partir de `main`. Ao trabalhar em uma tarefa já criada:

1. Confirme que a branch atual segue o padrão `task/<slug>`.
2. Implemente exclusivamente o escopo da tarefa.
3. Não incorpore alterações exclusivas de `develop`.
4. Execute testes, lint e build aplicáveis.
5. Crie os commits necessários.

Nunca faça commits diretamente em `main` ou `develop`.

## Commits

Utilize Conventional Commits, sempre em PT-BR.

| Tipo | Uso |
| --- | --- |
| `feat:` | Nova funcionalidade. |
| `fix:` | Correção. |
| `refactor:` | Refatoração. |
| `docs:` | Documentação. |
| `test:` | Testes. |
| `chore:` | Manutenção. |

Exemplos:

```text
feat: adiciona autenticação com Google
fix: corrige cálculo do valor total do pedido
refactor: reorganiza serviço de autenticação
chore: atualiza dependências do projeto
```

Os commits devem ser criados automaticamente quando necessários para concluir uma operação solicitada. Em fluxos de publicação, não é preciso solicitar separadamente `git add`, `git commit` ou `git push`.

## Preparação de release

Uma release é criada pela automação a partir da branch do ambiente de destino e incorpora a `task/<slug>` correspondente. O Pull Request é aberto pela branch de release, nunca diretamente pela branch de tarefa.

Antes de abrir o PR, a automação deve:

1. Confirmar que a tarefa contém somente alterações do seu escopo.
2. Criar a `release/<versão>` a partir de `develop` para HML ou de `main` para PRD.
3. Incorporar a `task/<slug>` na branch de release.
4. Atualizar os arquivos de versão aplicáveis.
5. Executar testes, lint e build aplicáveis.
6. Criar os commits, enviar a branch e abrir o Pull Request.

Somente uma release de homologação deve estar em preparação ou aberta por vez. Isso evita que duas releases calculem o mesmo número beta.

## Homologação

Homologação é opcional e serve para validar uma tarefa antes da produção.

Quando for solicitada, a automação cria uma branch `release/X.Y.Z.beta-N` a partir de `develop`, incorpora a tarefa e abre um PR para `develop`, identificado com `Destino: Homologação`.

### Versionamento de homologação

Use `X.Y.Z.beta-N`, em que `X.Y.Z` é a última versão oficial de `main` e `N` é o número sequencial de publicações em homologação desde essa versão.

```text
1.2.3        → 1.2.3.beta-1
1.2.3.beta-4 → 1.2.3.beta-5
```

Regras:

- A automação calcula a próxima beta a partir da versão atual de `develop` imediatamente antes de criar a release.
- A release atualiza `package.json` para a versão beta calculada.
- Durante homologação, altere apenas `N`; nunca antecipe `PATCH`, `MINOR` ou `MAJOR`.
- Releases beta não atualizam o `CHANGELOG.md` público.
- O merge do PR de release em `develop` dispara o deploy de HML já com a versão correta.

## Produção

Uma tarefa pode seguir para produção após homologação ou diretamente, quando solicitado. A release de produção sempre parte de `main`; nunca de `develop`.

Quando for solicitada, a automação cria uma branch `release/X.Y.Z` a partir de `main`, incorpora a `task/<slug>` e abre um PR para `main`, identificado com `Destino: Produção`.

### Versionamento de produção

Use o formato `MAJOR.MINOR.PATCH`.

| Tipo | Quando usar | Exemplo |
| --- | --- | --- |
| PATCH | Correções ou mudanças compatíveis sem funcionalidade relevante. | `1.2.3 → 1.2.4` |
| MINOR | Novas funcionalidades ou melhorias relevantes compatíveis. | `1.2.3 → 1.3.0` |
| MAJOR | Alterações incompatíveis ou breaking changes. | `1.2.3 → 2.0.0` |

A automação pode sugerir o tipo de release a partir de commits e Pull Requests desde a última versão oficial: `fix` sugere PATCH, `feat` sugere MINOR e `!` ou `BREAKING CHANGE` sugere MAJOR. A versão e as notas devem ser revisadas antes do merge do PR de produção.

Antes de abrir o PR, a release de produção atualiza `package.json` e `CHANGELOG.md` com a versão oficial definida.

O merge pode ocorrer fora do Codex. Depois que ele for confirmado em `main`, crie a tag da versão e execute o deploy de produção a partir dessa tag.

## Changelog

Mantenha `CHANGELOG.md` na raiz para documentar exclusivamente versões oficiais de produção.

Ao preparar uma release de produção:

1. Adicione no topo uma seção com a versão e a data da publicação.
2. Baseie o conteúdo nas alterações efetivamente incluídas desde a última versão oficial.
3. Escreva para usuários, clientes e equipe; não copie mensagens de commit.
4. Use, quando aplicável, as categorias `Novidades`, `Melhorias`, `Correções`, `Segurança` e `Breaking Changes`.
5. Não crie categorias vazias nem inclua detalhes internos sem impacto perceptível.

Exemplo:

```markdown
## [1.3.0] - 2026-08-07

### Novidades

- Adicionado login com Google.

### Correções

- Corrigida a exibição de datas nos relatórios.
```

## Pós-publicação em produção

Depois do merge, da criação da tag e do deploy bem-sucedido em `main`, sincronize obrigatoriamente `main` para `develop`.

```text
main → develop
```

Durante essa sincronização:

- preserve tarefas ainda em homologação em `develop`;
- incorpore todas as alterações oficiais de `main`, incluindo `CHANGELOG.md` e `package.json`;
- deixe a versão final de `package.json` igual à versão oficial de `main`;
- resolva automaticamente apenas um conflito exclusivo em `package.json`, usando a versão de `main`;
- interrompa a sincronização para revisão manual se houver conflito em qualquer outro arquivo;
- execute testes, lint e build após resolver conflitos, quando aplicável.

Após a sincronização, `develop` volta a usar a versão oficial. A próxima release de HML recomeça em `X.Y.Z.beta-1`.

Uma `task/<slug>` só pode ser excluída depois de publicada em produção, incorporada em `main` e sincronizada para `develop`. Quando houve homologação, ela também deve estar concluída.

## Pull Requests

Todo Pull Request de release deve informar:

- identificação ou nome da tarefa;
- versão da release;
- resumo da implementação;
- arquivos ou áreas afetadas;
- como testar;
- riscos conhecidos;
- checklist dos testes executados.

### Título

Use a versão da release no início do título, seguida de um resumo objetivo:

```text
vX.Y.Z.beta-N — Preparar release: resumo da entrega
vX.Y.Z — Preparar release: resumo da entrega
```

Exemplos:

```text
v0.1.19.beta-1 — Preparar release: workflow Git para homologação
v0.1.20 — Preparar release: novo checkout
```

| Tipo | Origem | Destino | Identificação |
| --- | --- | --- | --- |
| Homologação | `release/X.Y.Z.beta-N` | `develop` | `Destino: Homologação` |
| Produção | `release/X.Y.Z` | `main` | `Destino: Produção` |

O PR de produção deve conter somente a tarefa selecionada e os arquivos necessários para a sua release.

## Comandos em linguagem natural

| Solicitação | Ação esperada |
| --- | --- |
| “Crie a tarefa de ...” | Informar que a `task/<slug>` deve ser criada manualmente pelo usuário a partir de `main`; após isso, atuar na branch já criada. |
| “Libere para homologação”, “Publique em HML” ou “Pode mandar para develop” | Criar a próxima `release/X.Y.Z.beta-N` a partir de `develop`, incorporar a tarefa, atualizar `package.json`, validar e abrir PR para `develop`. |
| “Libere para produção” ou “Publique em PRD” | Criar `release/X.Y.Z` a partir de `main`, incorporar a tarefa, definir a versão oficial, atualizar `package.json` e `CHANGELOG.md`, validar e abrir PR para `main`. |
| “O PR foi mergeado em produção” | Confirmar o merge, criar a tag, disparar o deploy de PRD e sincronizar `main → develop`. |

## Restrições

Nunca:

- trabalhe diretamente em `main` ou `develop`;
- crie, renomeie ou exclua uma branch `task/<slug>`;
- crie uma tarefa a partir de `develop`;
- abra um PR de tarefa diretamente para `develop` ou `main`;
- faça merge de `develop` para `main`;
- use `develop` como base de uma release de produção;
- inclua mais de uma tarefa em uma release de produção;
- deixe duas releases de homologação concorrentes calcularem a mesma versão beta;
- incremente `PATCH`, `MINOR` ou `MAJOR` durante uma release de homologação;
- registre versões `.beta-N` no changelog público;
- substitua alterações ainda em homologação durante a sincronização `main → develop`.

## Resumo do fluxo

```text
                     ┌───────────────┐
                     │     main      │
                     │   produção    │
                     └──────┬────────┘
                            │
                     cria a tarefa
                            │
                            ▼
                    ┌────────────────┐
                    │  task/<slug>   │
                    └───────┬────────┘
                            │
             ┌──────────────┴──────────────┐
             ▼                             ▼
┌────────────────────────┐    ┌────────────────────────┐
│ release/X.Y.Z.beta-N   │    │     release/X.Y.Z      │
│ PR → develop → HML     │    │      PR → main → PRD   │
└────────────────────────┘    └───────────┬────────────┘
                                             │
                                      main → develop
```
