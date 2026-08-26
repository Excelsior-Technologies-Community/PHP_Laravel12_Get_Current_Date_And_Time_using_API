<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DateTimeController;

Route::get(
    '/current-date-time',
    [DateTimeController::class, 'getCurrentDateTime']
);

Route::post(
    '/convert',
    [DateTimeController::class, 'convert']
);

Route::get(
    '/compare-timezones',
    [DateTimeController::class, 'compareTimezones']
);

Route::get(
    '/business-hours',
    [DateTimeController::class, 'checkBusinessHours']
);

Route::get(
    '/holiday-check',
    [DateTimeController::class, 'checkHoliday']
);

Route::get(
    '/health',
    [DateTimeController::class, 'healthCheck']
);

/*
|--------------------------------------------------------------------------
| New Date & Time Features
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| DateTime Difference Calculator
|--------------------------------------------------------------------------
*/
Route::post(
    '/datetime-difference',
    [DateTimeController::class, 'datetimeDifference']
);

/*
|--------------------------------------------------------------------------
| Date Information
|--------------------------------------------------------------------------
*/
Route::get(
    '/date-info',
    [DateTimeController::class, 'dateInfo']
);