<p align="center">
<a href="#"><img src="https://img.shields.io/badge/version-v1.3.0-blue.svg" alt="Version"></a>
<a href="#"><img src="https://img.shields.io/badge/status-active-brightgreen.svg" alt="Status"></a>
<a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/license-MIT-green.svg" alt="License"></a>
</p>

## Synapses GED v1.3.0


---

# Synapses GED — Sistema de Gestão para Conselhos Profissionais

**Synapses GED** é um software de código aberto desenvolvido para atender às necessidades específicas dos conselhos profissionais brasileiros, cobrindo tanto as **atividades finalísticas** (registro, fiscalização, processos ético-disciplinares) quanto as **atividades meio** (gestão administrativa, financeira e de pessoas).

O projeto nasce da necessidade de oferecer às entidades de fiscalização do exercício profissional uma solução moderna, transparente e acessível, alinhada aos princípios da administração pública e às exigências dos órgãos de controle.

---

## 🎯 Escopo do Sistema

### Atividades Finalísticas

Módulos voltados ao propósito institucional dos conselhos:

- **Registro Profissional** — Cadastro, habilitação e emissão de carteiras e certidões para pessoas físicas e jurídicas.
- **Fiscalização** — Planejamento e controle de ações fiscais, visitas e autuações.
- **Processos Ético-Disciplinares** — Gestão de denúncias, sindicâncias e processos administrativos disciplinares.
- **Câmaras e Plenário** — Suporte à tramitação de pautas, deliberações e atas de reuniões.
- **Atendimento ao Profissional** — Portal de serviços, requerimentos online e acompanhamento de solicitações.

### Atividades Meio

Módulos de suporte à gestão interna:

- **Financeiro e Orçamentário** — Controle de arrecadação, anuidades, despesas e prestação de contas.
- **Gestão Documental (GED)** — Organização, armazenamento e rastreabilidade de documentos institucionais.
- **Gestão de Pessoas** — Controle de servidores, cargos, folha de ponto e férias.
- **Compras e Contratos** — Processos licitatórios, controle de contratos e fornecedores.
- **Protocolo e Expediente** — Recebimento, distribuição e tramitação de documentos e processos internos.

---

## 🚧 Estado do Projeto

O Synapses GED está em **fase inicial de desenvolvimento (alpha)**. As funcionalidades listadas representam o escopo pretendido, e não necessariamente o que já está implementado. As contribuições são muito bem-vindas.

Roadmap de curto prazo:
- [ ] Módulo de Registro Profissional — MVP
- [ ] Autenticação e controle de acesso por perfis
- [ ] Portal do profissional (autoatendimento)
- [ ] Módulo financeiro básico (arrecadação de anuidades)
- [ ] GED — indexação e versionamento de documentos

---

## 🛠️ Tecnologia

O sistema é construído sobre o **Laravel**, framework PHP com sintaxe expressiva e ecossistema robusto, que oferece:

- Motor de roteamento simples e eficiente.
- Injeção de dependência e arquitetura orientada a serviços.
- ORM Eloquent para modelagem e consulta ao banco de dados.
- Migrações para controle de esquema do banco de dados.
- Filas para processamento assíncrono de tarefas.
- Suporte a transmissão de eventos em tempo real.

---

## 🤝 Contribuindo

Este é um projeto de código aberto e contribuições são essenciais para o seu crescimento. Se você é desenvolvedor, designer, especialista em conselhos profissionais ou gestor público, sua participação faz diferença.

- Abra uma *issue* para relatar problemas ou sugerir funcionalidades.
- Envie um *pull request* com melhorias ou correções.
- Compartilhe o projeto com outros conselhos e profissionais da área.

Por favor, leia o guia de contribuição antes de submeter alterações (em elaboração).

---

## 🔒 Vulnerabilidades de Segurança

Se você descobrir uma vulnerabilidade de segurança, por favor **não abra uma issue pública**. Entre em contato diretamente com a equipe mantenedora por e-mail para que o problema seja tratado de forma responsável.

---

## 📄 Licença

Este software é distribuído sob a licença [MIT](https://opensource.org/licenses/MIT), garantindo liberdade de uso, modificação e distribuição, inclusive para fins institucionais e governamentais.
