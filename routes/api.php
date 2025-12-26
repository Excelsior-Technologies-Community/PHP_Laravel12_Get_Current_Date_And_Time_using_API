<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DateTimeController;

Route::get('/current-date-time', [DateTimeController::class, 'getCurrentDateTime']);
