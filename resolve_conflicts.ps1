# Script to resolve merge conflicts for a single branch
# Files with conflicts:
# 1. .agents/relatorio_evolucao.md
# 2. README.md
# 3. app/Providers/RepositoryServiceProvider.php
# 4. composer.json
# 5. resources/views/processos/show.blade.php

# ── relatorio_evolucao.md ──────────────────────────────────────────────────────
$relatorio = @"
# Relatório de Evolução - Synapses GED

## [v1.0.0] - 2026-05-09
### Adicionado
- **Módulo de Gestão de Usuários**: Implementação completa do CRUD de usuários seguindo o padrão MVCRS.
- **Autenticação Dupla**: 
  - **API (JWT)**: Implementação de login stateless via ``tymon/jwt-auth`` para integrações e apps.
  - **Web (Session)**: Login tradicional via sessões para a interface administrativa.
- **Design System Premium**: Interface Dark Mode com CSS customizado, glassmorphism e componentes interativos (sidebar, datatables).
- **Camada MVCRS**:
  - **Models**: ``User`` expandido com campos GED (``cpf``, ``role``, ``status``, ``avatar``) e suporte JWT.
  - **Validation**: Form Requests dedicados para cada ação de usuário e auth.
  - **Controllers**: Separados por contexto (Web e API).
  - **Repositories**: Padronização do acesso a dados via ``UserRepositoryInterface``.
  - **Services**: Centralização da lógica de negócio em ``UserService`` e ``AuthService``.
- **Segurança**: Middleware JWT para proteção de rotas API e controle de status de conta (ativa/inativa).

### Alterado
- **Migration**: Tabela ``users`` expandida com novos campos de perfil e auditoria.
- **Bootstrap**: Configuração de rotas API e injeção de dependência via ``RepositoryServiceProvider``.

---

## [v1.1.0] - 2026-05-09
### Adicionado
- **Módulo de Processos (Inspirado no SEI)**:
    - Registro de processos com numeração automática sequencial (``NNNNN.NNNNNN/YYYY-DD``).
    - Gestão de Tipos de Processos (categorias, prefixos e prazos).
    - Controle de níveis de acesso (Público, Restrito, Sigiloso).
    - Timeline básica de eventos do processo.
- **Camada MVCRS Expandida**:
    - Novos Repositórios e Contratos para ``Processo`` e ``TipoProcesso``.
    - Serviços com lógica de negócio centralizada e geração de numeração.
    - Controllers Web para gestão administrativa.
- **Interface**:
    - Telas de listagem, criação e edição com design premium Dark Mode.
    - Integração no sidebar principal.

---

## [v1.2.0] - 2026-05-09
### Adicionado
- **Módulo de Documentos (Fase 1 - PDF)**:
    - Implementação do CRUD de documentos vinculados a processos.
    - Suporte a upload de arquivos PDF com armazenamento local seguro (``storage/app/documentos``).
    - Visualização de PDFs diretamente no navegador e opção de download.
    - Geração de numeração de documentos (``DOC-NNNNNNNN``).
    - Identificação opaca e segura usando **UUIDs** gerados automaticamente no banco de dados.
    - Controle de nível de acesso por documento (Público, Restrito, Sigiloso).
- **Centralização de Recursos & Acessibilidade**:
    - Implementação de ``public/dist/css/style.css`` e módulos JS em ``public/dist/js/app/``.
    - Suporte a navegação por teclado com ``:focus-visible`` e labels ``aria-label``.
    - Refatoração de UI: Substituição de estilos inline por classes semânticas centralizadas.
- **Camada MVCRS Expandida**:
    - Repositórios e Serviços para ``Documento`` e ``Movimentacao``.
    - Suporte a ``findByUuid`` e lógica de geração de numeração.

---
*Assinado por: Antigravity AI*
"@

Set-Content -Path ".agents/relatorio_evolucao.md" -Value $relatorio -Encoding UTF8

# ── README.md ─────────────────────────────────────────────────────────────────
# Read current file and fix only the conflict markers at top
$readme = Get-Content -Path "README.md" -Raw

$readmeConflict = @'
<<<<<<< HEAD
<a href="#"><img src="https://img.shields.io/badge/version-v1.1.0--alpha-orange.svg" alt="Version"></a>
<a href="#"><img src="https://img.shields.io/badge/status-em%20desenvolvimento-yellow.svg" alt="Status"></a>
<a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/license-MIT-green.svg" alt="License"></a>
</p>

<p align="center">
  <strong>⚠️ Este projeto está em fase inicial de desenvolvimento. APIs, estrutura e funcionalidades estão sujeitas a mudanças significativas. Não recomendado para uso em produção.</strong>
</p>
=======
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/badge/version-v1.2.0-blue.svg" alt="Latest Version"></a>
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
</p>

## Synapses GED v1.2.0
>>>>>>> main
'@

$readmeResolved = @'
<a href="#"><img src="https://img.shields.io/badge/version-v1.3.0-blue.svg" alt="Version"></a>
<a href="#"><img src="https://img.shields.io/badge/status-active-brightgreen.svg" alt="Status"></a>
<a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/license-MIT-green.svg" alt="License"></a>
</p>

## Synapses GED v1.3.0

'@

$readme = $readme.Replace($readmeConflict, $readmeResolved)
Set-Content -Path "README.md" -Value $readme -Encoding UTF8 -NoNewline

# ── composer.json ─────────────────────────────────────────────────────────────
$composer = Get-Content -Path "composer.json" -Raw

$composerConflict1 = @'
<<<<<<< HEAD
    "name": "synapses/erp-ged-conselhos",
    "version": "1.1.0-alpha",
=======
    "name": "laravel/laravel",
    "version": "1.2.0",
>>>>>>> main
'@

$composerResolved1 = @'
    "name": "synapses/erp-ged-conselhos",
    "version": "1.3.0",
'@

$composer = $composer.Replace($composerConflict1, $composerResolved1)
Set-Content -Path "composer.json" -Value $composer -Encoding UTF8 -NoNewline

# ── RepositoryServiceProvider.php ─────────────────────────────────────────────
$provider = Get-Content -Path "app/Providers/RepositoryServiceProvider.php" -Raw

$providerConflict = @'
<<<<<<< HEAD
        $this->app->bind(\App\Repositories\Contracts\MovimentacaoRepositoryInterface::class, \App\Repositories\MovimentacaoRepository::class);
=======
        $this->app->bind(\App\Repositories\Contracts\DocumentoRepositoryInterface::class, \App\Repositories\DocumentoRepository::class);
>>>>>>> main
'@

$providerResolved = @'
        $this->app->bind(\App\Repositories\Contracts\MovimentacaoRepositoryInterface::class, \App\Repositories\MovimentacaoRepository::class);
        $this->app->bind(\App\Repositories\Contracts\DocumentoRepositoryInterface::class, \App\Repositories\DocumentoRepository::class);
'@

$provider = $provider.Replace($providerConflict, $providerResolved)
Set-Content -Path "app/Providers/RepositoryServiceProvider.php" -Value $provider -Encoding UTF8 -NoNewline

Write-Host "Conflicts resolved! Run: git add . && git commit -m 'chore: merge main and resolve conflicts'"
