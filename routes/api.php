<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\CustomerController;

// Endpoint untuk Services
Route::apiResource("services", ServiceController::class);
Route::patch("services/{service}/activate", [ServiceController::class, "activate"]);
Route::patch("services/{service}/deactivate", [ServiceController::class, "deactivate"]);

// Endpoint untuk Subscriptions
Route::apiResource("subscriptions", SubscriptionController::class);

// Endpoint untuk Customers
Route::apiResource("customers", CustomerController::class);