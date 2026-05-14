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

## [v1.3.0] - 2026-05-14
### Otimizado
- **N+1 Query Resolution**: Implementação de eager loading para movimentações e usuários na visualização de detalhes do processo, reduzindo significativamente o número de consultas ao banco de dados.
- **SARGable Queries**: Refatoração da busca pelo último número de processo para utilizar comparações de intervalo de datas, permitindo o uso eficiente de índices.
- **Performance Indexing**: Adição de índices estratégicos nas tabelas `processos`, `tipos_processos` e `users` para acelerar filtragens e ordenações frequentes.

---
*Assinado por: Bolt Agent*
