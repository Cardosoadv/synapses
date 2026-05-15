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
- **Centralização de Recursos**: Implementação de `public/dist/css/style.css` e módulos JS em `public/dist/js/app/`.
- **Acessibilidade (A11y)**:
    - Suporte a navegação por teclado com `:focus-visible`.
    - Labels de acessibilidade (`aria-label`) em todos os elementos de ação.
    - Suporte a eventos de teclado para elementos não-semânticos.
- **Refatoração de UI**: Substituição de estilos inline por classes semânticas centralizadas, melhorando a performance de cache e manutenção.

---

## [v1.3.0] - 2026-05-09
### Adicionado
- **Micro-UX e Acessibilidade Avançada**:
    - **Login**: Centralização de estilos da página de login no CSS global.
    - **Feedback Visual**: Implementação da classe `.form-label-required` para indicação visual clara de campos obrigatórios via CSS.
    - **Acessibilidade Web**: Adição de `role="alert"` em mensagens de feedback e `aria-current="page"` para navegação ativa no sidebar.
    - **Tabelas Semânticas**: Implementação de `scope="col"` em todos os cabeçalhos de tabela para melhor suporte a leitores de tela.

---
*Assinado por: Antigravity AI*
