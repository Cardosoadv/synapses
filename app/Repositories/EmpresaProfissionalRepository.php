<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\EmpresaProfissionalModel;
use App\Models\MotivosVinculosModel;

class EmpresaProfissionalRepository
{
    private EmpresaProfissionalModel $model;
    private MotivosVinculosModel $motivoModel;

    public function __construct()
    {
        $this->model = new EmpresaProfissionalModel();
        $this->motivoModel = new MotivosVinculosModel();
    }

    public function getLinksByEmpresa(int $empresaId): array
    {
        return $this->model->where('empresa_id', $empresaId)->findAll();
    }

    public function getLinksByProfissional(int $profissionalId): array
    {
        return $this->model->where('profissional_id', $profissionalId)->findAll();
    }

    public function getAllMotivos(): array
    {
        return $this->motivoModel->findAll();
    }

    public function link(array $data): int|false
    {
        return $this->model->insert($data);
    }

    public function unlink(int $id): bool
    {
        return $this->model->delete($id);
    }

    public function getLinkDetails(int $id): ?array
    {
        return $this->model->find($id);
    }
}
