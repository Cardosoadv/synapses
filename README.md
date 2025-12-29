# Synapses

O Synapses é um sistema de gestão abrangente construído sobre o CodeIgniter 4, projetado para o gerenciamento de Profissionais, Empresas, Contratos e Processos Administrativos (SEI!).

## Principais Funcionalidades

- **Cadastro de Profissionais**: Gerenciamento de profissionais (Pessoa Física) com registros detalhados.
  - **Dados Biométricos**: Suporte para armazenamento e gestão de:
    - Foto
    - Impressão Digital
    - Assinatura
- **Cadastro de Empresas**: Gerenciamento de pessoas jurídicas (PJ) com detalhes corporativos.
- **Gestão de Contratos**: Acompanhamento e gestão de contratos.
- **Gestão de Processos**: Integração com números de processo SEI!.
- **Módulo de Funcionários**: Gestão interna de colaboradores, integrando dados profissionais (Cargo, Departamento, OAB) com o sistema de autenticação (Shield).

## Requisitos do Sistema

- PHP 8.1 ou superior
- Extensões obrigatórias:
  - intl
  - mbstring
  - json
  - mysqlnd (para banco de dados MySQL)
  - libcurl

## Instalação e Configuração

1. **Clonar o repositório**
2. **Instalar dependências**:
   ```bash
   composer install
   ```
3. **Configuração**:
   - Copie o arquivo `env` para `.env`.
   - Defina `CI_ENVIRONMENT = development`.
   - Configure as credenciais do banco de dados no arquivo `.env`.
4. **Configuração do Banco de Dados**:
   - Execute as migrações:
     ```bash
     php spark migrate
     ```
5. **Executar a aplicação**:
   ```bash
   php spark serve
   ```

## Estrutura de Pastas

- `app/`: Código da aplicação (Controllers, Models, Views).
- `public/`: Raiz web (assets, index.php).
- `tests/`: Testes unitários e de integração.
- `vendor/`: Dependências do Composer.

## Atualizações Recentes

- **Refatoração do Módulo de Acesso**: Unificação da gestão de Usuários e Funcionários.
  - Criação do **Módulo de Funcionários**: Gerencia dados cadastrais e profissionais vinculados a uma conta de acesso (Shield).
  - **Arquitetura Service-Repository**: Implementação robusta com transações para garantir integridade entre dados de login e dados de RH.
- **Módulo de Cadastro**: Adicionado suporte no banco de dados para campos biométricos (Foto, Impressão Digital, Assinatura) na tabela `professionals`.
