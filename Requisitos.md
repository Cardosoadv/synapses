# Documento de Requisitos: Projeto Synapses

**Versão**: 0.0.2

**Descrição**: Sistema Open Source para Gestão de Conselhos de Fiscalização Profissional

## 1. Introdução

O Synapses é um sistema de gestão ERP (Enterprise Resource Planning) voltado exclusivamente para Conselhos de Fiscalização Profissional (Autarquias). O objetivo é unificar processos administrativos, financeiros, jurídicos e de fiscalização em uma plataforma moderna, segura e de código aberto.

## 2. Justificativa Técnica

### 2.1 Por que PHP e CodeIgniter 4?

- **Performance e Leveza**: O CodeIgniter possui um footprint mínimo, garantindo alta performance mesmo em infraestruturas modestas.
- **Segurança**: Proteção nativa contra vulnerabilidades web e integração com o Shield para autenticação.
- **Ecossistema Brasileiro**: O PHP é amplamente difundido em TI pública no Brasil, facilitando a sustentação do software.

### 2.2 Arquitetura MVCRS (Model-View-Controller-Repository-Service)

A aplicação adota a separação rigorosa de responsabilidades:

- **Model**: Abstração das tabelas não deve ter lógica.
- **View**: Interface de usuário AdminLte usando template.
- **Controller**: Gerenciamento do fluxo de entrada e respostas.
- **Repository**: Abstração de queries complexas e persistência de dados.
- **Service**: Coração do sistema. Toda a lógica de negócios, cálculos de multas, fluxos de contratos e integrações com o SEI! devem residir aqui.

## 3. Autenticação e Segurança (CI Shield)

- **Controle Granular**: Uso de Permissions e Groups do Shield para garantir que um servidor do Almoxarifado não acesse dados do Jurídico, por exemplo.
- **Auditoria de Acesso**: Registro detalhado de logins e tentativas falhas.

## 4. Requisitos Funcionais (RF)

### RF001 - Gestão de Cadastro e Integração SEI!

- Inscrição e gestão de Pessoa Física e Jurídica.
- **Dados Biométricos**: Coleta e armazenamento de Foto, Impressão Digital e Assinatura para identificação segura dos profissionais.
- **Integração SEI!**: Possibilidade de vincular registros de profissionais a processos eletrônicos no SEI! via Webservice (SOAP).
- Sincronização de número de protocolo e status de tramitação de processos administrativos.

### RF002 - Módulo Financeiro

- Geração de anuidades, taxas e multas.
- Baixa automática de arquivos retorno bancário e integração PIX.
- Gestão de parcelamentos e Refis.

### RF003 - Módulo de Fiscalização

- Planejamento de rotas e visitas.
- Emissão de Autos de Infração digitais.

### RF004 - Módulo Jurídico

- **Dívida Ativa**: Gestão de certidões de dívida ativa (CDA).
- **Contencioso**: Acompanhamento de processos judiciais onde o conselho é parte.
- Integração com o módulo financeiro para atualização de débitos em cobrança judicial.

### RF005 - Módulo de Contratos e Convênios

- Gestão do ciclo de vida de contratos (Vigência, Aditivos, Reajustes).
- Alertas de vencimento para gestores de contrato.
- Gestão de fornecedores e documentos de habilitação.

### RF006 - Módulo de Almoxarifado e Patrimônio

- Controle de estoque de materiais de consumo.
- Gestão de ativos imobilizados (Patrimônio) com controle de etiquetas e localização.
- Fluxo de requisição de materiais via sistema com aprovação hierárquica.

### RF007 - Portal do Profissional

- Autoatendimento para emissão de certidões, boletos e atualização cadastral.

## 5. Requisitos Não Funcionais (RNF)

- **RNF001**: Compatibilidade com PHP 8.1+ e PostgreSQL (recomendado para autarquias) ou MySQL.
- **RNF002**: Interface responsiva para uso em tablets por fiscais.
- **RNF003 (Auditoria)**: Log de trilha de auditoria para todas as operações de escrita, registrando quem, quando, o quê e o IP de origem.

---

**Status do Projeto**: Em Desenvolvimento
**Próximo Passo**: Implementação da captura e gestão de dados biométricos.
