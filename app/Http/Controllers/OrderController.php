<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

use App\Http\Requests\OrderRequest;
use App\Services\OrderServiceInterface;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderServiceInterface $orderService)
    {
        $this->orderService = $orderService;
    }

    public function store(OrderRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        try {
            $this->orderService->validateOrderData($validatedData);
        } catch (ValidationException $validator) {
            return response()->json(
                $validator->errors(),
                JsonResponse::HTTP_BAD_REQUEST
            );
        }
        $response = $this->orderService->processOrder($validatedData);

        return response()->json($response);
    }
}
