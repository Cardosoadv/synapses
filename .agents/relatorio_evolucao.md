# Relat├│rio de Evolu├º├úo - Synapses GED

## [v1.0.0] - 2026-05-09
### Adicionado
- **M├│dulo de Gest├úo de Usu├írios**: Implementa├º├úo completa do CRUD de usu├írios seguindo o padr├úo MVCRS.
- **Autentica├º├úo Dupla**: 
  - **API (JWT)**: Implementa├º├úo de login stateless via `tymon/jwt-auth` para integra├º├Áes e apps.
  - **Web (Session)**: Login tradicional via sess├Áes para a interface administrativa.
- **Design System Premium**: Interface Dark Mode com CSS customizado, glassmorphism e componentes interativos (sidebar, datatables).
- **Camada MVCRS**:
  - **Models**: `User` expandido com campos GED (`cpf`, `role`, `status`, `avatar`) e suporte JWT.
  - **Validation**: Form Requests dedicados para cada a├º├úo de usu├írio e auth.
  - **Controllers**: Separados por contexto (Web e API).
  - **Repositories**: Padroniza├º├úo do acesso a dados via `UserRepositoryInterface`.
  - **Services**: Centraliza├º├úo da l├│gica de neg├│cio em `UserService` e `AuthService`.
- **Seguran├ºa**: Middleware JWT para prote├º├úo de rotas API e controle de status de conta (ativa/inativa).

### Alterado
- **Migration**: Tabela `users` expandida com novos campos de perfil e auditoria.
- **Bootstrap**: Configura├º├úo de rotas API e inje├º├úo de depend├¬ncia via `RepositoryServiceProvider`.

---

## [v1.1.0] - 2026-05-09
### Adicionado
- **M├│dulo de Processos (Inspirado no SEI)**:
    - Registro de processos com numera├º├úo autom├ítica sequencial (`NNNNN.NNNNNN/YYYY-DD`).
    - Gest├úo de Tipos de Processos (categorias, prefixos e prazos).
    - Controle de n├¡veis de acesso (P├║blico, Restrito, Sigiloso).
    - Timeline b├ísica de eventos do processo.
- **Camada MVCRS Expandida**:
    - Novos Reposit├│rios e Contratos para `Processo` e `TipoProcesso`.
    - Servi├ºos com l├│gica de neg├│cio centralizada e gera├º├úo de numera├º├úo.
    - Controllers Web para gest├úo administrativa.
- **Interface**:
    - Telas de listagem, cria├º├úo e edi├º├úo com design premium Dark Mode.
    - Integra├º├úo no sidebar principal.

---

## [v1.2.0] - 2026-05-09
### Adicionado
- **M├│dulo de Documentos (Fase 1 - PDF)**:
    - Implementa├º├úo do CRUD de documentos vinculados a processos.
    - Suporte a upload de arquivos PDF com armazenamento local seguro (`storage/app/documentos`).
    - Visualiza├º├úo de PDFs diretamente no navegador e op├º├úo de download.
    - Gera├º├úo de numera├º├úo de documentos (`DOC-NNNNNNNN`).
    - Identifica├º├úo opaca e segura usando **UUIDs** gerados automaticamente no banco de dados.
    - Controle de n├¡vel de acesso por documento (P├║blico, Restrito, Sigiloso).
- **Camada MVCRS Expandida**:
    - `DocumentoRepository` e `DocumentoService` para gest├úo de arquivos e metadados com suporte a `findByUuid`.
    - Controller Web para integra├º├úo com a interface de processos.
- **Interface**:
    - Tela de upload premium com suporte a drag-and-drop (visual).
    - Listagem de documentos integrada ├á tela de detalhes do processo.
    - **Visualizador de Processo (Folheador)**: Nova tela com barra lateral que permite "folhear" todos os documentos de um processo de forma fluida.


---

## [v1.3.0] - 2026-05-10
### Adicionado
- **Padroniza├º├úo e Robustez**:
    - Implementa├º├úo da constante `DEFAULT_PER_PAGE` na `BaseRepositoryInterface` para uniformizar a pagina├º├úo.
    - Padroniza├º├úo do m├®todo `findById` nos servi├ºos `ProcessoService` e `TipoProcessoService` para lan├ºar `ModelNotFoundException`, garantindo consist├¬ncia com o `UserService`.
    - Implementa├º├úo de `getSystemUserId()` no `ProcessoService` como fallback para o `admin@synapses.com` em contextos sem autentica├º├úo (CLI/Seeding).
- **Corre├º├Áes T├®cnicas**:
    - Corre├º├úo do teste `ExampleTest.php` para validar o redirecionamento (302) da rota raiz.

---
*Assinado por: Antigravity AI*