<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title><?= (isset($titulo) ? esc($titulo) : "Relatório") ?></title>
    <style>
        /* --- CONFIGURAÇÃO DE PÁGINA (DOMPDF) --- */
        @page {
            margin: 1cm 1.5cm 1.5cm 1.5cm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px; /* Reduzi levemente para caber mais dados */
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding-top: 3.5cm; /* Espaço para o cabeçalho fixo se necessário */
            padding-bottom: 1cm;
        }

        /* --- CABEÇALHO FIXO --- */
        /* Para o cabeçalho aparecer em todas as páginas, usamos position: fixed */
        .header-fixed {
            position: fixed;
            top: -0.5cm;
            left: 0;
            right: 0;
            height: 3cm;
            border-bottom: 2px solid #00435A;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: middle;
            padding: 5px;
        }

        .header-logo img {
            width: 100px;
            height: auto;
        }

        .header-title h1 {
            font-size: 16px;
            font-weight: bold;
            color: #00435A;
            margin: 0;
        }

        .header-title h2 {
            font-size: 12px;
            color: #00435A;
            font-weight: normal;
            margin: 0;
        }

        .header-info {
            font-size: 9px;
            color: #00435A;
            text-align: right;
        }

        /* --- CONTEÚDO --- */
        .section {
            margin-bottom: 15px;
            /* Removido o page-break-inside: avoid daqui para evitar saltos de página desnecessários */
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            background-color: #f8f9fa;
            padding: 5px 10px;
            border-left: 4px solid #00435A;
            margin-bottom: 10px;
            color: #00435A;
        }

        /* --- TABELA DE DADOS --- */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* Ajuda a manter as larguras das colunas */
        }

        /* Importante: Faz o thead repetir em cada página */
        .data-table thead {
            display: table-header-group;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #eee;
            padding: 6px 4px;
            word-wrap: break-word; /* Evita que texto longo quebre o layout */
        }

        .data-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        /* Evita que uma linha da tabela seja cortada ao meio */
        .data-table tr {
            page-break-inside: avoid;
        }

        .categoria-pai {
            background-color: #fcfcfc;
            font-weight: bold;
        }

        .total-row {
            background-color: #f0f0f0;
        }

        /* --- RODAPÉ FIXO --- */
        .footer {
            position: fixed;
            bottom: -0.5cm;
            left: 0;
            right: 0;
            height: 1cm;
            font-size: 9px;
            color: #777;
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }

        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .text-green { color: #198754; }
        .text-red { color: #dc3545; }
        .text-blue { color: #0d6efd; }
    </style>
</head>

<body>
    <!-- Rodapé -->
    <div class="footer">
        <p>Relatório gerado em <?= date('d/m/Y H:i') ?> | © <?= date('Y') ?> Ordo - Escritórios de Advocacia</p>
        <p>© <?= date('Y') ?> - Ordo - Sistema de Administração de Escritórios de Advocacia</p>

    </div>

    <!-- Cabeçalho Fixo -->
    <div class="header-fixed">
        <table class="header-table">
            <tr>
                <td style="width: 20%;">
                    <div class="header-logo">
                        <img src="<?= base_url('public/dist/assets/img/LogoCeB.png') ?>" alt="Logo">
                    </div>
                </td>
                <td style="width: 50%; text-align: center;">
                    <div class="header-title">
                        <h1><?= (isset($titulo) ? esc($titulo) : "Relatório") ?></h1>
                        <h2><?= (isset($subtitulo) ? esc($subtitulo) : "") ?></h2>
                    </div>
                </td>
                <td style="width: 30%;">
                    <div class="header-info">
                        <strong>Cardoso & Bruno Sociedade de Advogados</strong><br>
                        CNPJ: 32.054.878/0001-04
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Conteúdo Dinâmico -->
    <div class="main-content">
        <?= $this->renderSection('conteudo') ?>
    </div>
</body>

</html>