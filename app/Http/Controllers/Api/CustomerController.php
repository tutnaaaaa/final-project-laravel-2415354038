<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function index(): JsonResponse
    {
        $customers = Customer::latest()->get();
        return response()->json([
            "success" => true,
            "message" => "Customers retrieved successfully",
            "data" => $customers
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            "name" => ["required", "string", "max:255"],
            "email" => ["nullable", "email", "unique:customers,email"],
            "phone" => ["nullable", "string"],
            "address" => ["nullable", "string"],
            "status" => ["nullable", "boolean"],
        ]);

        // Generate ID Pelanggan unik secara otomatis (misal: CUST-XYZ123)
        $data['customer_id'] = 'CUST-' . strtoupper(Str::random(6));
        $data['status'] = $data['status'] ?? true;

        $customer = Customer::query()->create($data);

        return response()->json([
            "success" => true,
            "message" => "Customer created successfully",
            "data" => $customer
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $customer = Customer::with('subscriptions')->find($id);

        if (!$customer) {
            return response()->json([
                "success" => false,
                "message" => "Customer not found",
            ], 404);
        }

        return response()->json([
            "success" => true,
            "message" => "Customer retrieved successfully",
            "data" => $customer
        ]);
    }
}