<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

class DateTimeController extends Controller
{
    public function getCurrentDateTime()
    {
        //  India Time (IST)
        $now = Carbon::now('Asia/Kolkata');

        return response()->json([
            'status'     => true,
            'message'    => 'Current Date and Time',
            'country'    => 'India',             
            'date'=> $now->format('d-m-Y '),
            'time'       => $now->format('h:i A'),
        ]);
    }
}
