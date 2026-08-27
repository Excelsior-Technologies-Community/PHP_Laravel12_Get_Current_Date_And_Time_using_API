<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PublicHoliday;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DateTimeController extends Controller
{
    /**
     * Validate timezone and fallback to India timezone.
     */
    private function getValidTimezone(string $timezone): string
    {
        $valid = timezone_identifiers_list();

        return in_array($timezone, $valid, true)
            ? $timezone
            : 'Asia/Kolkata';
    }

    /**
     * Format a Carbon datetime.
     */
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
            return [
                'formatted' => $formats[$format],
                'format' => $format,
            ];
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

    /**
     * Get current date and time.
     */
    public function getCurrentDateTime(Request $request)
    {
        $timezone = $this->getValidTimezone(
            $request->query('timezone', 'Asia/Kolkata')
        );

        $format = $request->query('format');

        $cacheKey = "current_datetime:{$timezone}:" . ($format ?: 'all');

        $data = Cache::remember($cacheKey, 1, function () use ($timezone, $format) {
            $now = Carbon::now($timezone);

            $response = [
                'status' => true,
                'message' => 'Current Date and Time',
                'timezone' => $timezone,
            ];

            return array_merge(
                $response,
                $this->formatDateTime($now, $format)
            );
        });

        return response()->json($data);
    }

    /**
     * Convert datetime between timezones.
     */
    public function convert(Request $request)
    {
        $request->validate([
            'datetime' => 'required|string',
            'from_timezone' => 'nullable|string',
            'to_timezone' => 'nullable|string',
            'format' => 'nullable|string',
        ]);

        $fromTz = $this->getValidTimezone(
            $request->input('from_timezone', 'UTC')
        );

        $toTz = $this->getValidTimezone(
            $request->input('to_timezone', 'Asia/Kolkata')
        );

        try {
            $dt = Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $request->input('datetime'),
                $fromTz
            );
        } catch (\Exception $e) {
            try {
                $dt = Carbon::parse(
                    $request->input('datetime'),
                    $fromTz
                );
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid datetime format. Use Y-m-d H:i:s',
                ], 422);
            }
        }

        $originalDateTime = $dt->copy();

        $converted = $dt->copy()->setTimezone($toTz);

        return response()->json([
            'status' => true,
            'message' => 'DateTime converted successfully',
            'original_datetime' => $originalDateTime->toDateTimeString(),
            'from_timezone' => $fromTz,
            'converted_datetime' => $converted->toDateTimeString(),
            'to_timezone' => $toTz,
            'formatted' => $this->formatDateTime(
                $converted,
                $request->input('format')
            ),
        ]);
    }

    /**
     * Compare current time across major timezones.
     */
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

        $data = Cache::remember(
            'timezone_comparison',
            1,
            function () use ($majorTimezones) {
                $result = [];

                foreach ($majorTimezones as $tzLabel) {
                    [$tz] = explode(' ', $tzLabel, 2);

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
            }
        );

        return response()->json([
            'status' => true,
            'message' => 'Current time across major timezones',
            'comparison' => $data,
        ]);
    }

    /**
     * Check whether current time is inside business hours.
     */
    public function checkBusinessHours(Request $request)
    {
        $timezone = $this->getValidTimezone(
            $request->query('tz', 'Asia/Kolkata')
        );

        $startTime = $request->query('start', '09:00');
        $endTime = $request->query('end', '18:00');
        $days = $request->query('days', '1,2,3,4,5');

        $now = Carbon::now($timezone);

        $currentDay = $now->dayOfWeek;
        $currentTime = $now->format('H:i');

        $workingDays = array_map(
            'intval',
            explode(',', $days)
        );

        $isWorkingDay = in_array(
            $currentDay,
            $workingDays,
            true
        );

        $isWithinHours =
            $currentTime >= $startTime &&
            $currentTime <= $endTime;

        $isBusinessHour =
            $isWorkingDay &&
            $isWithinHours;

        $dayNames = [
            'Sunday',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
        ];

        return response()->json([
            'status' => true,
            'message' => $isBusinessHour
                ? 'Currently within business hours'
                : 'Currently outside business hours',

            'is_business_hour' => $isBusinessHour,

            'current_time' => $currentTime,

            'current_day' =>
            $dayNames[$currentDay] ?? 'Unknown',

            'timezone' => $timezone,

            'business_hours' => [
                'start' => $startTime,
                'end' => $endTime,
                'working_days' => array_map(
                    fn($d) => $dayNames[$d] ?? $d,
                    $workingDays
                ),
            ],

            'checks' => [
                'is_working_day' => $isWorkingDay,
                'is_within_hours' => $isWithinHours,
            ],
        ]);
    }

    /**
     * Check whether today is a public holiday.
     */
    public function checkHoliday(Request $request)
    {
        $timezone = $this->getValidTimezone(
            $request->query('tz', 'Asia/Kolkata')
        );

        $today = Carbon::now($timezone)->toDateString();

        $holiday = Cache::remember(
            "holiday:{$today}",
            86400,
            function () use ($today) {
                return PublicHoliday::where('date', $today)
                    ->where('is_active', true)
                    ->first();
            }
        );

        $isHoliday = (bool) $holiday;

        return response()->json([
            'status' => true,

            'message' => $isHoliday
                ? "Today is a holiday: {$holiday->name}"
                : 'Today is not a public holiday',

            'is_holiday' => $isHoliday,

            'date' => $today,

            'timezone' => $timezone,

            'holiday' => $holiday
                ? [
                    'name' => $holiday->name,
                    'date' => $holiday->date->toDateString(),
                ]
                : null,
        ]);
    }

    /**
     * Application health check.
     */
    public function healthCheck(Request $request)
    {
        return response()->json([
            'status' => true,
            'message' => 'Application is healthy',
            'app_name' => config('app.name'),
            'laravel_version' => app()->version(),
            'php_version' => phpversion(),
            'timezone' => config('app.timezone'),
            'current_server_time' =>
            Carbon::now('Asia/Kolkata')->toDateTimeString(),
            'environment' => app()->environment(),
        ]);
    }

    /**
     * Calculate difference between two datetime values.
     */
    public function datetimeDifference(Request $request)
    {
        $request->validate([
            'start' => 'required|string',
            'end' => 'required|string',
            'timezone' => 'nullable|string',
        ]);

        $timezone = $this->getValidTimezone(
            $request->input('timezone', 'Asia/Kolkata')
        );

        try {
            $start = Carbon::parse(
                $request->input('start'),
                $timezone
            );

            $end = Carbon::parse(
                $request->input('end'),
                $timezone
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' =>
                'Invalid date/time format. Example: 2026-08-26 09:00:00',
            ], 422);
        }

        if ($end->lessThan($start)) {
            return response()->json([
                'status' => false,
                'message' =>
                'End datetime must be greater than or equal to start datetime.',
            ], 422);
        }

        $totalSeconds = $start->diffInSeconds($end);

        $days = intdiv($totalSeconds, 86400);
        $remainingSeconds = $totalSeconds % 86400;

        $hours = intdiv($remainingSeconds, 3600);
        $remainingSeconds %= 3600;

        $minutes = intdiv($remainingSeconds, 60);
        $seconds = $remainingSeconds % 60;

        $humanReadableParts = [];

        if ($days > 0) {
            $humanReadableParts[] =
                $days . ' ' . ($days === 1 ? 'day' : 'days');
        }

        if ($hours > 0) {
            $humanReadableParts[] =
                $hours . ' ' . ($hours === 1 ? 'hour' : 'hours');
        }

        if ($minutes > 0) {
            $humanReadableParts[] =
                $minutes . ' ' . ($minutes === 1 ? 'minute' : 'minutes');
        }

        if ($seconds > 0 || empty($humanReadableParts)) {
            $humanReadableParts[] =
                $seconds . ' ' . ($seconds === 1 ? 'second' : 'seconds');
        }

        return response()->json([
            'status' => true,
            'message' =>
            'DateTime difference calculated successfully',

            'timezone' => $timezone,

            'start_datetime' =>
            $start->toDateTimeString(),

            'end_datetime' =>
            $end->toDateTimeString(),

            'difference' => [
                'days' => $days,
                'hours' => $hours,
                'minutes' => $minutes,
                'seconds' => $seconds,
                'total_seconds' => $totalSeconds,
                'total_minutes' =>
                round($totalSeconds / 60, 2),
                'total_hours' =>
                round($totalSeconds / 3600, 2),

                'human_readable' =>
                implode(' ', $humanReadableParts),
            ],
        ]);
    }

    /**
     * Get detailed information about a specific date.
     */
    public function dateInfo(Request $request)
    {
        $request->validate([
            'date' => 'nullable|date',
            'timezone' => 'nullable|string',
        ]);

        $timezone = $this->getValidTimezone(
            $request->query('timezone', 'Asia/Kolkata')
        );

        try {
            $date = $request->query('date');

            $dt = $date
                ? Carbon::parse($date, $timezone)
                : Carbon::now($timezone);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' =>
                'Invalid date format. Use YYYY-MM-DD.',
            ], 422);
        }

        $dayNames = [
            'Sunday',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
        ];

        return response()->json([
            'status' => true,
            'message' =>
            'Date information retrieved successfully',

            'date' => $dt->toDateString(),

            'timezone' => $timezone,

            'information' => [
                'day' => $dayNames[$dt->dayOfWeek],
                'day_of_week' => $dt->dayOfWeek,
                'day_of_year' => $dt->dayOfYear,
                'week_of_year' => $dt->weekOfYear,

                'month' => $dt->month,
                'month_name' => $dt->format('F'),

                'year' => $dt->year,

                'quarter' => $dt->quarter,

                'days_in_month' =>
                $dt->daysInMonth,

                'is_weekend' =>
                $dt->isWeekend(),

                'is_leap_year' =>
                $dt->isLeapYear(),

                'start_of_month' =>
                $dt->copy()
                    ->startOfMonth()
                    ->toDateString(),

                'end_of_month' =>
                $dt->copy()
                    ->endOfMonth()
                    ->toDateString(),

                'start_of_year' =>
                $dt->copy()
                    ->startOfYear()
                    ->toDateString(),

                'end_of_year' =>
                $dt->copy()
                    ->endOfYear()
                    ->toDateString(),
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | NEW FEATURE #3
    |--------------------------------------------------------------------------
    | Date Calculation
    |
    | POST /api/date-calculation
    |--------------------------------------------------------------------------
    */
    public function dateCalculation(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'operation' => 'required|in:add,subtract',
            'value' => 'required|integer|min:1',
            'unit' => 'required|in:days,weeks,months,years',
            'timezone' => 'nullable|string',
        ]);

        $timezone = $this->getValidTimezone(
            $request->input('timezone', 'Asia/Kolkata')
        );

        try {
            $date = Carbon::parse(
                $request->input('date'),
                $timezone
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid date format.',
            ], 422);
        }

        $operation = $request->input('operation');
        $value = (int) $request->input('value');
        $unit = $request->input('unit');

        $method = $operation === 'add'
            ? 'add' . ucfirst($unit)
            : 'sub' . ucfirst($unit);

        $result = $date->copy()->{$method}($value);

        return response()->json([
            'status' => true,
            'message' => 'Date calculation completed successfully',

            'input' => [
                'date' => $date->toDateTimeString(),
                'operation' => $operation,
                'value' => $value,
                'unit' => $unit,
                'timezone' => $timezone,
            ],

            'result' => [
                'date' => $result->toDateString(),
                'date_time' => $result->toDateTimeString(),
                'day' => $result->format('l'),
                'timestamp' => $result->getTimestamp(),
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | NEW FEATURE #4
    |--------------------------------------------------------------------------
    | Age Calculator
    |
    | GET /api/age-calculator
    |--------------------------------------------------------------------------
    */
    public function ageCalculator(Request $request)
    {
        $request->validate([
            'birth_date' => 'required|date',
            'as_of' => 'nullable|date',
        ]);

        try {
            $birthDate = Carbon::parse(
                $request->input('birth_date')
            );

            $asOf = $request->filled('as_of')
                ? Carbon::parse($request->input('as_of'))
                : Carbon::today();
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid date format. Use YYYY-MM-DD.',
            ], 422);
        }

        if ($birthDate->greaterThan($asOf)) {
            return response()->json([
                'status' => false,
                'message' => 'Birth date cannot be in the future.',
            ], 422);
        }

        $difference = $birthDate->diff($asOf);

        $totalDays = $birthDate->diffInDays($asOf);

        $totalMonths = $birthDate->diffInMonths($asOf);

        return response()->json([
            'status' => true,
            'message' => 'Age calculated successfully',

            'birth_date' =>
            $birthDate->toDateString(),

            'as_of' =>
            $asOf->toDateString(),

            'age' => [
                'years' => $difference->y,
                'months' => $difference->m,
                'days' => $difference->d,
            ],

            'total' => [
                'days' => $totalDays,
                'months' => $totalMonths,
            ],

            'next_birthday' => [
                'date' => Carbon::create(
                    $asOf->year,
                    $birthDate->month,
                    $birthDate->day
                )->toDateString(),
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | NEW FEATURE #5
    |--------------------------------------------------------------------------
    | Unix Timestamp Converter
    |
    | GET /api/timestamp
    |--------------------------------------------------------------------------
    */
    public function timestamp(Request $request)
    {
        $request->validate([
            'timestamp' => 'nullable|integer',
            'datetime' => 'nullable|string',
            'timezone' => 'nullable|string',
        ]);

        if (!$request->filled('timestamp') && !$request->filled('datetime')) {
            $now = Carbon::now(
                $this->getValidTimezone(
                    $request->query('timezone', 'Asia/Kolkata')
                )
            );

            return response()->json([
                'status' => true,
                'message' => 'Current timestamp generated successfully',
                'timestamp' => $now->getTimestamp(),
                'datetime' => $now->toDateTimeString(),
                'iso8601' => $now->toIso8601String(),
                'timezone' => $now->getTimezone()->getName(),
            ]);
        }

        try {
            if ($request->filled('timestamp')) {
                $timestamp = (int) $request->input('timestamp');

                $timezone = $this->getValidTimezone(
                    $request->query('timezone', 'Asia/Kolkata')
                );

                $dt = Carbon::createFromTimestamp(
                    $timestamp,
                    $timezone
                );
            } else {
                $timezone = $this->getValidTimezone(
                    $request->query('timezone', 'Asia/Kolkata')
                );

                $dt = Carbon::parse(
                    $request->input('datetime'),
                    $timezone
                );

                $timestamp = $dt->getTimestamp();
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid timestamp or datetime.',
            ], 422);
        }

        return response()->json([
            'status' => true,
            'message' => 'Timestamp converted successfully',

            'timestamp' => $timestamp,

            'datetime' =>
            $dt->toDateTimeString(),

            'date' =>
            $dt->toDateString(),

            'time' =>
            $dt->toTimeString(),

            'day' =>
            $dt->format('l'),

            'iso8601' =>
            $dt->toIso8601String(),

            'timezone' =>
            $dt->getTimezone()->getName(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | NEW FEATURE #6
    |--------------------------------------------------------------------------
    | Business Days Calculator
    |
    | GET /api/business-days
    |--------------------------------------------------------------------------
    */
    public function businessDays(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        try {
            $start = Carbon::parse(
                $request->query('start_date')
            )->startOfDay();

            $end = Carbon::parse(
                $request->query('end_date')
            )->startOfDay();
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid date format.',
            ], 422);
        }

        if ($end->lessThan($start)) {
            return response()->json([
                'status' => false,
                'message' =>
                'End date must be greater than or equal to start date.',
            ], 422);
        }

        $totalDays = $start->diffInDays($end) + 1;

        $businessDays = 0;
        $weekendDays = 0;

        $current = $start->copy();

        while ($current->lessThanOrEqualTo($end)) {
            if ($current->isWeekend()) {
                $weekendDays++;
            } else {
                $businessDays++;
            }

            $current->addDay();
        }

        return response()->json([
            'status' => true,
            'message' => 'Business days calculated successfully',

            'start_date' =>
            $start->toDateString(),

            'end_date' =>
            $end->toDateString(),

            'total_calendar_days' =>
            $totalDays,

            'business_days' =>
            $businessDays,

            'weekend_days' =>
            $weekendDays,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | NEW FEATURE #7
    |--------------------------------------------------------------------------
    | Weekday Finder
    |
    | GET /api/weekday-finder
    |--------------------------------------------------------------------------
    */
    public function weekdayFinder(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'weekday' => 'required|integer|min:0|max:6',
            'direction' => 'required|in:next,previous',
        ]);

        try {
            $date = Carbon::parse(
                $request->query('date')
            )->startOfDay();
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid date format.',
            ], 422);
        }

        $weekday = (int) $request->query('weekday');

        $direction = $request->query('direction');

        $dayNames = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];

        $result = $date->copy();

        if ($direction === 'next') {
            $result->next($weekday);
        } else {
            $result->previous($weekday);
        }

        return response()->json([
            'status' => true,
            'message' => 'Weekday calculated successfully',

            'input' => [
                'date' =>
                $date->toDateString(),

                'weekday' =>
                $dayNames[$weekday],

                'direction' =>
                $direction,
            ],

            'result' => [
                'date' =>
                $result->toDateString(),

                'day' =>
                $result->format('l'),

                'days_difference' =>
                abs($date->diffInDays($result)),
            ],
        ]);
    }
}
