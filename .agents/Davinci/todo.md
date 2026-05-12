# 🎨 Davinci - Feature Discovery Todo

## 🚀 Novas Funcionalidades (Opportunities)

- [x] **Gestão de Movimentações (Histórico do Processo)**
  - **Por que:** Atualmente o processo só tem data de abertura e fechamento. Para um sistema de GED/Processos, é vital saber por onde o processo passou, quem alterou seu status e quando. Isso provê auditoria e transparência.

- [ ] **Módulo de Anexos/Documentos**
  - **Por que:** Um "Processo" no mundo real é composto por documentos. A capacidade de anexar arquivos (PDFs, imagens) é fundamental para a utilidade do sistema.

- [ ] **Sistema de Alertas e Prazos**
  - **Por que:** O `TipoProcesso` já possui um campo `prazo_conclusao`. Automatizar alertas quando um processo está perto de vencer ou vencido agrega valor operacional imediato.

- [ ] **Comentários e Observações Internas**
  - **Por que:** Facilita a colaboração entre os usuários que estão analisando o processo, permitindo registrar informações que não cabem apenas no campo "descrição".

- [ ] **Dashboard de Produtividade**
  - **Por que:** Gestores precisam ver gargalos. Um dashboard mostrando o tempo médio de conclusão por tipo de processo e volume de processos abertos vs. concluídos.

## 🛠️ Lacunas Técnicas (Functional Gaps)

- [ ] **Exposição da API de Processos e Tipos de Processos**
  - **Por que:** Embora as rotas WEB já existam e funcionem, o sistema carece de endpoints de API (JSON) para integração com mobile ou frontends modernos.
