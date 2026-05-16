# 🎨 Davinci - Report

## 🔍 Análise de Domínio (Synapses GED)
O sistema é um Gestor Eletrônico de Documentos/Processos (GED).
- **Entidades Principais:** `User`, `Processo`, `TipoProcesso`, `Documento`.
- **Estado Atual:** A estrutura base de dados e a camada de serviço para Processos, Tipos de Processos e Documentos estão presentes e integradas à interface Web.
- **Descoberta:** O sistema possui uma lógica de geração de número de processo padronizada no `ProcessoService`, indicando uma maturidade inicial na regra de negócio.

## 💡 Insights e Descobertas
1. **Falta de Rastreabilidade:** Não havia uma tabela de logs ou movimentações, o que foi corrigido nesta iteração.
2. **Prazos Inertes:** O campo `prazo_conclusao` em `TipoProcesso` não está sendo utilizado em nenhuma lógica de cálculo ou alerta no `ProcessoService`.
3. **Potencial de Expansão:** O sistema pode evoluir de um simples cadastro para um workflow motorizado, adicionando anexos e comentários.
4. **Documentos como Core:** Documentos são o núcleo de um sistema GED. O suporte a PDF é o primeiro passo.
5. **Oportunidades Futuras:**
   - Editor HTML interno para "Despachos" diretamente no sistema (similar ao SEI).
   - Assinaturas digitais (via certificados ou login-based).
   - Versionamento de documentos (histórico de alterações para docs editáveis).

## 📅 Ações Realizadas
- Varredura inicial do diretório `app/` e `database/migrations/`.
- Identificação de oportunidades de negócio além da manutenção técnica.
- Criação do `todo.md` com o backlog inicial de oportunidades.
- **Implementação da Gestão de Movimentações:** Criada a estrutura completa (Migration, Model, Repository) para rastrear o histórico de processos.
- **Módulo de Documentos (PDF):** Upload, visualização e gestão de PDFs vinculados a processos, com suporte a UUIDs e folheador integrado.

