<?php

namespace App\Repositories\Contracts;

use App\Models\Processo;

/**
 * Interface ProcessoRepositoryInterface
 * @package App\Repositories\Contracts
 */
interface ProcessoRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get the latest process number for a specific year.
     *
     * @param int $year
     * @return string|null
     */
    public function getLatestProcessNumber(int $year): ?string;
}
