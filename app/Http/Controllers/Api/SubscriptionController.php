<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(): JsonResponse
    {
        $subscriptions = Subscription::with(['customer', 'service'])->latest()->get();
        
        return response()->json([
            "success" => true,
            "message" => "Subscriptions retrieved successfully",
            "data" => $subscriptions
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            "customer_id" => ["required", "integer"],
            "service_id" => ["required", "integer"],
            "start_date" => ["nullable", "date"],
            "end_date" => ["nullable", "date"],
            "status" => ["required", "string", "in:active,inactive,trial,isolir,dismantle"],
        ]);

        $subscription = Subscription::query()->create($data);

        return response()->json([
            "success" => true,
            "message" => "Subscription created successfully",
            "data" => $subscription
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $subscription = Subscription::with(['customer', 'service'])->find($id);

        if (!$subscription) {
            return response()->json([
                "success" => false,
                "message" => "Subscription not found",
            ], 404);
        }

        return response()->json([
            "success" => true,
            "message" => "Subscription retrieved successfully",
            "data" => $subscription
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $subscription = Subscription::find($id);

        if (!$subscription) {
            return response()->json([
                "success" => false,
                "message" => "Subscription not found",
            ], 404);
        }

        $data = $request->validate([
            "customer_id" => ["sometimes", "integer"],
            "service_id" => ["sometimes", "integer"],
            "start_date" => ["nullable", "date"],
            "end_date" => ["nullable", "date"],
            "status" => ["sometimes", "string", "in:active,inactive,trial,isolir,dismantle"],
        ]);

        $subscription->update($data);

        return response()->json([
            "success" => true,
            "message" => "Subscription updated successfully",
            "data" => $subscription
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $subscription = Subscription::find($id);

        if (!$subscription) {
            return response()->json([
                "success" => false,
                "message" => "Subscription not found",
            ], 404);
        }

        $subscription->delete();

        return response()->json([
            "success" => true,
            "message" => "Subscription deleted successfully",
            "data" => null
        ]);
    }
}