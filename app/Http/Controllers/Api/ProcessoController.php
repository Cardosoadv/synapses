<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Processo\StoreProcessoRequest;
use App\Http\Requests\Processo\UpdateProcessoStatusRequest;
use App\Services\ProcessoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class ProcessoController
 * @package App\Http\Controllers\Api
 */
class ProcessoController extends Controller
{
    /**
     * @var ProcessoService
     */
    protected $service;

    /**
     * ProcessoController constructor.
     * @param ProcessoService $service
     */
    public function __construct(ProcessoService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $processos = $this->service->listAll($request->all());
        return response()->json($processos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProcessoRequest $request
     * @return JsonResponse
     */
    public function store(StoreProcessoRequest $request): JsonResponse
    {
        $processo = $this->service->create($request->validated());
        return response()->json($processo, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $processo = $this->service->findById($id);
            return response()->json($processo);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Update the status of the specified resource.
     *
     * @param UpdateProcessoStatusRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateProcessoStatusRequest $request, int $id): JsonResponse
    {
        $processo = $this->service->update($id, $request->validated());
        return response()->json($processo);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);
        return response()->json(null, 204);
    }
}
