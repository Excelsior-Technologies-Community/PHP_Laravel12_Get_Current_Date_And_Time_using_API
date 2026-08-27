<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DateTimeController;

/*
|--------------------------------------------------------------------------
| Existing Date & Time APIs
|--------------------------------------------------------------------------
*/

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
| Existing New Features
|--------------------------------------------------------------------------
*/

Route::post(
    '/datetime-difference',
    [DateTimeController::class, 'datetimeDifference']
);

Route::get(
    '/date-info',
    [DateTimeController::class, 'dateInfo']
);

/*
|--------------------------------------------------------------------------
| NEW FEATURE #3
| Date Calculation
|--------------------------------------------------------------------------
*/

Route::post(
    '/date-calculation',
    [DateTimeController::class, 'dateCalculation']
);

/*
|--------------------------------------------------------------------------
| NEW FEATURE #4
| Age Calculator
|--------------------------------------------------------------------------
*/

Route::get(
    '/age-calculator',
    [DateTimeController::class, 'ageCalculator']
);

/*
|--------------------------------------------------------------------------
| NEW FEATURE #5
| Unix Timestamp Converter
|--------------------------------------------------------------------------
*/

Route::get(
    '/timestamp',
    [DateTimeController::class, 'timestamp']
);

/*
|--------------------------------------------------------------------------
| NEW FEATURE #6
| Business Days Calculator
|--------------------------------------------------------------------------
*/

Route::get(
    '/business-days',
    [DateTimeController::class, 'businessDays']
);

/*
|--------------------------------------------------------------------------
| NEW FEATURE #7
| Weekday Finder
|--------------------------------------------------------------------------
*/

Route::get(
    '/weekday-finder',
    [DateTimeController::class, 'weekdayFinder']
);
