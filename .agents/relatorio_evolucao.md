# Relatório de Evolução - Synapses GED

## [v1.0.0] - 2026-05-09
### Adicionado
- **Módulo de Gestão de Usuários**: Implementação completa do CRUD de usuários seguindo o padrão MVCRS.
- **Autenticação Dupla**: 
  - **API (JWT)**: Implementação de login stateless via `tymon/jwt-auth` para integrações e apps.
  - **Web (Session)**: Login tradicional via sessões para a interface administrativa.
- **Design System Premium**: Interface Dark Mode com CSS customizado, glassmorphism e componentes interativos (sidebar, datatables).
- **Camada MVCRS**:
  - **Models**: `User` expandido com campos GED (`cpf`, `role`, `status`, `avatar`) e suporte JWT.
  - **Validation**: Form Requests dedicados para cada ação de usuário e auth.
  - **Controllers**: Separados por contexto (Web e API).
  - **Repositories**: Padronização do acesso a dados via `UserRepositoryInterface`.
  - **Services**: Centralização da lógica de negócio em `UserService` e `AuthService`.
- **Segurança**: Middleware JWT para proteção de rotas API e controle de status de conta (ativa/inativa).

### Alterado
- **Migration**: Tabela `users` expandida com novos campos de perfil e auditoria.
- **Bootstrap**: Configuração de rotas API e injeção de dependência via `RepositoryServiceProvider`.

---

## [v1.1.0] - 2026-05-09
### Adicionado
- **Módulo de Processos (Inspirado no SEI)**:
    - Registro de processos com numeração automática sequencial (`NNNNN.NNNNNN/YYYY-DD`).
    - Gestão de Tipos de Processos (categorias, prefixos e prazos).
    - Controle de níveis de acesso (Público, Restrito, Sigiloso).
    - Timeline básica de eventos do processo.
- **Camada MVCRS Expandida**:
    - Novos Repositórios e Contratos para `Processo` e `TipoProcesso`.
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
    - Suporte a upload de arquivos PDF com armazenamento local seguro (`storage/app/documentos`).
    - Visualização de PDFs diretamente no navegador e opção de download.
    - Geração de numeração de documentos (`DOC-NNNNNNNN`).
    - Identificação opaca e segura usando **UUIDs** gerados automaticamente no banco de dados.
    - Controle de nível de acesso por documento (Público, Restrito, Sigiloso).
- **Centralização de Recursos & Acessibilidade**:
    - Implementação de `public/dist/css/style.css` e módulos JS em `public/dist/js/app/`.
    - Suporte a navegação por teclado com `:focus-visible` e labels `aria-label`.
    - Refatoração de UI: Substituição de estilos inline por classes semânticas centralizadas.
- **Camada MVCRS Expandida**:
    - Repositórios e Serviços para `Documento` e `Movimentacao`.
    - Suporte a `findByUuid` e lógica de geração de numeração.

---

## [v1.3.0] - 2026-05-14
### Otimizado
- **N+1 Query Resolution**: Implementação de eager loading para movimentações e usuários na visualização de detalhes do processo.
- **SARGable Queries**: Refatoração da busca pelo último número de processo para utilizar comparações de intervalo de datas.
- **Performance Indexing**: Adição de índices estratégicos nas tabelas `processos`, `tipos_processos` e `users`.

---
*Assinado por: Bolt Agent*
