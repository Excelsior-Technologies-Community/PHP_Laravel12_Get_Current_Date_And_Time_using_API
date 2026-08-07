<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PublicHoliday;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DateTimeController extends Controller
{
    private function getValidTimezone(string $timezone): string
    {
        $valid = timezone_identifiers_list();
        return in_array($timezone, $valid) ? $timezone : 'Asia/Kolkata';
    }

    private function formatDateTime(Carbon $dt, ?string $format): array
    {
        $formats = [
            'Y-m-d H:i:s' => $dt->format('Y-m-d H:i:s'),
            'Y-m-d' => $dt->toDateString(),
            'H:i:s' => $dt->toTimeString(),
            'timestamp' => $dt->getTimestamp(),
            'iso8601' => $dt->toIso8601String(),
            'rfc2822' => $dt->toRfc2822String(),
            'd/m/Y H:i:s' => $dt->format('d/m/Y H:i:s'),
            'm-d-Y' => $dt->format('m-d-Y'),
        ];

        if ($format && isset($formats[$format])) {
            return ['formatted' => $formats[$format], 'format' => $format];
        }

        return [
            'date' => $dt->toDateString(),
            'time' => $dt->toTimeString(),
            'date_time' => $dt->toDateTimeString(),
            'timestamp' => $dt->getTimestamp(),
            'iso8601' => $dt->toIso8601String(),
            'timezone' => $dt->getTimezone()->getName(),
        ];
    }

    public function getCurrentDateTime(Request $request)
    {
        $timezone = $this->getValidTimezone($request->query('timezone', 'Asia/Kolkata'));
        $format = $request->query('format');

        $cacheKey = "current_datetime:{$timezone}:" . ($format ?: 'all');

        $data = Cache::remember($cacheKey, 1, function () use ($timezone, $format) {
            $now = Carbon::now($timezone);
            $response = [
                'status' => true,
                'message' => 'Current Date and Time',
                'timezone' => $timezone,
            ];
            $response = array_merge($response, $this->formatDateTime($now, $format));
            return $response;
        });

        return response()->json($data);
    }

    public function convert(Request $request)
    {
        $request->validate([
            'datetime' => 'required|string',
            'from_timezone' => 'nullable|string',
            'to_timezone' => 'nullable|string',
            'format' => 'nullable|string',
        ]);

        $fromTz = $this->getValidTimezone($request->input('from_timezone', 'UTC'));
        $toTz = $this->getValidTimezone($request->input('to_timezone', 'Asia/Kolkata'));

        try {
            $dt = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('datetime'), $fromTz);
        } catch (\Exception $e) {
            try {
                $dt = Carbon::parse($request->input('datetime'), $fromTz);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid datetime format. Use Y-m-d H:i:s',
                ], 422);
            }
        }

        $converted = $dt->setTimezone($toTz);

        return response()->json([
            'status' => true,
            'message' => 'DateTime converted successfully',
            'original_datetime' => $dt->toDateTimeString(),
            'from_timezone' => $fromTz,
            'converted_datetime' => $converted->toDateTimeString(),
            'to_timezone' => $toTz,
            'formatted' => $this->formatDateTime($converted, $request->input('format')),
        ]);
    }

    public function compareTimezones(Request $request)
    {
        $majorTimezones = [
            'Asia/Kolkata (IST)',
            'America/New_York (EST)',
            'America/Los_Angeles (PST)',
            'Europe/London (GMT)',
            'Europe/Paris (CET)',
            'Asia/Tokyo (JST)',
            'Australia/Sydney (AEST)',
            'Asia/Dubai (GST)',
            'Asia/Singapore (SGT)',
        ];

        $data = Cache::remember('timezone_comparison', 1, function () use ($majorTimezones) {
            $result = [];
            foreach ($majorTimezones as $tzLabel) {
                [$tz] = explode(' ', $tzLabel, 2);
                $tz = trim($tz, '()');
                $now = Carbon::now($tz);
                $result[] = [
                    'label' => $tzLabel,
                    'timezone' => $tz,
                    'date' => $now->toDateString(),
                    'time' => $now->toTimeString(),
                    'date_time' => $now->toDateTimeString(),
                    'timestamp' => $now->getTimestamp(),
                    'is_daylight' => (bool) $now->format('I'),
                ];
            }
            return $result;
        });

        return response()->json([
            'status' => true,
            'message' => 'Current time across major timezones',
            'comparison' => $data,
        ]);
    }

    public function checkBusinessHours(Request $request)
    {
        $timezone = $this->getValidTimezone($request->query('tz', 'Asia/Kolkata'));
        $startTime = $request->query('start', '09:00');
        $endTime = $request->query('end', '18:00');
        $days = $request->query('days', '1,2,3,4,5');

        $now = Carbon::now($timezone);
        $currentDay = $now->dayOfWeek;
        $currentTime = $now->format('H:i');

        $workingDays = array_map('intval', explode(',', $days));
        $isWorkingDay = in_array($currentDay, $workingDays);
        $isWithinHours = $currentTime >= $startTime && $currentTime <= $endTime;
        $isBusinessHour = $isWorkingDay && $isWithinHours;

        $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        return response()->json([
            'status' => true,
            'message' => $isBusinessHour ? 'Currently within business hours' : 'Currently outside business hours',
            'is_business_hour' => $isBusinessHour,
            'current_time' => $currentTime,
            'current_day' => $dayNames[$currentDay] ?? 'Unknown',
            'timezone' => $timezone,
            'business_hours' => [
                'start' => $startTime,
                'end' => $endTime,
                'working_days' => array_map(fn($d) => $dayNames[$d] ?? $d, $workingDays),
            ],
            'checks' => [
                'is_working_day' => $isWorkingDay,
                'is_within_hours' => $isWithinHours,
            ],
        ]);
    }

    public function checkHoliday(Request $request)
    {
        $timezone = $this->getValidTimezone($request->query('tz', 'Asia/Kolkata'));
        $today = Carbon::now($timezone)->toDateString();

        $holiday = Cache::remember("holiday:{$today}", 86400, function () use ($today) {
            return PublicHoliday::where('date', $today)
                ->where('is_active', true)
                ->first();
        });

        $isHoliday = (bool) $holiday;

        return response()->json([
            'status' => true,
            'message' => $isHoliday ? "Today is a holiday: {$holiday->name}" : 'Today is not a public holiday',
            'is_holiday' => $isHoliday,
            'date' => $today,
            'timezone' => $timezone,
            'holiday' => $holiday ? [
                'name' => $holiday->name,
                'date' => $holiday->date->toDateString(),
            ] : null,
        ]);
    }

    public function healthCheck(Request $request)
    {
        return response()->json([
            'status' => true,
            'message' => 'Application is healthy',
            'app_name' => config('app.name'),
            'laravel_version' => app()->version(),
            'php_version' => phpversion(),
            'timezone' => config('app.timezone'),
            'current_server_time' => Carbon::now('Asia/Kolkata')->toDateTimeString(),
            'environment' => app()->environment(),
        ]);
    }
}
