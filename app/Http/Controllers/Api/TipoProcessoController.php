<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TipoProcesso\StoreTipoProcessoRequest;
use App\Http\Requests\TipoProcesso\UpdateTipoProcessoRequest;
use App\Services\TipoProcessoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class TipoProcessoController
 * @package App\Http\Controllers\Api
 */
class TipoProcessoController extends Controller
{
    /**
     * @var TipoProcessoService
     */
    protected $service;

    /**
     * TipoProcessoController constructor.
     * @param TipoProcessoService $service
     */
    public function __construct(TipoProcessoService $service)
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
        $tipos = $this->service->listAll($request->all());
        return response()->json($tipos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreTipoProcessoRequest $request
     * @return JsonResponse
     */
    public function store(StoreTipoProcessoRequest $request): JsonResponse
    {
        $tipo = $this->service->create($request->validated());
        return response()->json($tipo, 201);
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
            $tipo = $this->service->findById($id);
            return response()->json($tipo);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTipoProcessoRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateTipoProcessoRequest $request, int $id): JsonResponse
    {
        $tipo = $this->service->update($id, $request->validated());
        return response()->json($tipo);
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
