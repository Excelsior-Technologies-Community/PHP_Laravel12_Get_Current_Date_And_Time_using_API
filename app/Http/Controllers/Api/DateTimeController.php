<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

class DateTimeController extends Controller
{
    public function getCurrentDateTime()
    {
        // ✅ India Time (IST)
        $now = Carbon::now('Asia/Kolkata');

        return response()->json([
            'status'     => true,
            'message'    => 'Current Date and Time',
            'country'    => 'India',              // ✅ COUNTRY ADDED
            'date'       => $now->toDateString(),
            'time'       => $now->toTimeString(),
            'date_time'  => $now->toDateTimeString(),
            'timezone'   => 'Asia/Kolkata',
        ]);
    }
}
