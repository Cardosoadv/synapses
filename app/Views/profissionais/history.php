<?= $this->extend('template/layout') ?>

<?= $this->section('conteudo') ?>
<div class="card shadow-sm border-0 mt-3">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">
            <i class="bi bi-clock-history me-2"></i>
            Histórico: <?= esc($profissional['name']) ?>
        </h5>
    </div>
    <div class="card-body">
        <a href="<?= base_url('profissionais') ?>" class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Data/Hora</th>
                        <th>Ação</th>
                        <th>Usuário</th>
                        <th>IP</th>
                        <th>Detalhes</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Placeholder: In a real scenario, we would iterate over $logs passed from controller -->
                    <!-- Since logic to fetch logs isn't fully wired in Service yet, showing static message or empty table -->
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            A funcionalidade de visualização detalhada de logs será implementada em breve.
                            <br>
                            Os registros estão sendo salvos no banco de dados (tabela <code>auditoria</code>).
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
