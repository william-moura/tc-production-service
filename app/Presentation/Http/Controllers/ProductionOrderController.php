<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers;

use App\Application\DTOs\ProductionOrderOutputDto;
use App\Application\UseCases\AbortProductionUseCase;
use App\Application\UseCases\CompleteRepairUseCase;
use App\Application\UseCases\StartRepairUseCase;
use App\Domain\Entities\ProductionOrder;
use App\Domain\Exceptions\ProductionOrderNotFoundException;
use App\Domain\Repositories\ProductionOrderRepositoryInterface;
use App\Presentation\Http\Requests\AbortProductionRequest;
use App\Presentation\Http\Requests\StartRepairRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

final class ProductionOrderController extends Controller
{
    public function __construct(
        private readonly ProductionOrderRepositoryInterface $repository
    ) {}

    /**
     * Lista todas as ordens de produção ou filtra por status.
     *
     * GET /api/v1/production-orders
     */
    public function index(Request $request): JsonResponse
    {
        $status = $request->query('status');

        $orders = is_string($status) && $status !== ''
            ? $this->repository->findByStatus($status)
            : $this->repository->findAll();

        $data = array_map(
            fn(ProductionOrder $order) => ProductionOrderOutputDto::fromDomain($order)->toArray(),
            $orders
        );

        return response()->json(['data' => $data], Response::HTTP_OK);
    }

    /**
     * Consulta ordem de produção pelo ID único (MongoDB _id).
     *
     * GET /api/v1/production-orders/{id}
     */
    public function show(string $id): JsonResponse
    {
        $order = $this->repository->findById($id);

        if ($order === null) {
            throw ProductionOrderNotFoundException::forId($id);
        }

        return response()->json([
            'data' => ProductionOrderOutputDto::fromDomain($order)->toArray(),
        ], Response::HTTP_OK);
    }

    /**
     * Consulta ordem de produção pelo número da Work Order.
     *
     * GET /api/v1/production-orders/work-order/{workOrderId}
     */
    public function findByWorkOrder(int $workOrderId): JsonResponse
    {
        $order = $this->repository->findByWorkOrderId($workOrderId);

        if ($order === null) {
            throw ProductionOrderNotFoundException::forWorkOrder($workOrderId);
        }

        return response()->json([
            'data' => ProductionOrderOutputDto::fromDomain($order)->toArray(),
        ], Response::HTTP_OK);
    }

    /**
     * Inicia o reparo e atribui o mecânico à ordem.
     *
     * POST /api/v1/production-orders/work-order/{workOrderId}/start
     */
    public function start(
        int $workOrderId,
        StartRepairRequest $request,
        StartRepairUseCase $useCase
    ): JsonResponse {
        $dto = $request->toDto($workOrderId);
        $output = $useCase->execute($dto);

        return response()->json([
            'message' => 'Repair started successfully.',
            'data' => $output->toArray(),
        ], Response::HTTP_OK);
    }

    /**
     * Conclui o reparo e publica 'repair.completed' na Saga.
     *
     * POST /api/v1/production-orders/work-order/{workOrderId}/complete
     */
    public function complete(
        int $workOrderId,
        CompleteRepairUseCase $useCase
    ): JsonResponse {
        $output = $useCase->execute($workOrderId);

        return response()->json([
            'message' => 'Repair completed successfully.',
            'data' => $output->toArray(),
        ], Response::HTTP_OK);
    }

    /**
     * Aborta a produção e publica 'production.aborted' para compensação financeira.
     *
     * POST /api/v1/production-orders/work-order/{workOrderId}/abort
     */
    public function abort(
        int $workOrderId,
        AbortProductionRequest $request,
        AbortProductionUseCase $useCase
    ): JsonResponse {
        $dto = $request->toDto($workOrderId);
        $output = $useCase->execute($dto->workOrderId, $dto->reason);

        return response()->json([
            'message' => 'Production aborted successfully.',
            'data' => $output->toArray(),
        ], Response::HTTP_OK);
    }
}