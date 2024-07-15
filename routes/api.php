<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MeterController;

Route::post('/meter', [MeterController::class, 'updateMeterData']);