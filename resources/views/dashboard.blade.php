<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>DateTime Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: #0a0a0f;
            overflow-x: hidden;
        }

        .font-digital {
            font-family: 'Orbitron', monospace;
        }

        .clock-container {
            background:
                radial-gradient(ellipse at center,
                    #0f172a 0%,
                    #0a0a0f 70%);
        }

        .clock-face {
            background:
                radial-gradient(circle at 30% 30%,
                    #1e293b 0%,
                    #0f172a 50%,
                    #020617 100%);

            box-shadow:
                inset 0 2px 20px rgba(0, 0, 0, 0.8),
                0 25px 50px -12px rgba(0, 0, 0, 0.8),
                0 0 0 1px rgba(148, 163, 184, 0.1);
        }

        .digit-display {
            color: #38bdf8;

            text-shadow:
                0 0 10px rgba(56, 189, 248, 0.8),
                0 0 20px rgba(56, 189, 248, 0.5),
                0 0 40px rgba(56, 189, 248, 0.3);
        }

        .colon-glow {
            color: #38bdf8;

            text-shadow:
                0 0 5px rgba(56, 189, 248, 0.9),
                0 0 15px rgba(56, 189, 248, 0.5);
        }

        .colon-blink {
            animation: colonBlink 1s ease-in-out infinite;
        }

        @keyframes colonBlink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.2;
            }
        }

        .card {
            background:
                linear-gradient(145deg,
                    rgba(30, 41, 59, 0.8) 0%,
                    rgba(15, 23, 42, 0.9) 100%);

            border: 1px solid rgba(148, 163, 184, 0.1);

            backdrop-filter: blur(20px);

            box-shadow:
                0 4px 6px -1px rgba(0, 0, 0, 0.3);
        }

        .card:hover {
            border-color: rgba(56, 189, 248, 0.25);
        }

        .status-online {
            color: #4ade80;

            text-shadow:
                0 0 10px rgba(74, 222, 128, 0.5);
        }

        .status-offline {
            color: #f87171;

            text-shadow:
                0 0 10px rgba(248, 113, 113, 0.5);
        }

        .pulse-dot {
            animation:
                pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.7;
                transform: scale(1.1);
            }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .timezone-row {
            transition: all 0.2s ease;
        }

        .timezone-row:hover {
            background: rgba(56, 189, 248, 0.05);
        }

        .datetime-input {
            color-scheme: dark;
        }

        .input-style {
            width: 100%;
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 8px;
            padding: 10px 12px;
            color: #cbd5e1;
            font-size: 14px;
            outline: none;
        }

        .input-style:focus {
            border-color: #38bdf8;
            box-shadow: 0 0 0 1px #38bdf8;
        }

        .select-style {
            width: 100%;
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 8px;
            padding: 10px 12px;
            color: #cbd5e1;
            font-size: 14px;
            outline: none;
        }

        .select-style:focus {
            border-color: #38bdf8;
        }

        .btn-primary {
            width: 100%;
            padding: 10px 14px;
            border-radius: 8px;
            background: rgba(56, 189, 248, 0.1);
            border: 1px solid rgba(56, 189, 248, 0.25);
            color: #7dd3fc;
            font-size: 14px;
            font-weight: 600;
            transition: 0.2s;
        }

        .btn-primary:hover {
            background: rgba(56, 189, 248, 0.2);
            border-color: rgba(56, 189, 248, 0.5);
        }

        .result-box {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 10px;
            padding: 14px;
        }
    </style>

</head>

<body class="min-h-screen text-white">

    <div class="clock-container min-h-screen p-4 md:p-8">

        <!-- ========================================================= -->
        <!-- HEADER -->
        <!-- ========================================================= -->

        <div class="text-center mb-8 fade-in">

            <h1 class="text-2xl md:text-4xl font-bold text-sky-400 tracking-wider">
                DateTime Dashboard
            </h1>

            <p class="text-slate-500 mt-2">
                Real-time Global Time Monitor & Date Tools
            </p>

        </div>


        <!-- ========================================================= -->
        <!-- MAIN CLOCK -->
        <!-- ========================================================= -->

        <div class="clock-face rounded-3xl p-8 md:p-14 mb-8 w-full max-w-6xl mx-auto text-center">

            <div class="mb-5">

                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full
                bg-sky-500/10 text-sky-300 text-xs font-medium
                border border-sky-500/20">

                    <span class="w-2 h-2 bg-emerald-400 rounded-full pulse-dot"></span>

                    LIVE

                </span>

            </div>


            <div class="digit-display font-digital
            text-5xl sm:text-7xl md:text-8xl lg:text-9xl
            font-bold tracking-wider">

                <span id="clock-hours">00</span>

                <span class="colon-blink colon-glow">:</span>

                <span id="clock-minutes">00</span>

                <span class="colon-blink colon-glow">:</span>

                <span id="clock-seconds">00</span>

            </div>


            <div
                id="main-date"
                class="text-xl md:text-3xl text-slate-300 mt-5">
                Loading...
            </div>


            <div class="flex items-center justify-center gap-2 mt-5">

                <span class="text-[10px] uppercase tracking-widest text-slate-500">
                    Timezone
                </span>

                <span class="text-sky-300 bg-sky-500/10 border
                border-sky-500/20 rounded px-3 py-1 text-xs">
                    Asia/Kolkata (IST)
                </span>

            </div>

        </div>


        <!-- ========================================================= -->
        <!-- STATUS CARDS -->
        <!-- ========================================================= -->

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-6xl mx-auto mb-8">

            <!-- BUSINESS HOURS -->

            <div class="card rounded-2xl p-5">

                <div class="flex items-center gap-3 mb-4">

                    <div
                        id="business-indicator"
                        class="w-2.5 h-2.5 rounded-full bg-slate-600"></div>

                    <h3 class="text-sm font-semibold text-slate-300 uppercase">
                        Business Hours
                    </h3>

                </div>

                <p class="text-slate-500 text-xs">
                    Status
                </p>

                <p
                    id="business-status"
                    class="text-base font-semibold mt-1">
                    Checking...
                </p>

                <p
                    id="business-details"
                    class="text-xs text-slate-600 mt-2"></p>

            </div>


            <!-- HOLIDAY -->

            <div class="card rounded-2xl p-5">

                <div class="flex items-center gap-3 mb-4">

                    <div
                        id="holiday-indicator"
                        class="w-2.5 h-2.5 rounded-full bg-slate-600"></div>

                    <h3 class="text-sm font-semibold text-slate-300 uppercase">
                        Public Holiday
                    </h3>

                </div>

                <p class="text-slate-500 text-xs">
                    Today
                </p>

                <p
                    id="holiday-status"
                    class="text-base font-semibold mt-1">
                    Checking...
                </p>

                <p
                    id="holiday-details"
                    class="text-xs text-slate-600 mt-2"></p>

            </div>


            <!-- HEALTH -->

            <div class="card rounded-2xl p-5">

                <div class="flex items-center gap-3 mb-4">

                    <div class="w-2.5 h-2.5 rounded-full bg-emerald-400 pulse-dot"></div>

                    <h3 class="text-sm font-semibold text-slate-300 uppercase">
                        System Status
                    </h3>

                </div>

                <p class="text-slate-500 text-xs">
                    API Health
                </p>

                <p class="text-base font-semibold status-online mt-1">
                    Online
                </p>

                <p
                    id="server-time"
                    class="text-xs text-slate-600 mt-2">
                    Server: Loading...
                </p>

            </div>

        </div>


        <!-- ========================================================= -->
        <!-- FEATURE 1 - DATE INFORMATION -->
        <!-- ========================================================= -->

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 max-w-6xl mx-auto mb-6">

            <div class="card rounded-2xl p-6">

                <div class="flex items-center gap-3 mb-5">

                    <span class="w-2 h-2 bg-sky-400 rounded-full pulse-dot"></span>

                    <h2 class="text-sm font-semibold text-slate-300 uppercase tracking-wider">
                        Date Information
                    </h2>

                </div>


                <label class="text-xs text-slate-500">
                    Select Date
                </label>

                <input
                    type="date"
                    id="date-info-input"
                    class="input-style datetime-input mt-2">


                <div class="grid grid-cols-2 gap-3 mt-5">

                    <div class="result-box">
                        <p class="text-[10px] text-slate-600 uppercase">
                            Day
                        </p>

                        <p
                            id="date-info-day"
                            class="text-sky-400 font-semibold mt-1">
                            -
                        </p>
                    </div>


                    <div class="result-box">
                        <p class="text-[10px] text-slate-600 uppercase">
                            Month
                        </p>

                        <p
                            id="date-info-month"
                            class="text-sky-400 font-semibold mt-1">
                            -
                        </p>
                    </div>


                    <div class="result-box">
                        <p class="text-[10px] text-slate-600 uppercase">
                            Week
                        </p>

                        <p
                            id="date-info-week"
                            class="text-slate-300 font-semibold mt-1">
                            -
                        </p>
                    </div>


                    <div class="result-box">
                        <p class="text-[10px] text-slate-600 uppercase">
                            Quarter
                        </p>

                        <p
                            id="date-info-quarter"
                            class="text-slate-300 font-semibold mt-1">
                            -
                        </p>
                    </div>


                    <div class="result-box">
                        <p class="text-[10px] text-slate-600 uppercase">
                            Days In Month
                        </p>

                        <p
                            id="date-info-days"
                            class="text-slate-300 font-semibold mt-1">
                            -
                        </p>
                    </div>


                    <div class="result-box">

                        <p class="text-[10px] text-slate-600 uppercase">
                            Day Type
                        </p>

                        <p
                            id="date-info-weekend"
                            class="font-semibold mt-1">
                            -
                        </p>

                    </div>

                </div>

            </div>


            <!-- ===================================================== -->
            <!-- FEATURE 2 - DATETIME DIFFERENCE -->
            <!-- ===================================================== -->

            <div class="card rounded-2xl p-6">

                <div class="flex items-center gap-3 mb-5">

                    <span class="w-2 h-2 bg-emerald-400 rounded-full pulse-dot"></span>

                    <h2 class="text-sm font-semibold text-slate-300 uppercase tracking-wider">
                        DateTime Difference
                    </h2>

                </div>


                <label class="text-xs text-slate-500">
                    Start Date & Time
                </label>

                <input
                    type="datetime-local"
                    id="difference-start"
                    class="input-style datetime-input mt-2">


                <label class="text-xs text-slate-500 block mt-4">
                    End Date & Time
                </label>

                <input
                    type="datetime-local"
                    id="difference-end"
                    class="input-style datetime-input mt-2">


                <button
                    onclick="calculateDateTimeDifference()"
                    class="btn-primary mt-4">
                    Calculate Difference
                </button>


                <div class="result-box mt-5">

                    <p class="text-[10px] text-slate-600 uppercase">
                        Result
                    </p>

                    <p
                        id="difference-human"
                        class="text-sky-400 font-digital text-lg mt-2">
                        Enter start and end time
                    </p>


                    <div class="grid grid-cols-4 gap-3 mt-4">

                        <div>
                            <p class="text-[9px] text-slate-600 uppercase">
                                Days
                            </p>

                            <p
                                id="difference-days"
                                class="text-slate-300 mt-1">
                                -
                            </p>
                        </div>


                        <div>
                            <p class="text-[9px] text-slate-600 uppercase">
                                Hours
                            </p>

                            <p
                                id="difference-hours"
                                class="text-slate-300 mt-1">
                                -
                            </p>
                        </div>


                        <div>
                            <p class="text-[9px] text-slate-600 uppercase">
                                Minutes
                            </p>

                            <p
                                id="difference-minutes"
                                class="text-slate-300 mt-1">
                                -
                            </p>
                        </div>


                        <div>
                            <p class="text-[9px] text-slate-600 uppercase">
                                Seconds
                            </p>

                            <p
                                id="difference-seconds"
                                class="text-slate-300 mt-1">
                                -
                            </p>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- ========================================================= -->
        <!-- FEATURE 3 - TIMEZONE CONVERTER -->
        <!-- ========================================================= -->

        <div class="card rounded-2xl p-6 max-w-6xl mx-auto mb-6">

            <div class="flex items-center gap-3 mb-5">

                <span class="w-2 h-2 bg-purple-400 rounded-full pulse-dot"></span>

                <h2 class="text-sm font-semibold text-slate-300 uppercase tracking-wider">
                    Timezone Converter
                </h2>

            </div>


            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <div>

                    <label class="text-xs text-slate-500">
                        Date & Time
                    </label>

                    <input
                        type="datetime-local"
                        id="convert-datetime"
                        class="input-style datetime-input mt-2">

                </div>


                <div>

                    <label class="text-xs text-slate-500">
                        From Timezone
                    </label>

                    <select
                        id="from-timezone"
                        class="select-style mt-2">

                        <option value="Asia/Kolkata">
                            Asia/Kolkata
                        </option>

                        <option value="UTC">
                            UTC
                        </option>

                        <option value="America/New_York">
                            America/New_York
                        </option>

                        <option value="America/Los_Angeles">
                            America/Los_Angeles
                        </option>

                        <option value="Europe/London">
                            Europe/London
                        </option>

                        <option value="Europe/Paris">
                            Europe/Paris
                        </option>

                        <option value="Asia/Tokyo">
                            Asia/Tokyo
                        </option>

                        <option value="Asia/Dubai">
                            Asia/Dubai
                        </option>

                        <option value="Asia/Singapore">
                            Asia/Singapore
                        </option>

                    </select>

                </div>


                <div>

                    <label class="text-xs text-slate-500">
                        To Timezone
                    </label>

                    <select
                        id="to-timezone"
                        class="select-style mt-2">

                        <option value="UTC">
                            UTC
                        </option>

                        <option value="Asia/Kolkata">
                            Asia/Kolkata
                        </option>

                        <option value="America/New_York">
                            America/New_York
                        </option>

                        <option value="America/Los_Angeles">
                            America/Los_Angeles
                        </option>

                        <option value="Europe/London">
                            Europe/London
                        </option>

                        <option value="Europe/Paris">
                            Europe/Paris
                        </option>

                        <option value="Asia/Tokyo">
                            Asia/Tokyo
                        </option>

                        <option value="Asia/Dubai">
                            Asia/Dubai
                        </option>

                        <option value="Asia/Singapore">
                            Asia/Singapore
                        </option>

                    </select>

                </div>


                <div class="flex items-end">

                    <button
                        onclick="convertTimezone()"
                        class="btn-primary">
                        Convert Time
                    </button>

                </div>

            </div>


            <div
                id="conversion-result"
                class="result-box mt-5 hidden">

                <p class="text-[10px] text-slate-600 uppercase">
                    Converted DateTime
                </p>

                <p
                    id="converted-value"
                    class="text-sky-400 font-digital text-xl mt-2">
                    -
                </p>

                <p
                    id="converted-details"
                    class="text-xs text-slate-500 mt-2">
                </p>

            </div>

        </div>


        <!-- ========================================================= -->
        <!-- FEATURE 4 - ADD / SUBTRACT DAYS -->
        <!-- ========================================================= -->

        <div class="card rounded-2xl p-6 max-w-6xl mx-auto mb-6">

            <div class="flex items-center gap-3 mb-5">

                <span class="w-2 h-2 bg-orange-400 rounded-full pulse-dot"></span>

                <h2 class="text-sm font-semibold text-slate-300 uppercase tracking-wider">
                    Add / Subtract Days
                </h2>

            </div>


            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <div>

                    <label class="text-xs text-slate-500">
                        Start Date
                    </label>

                    <input
                        type="date"
                        id="add-date"
                        class="input-style datetime-input mt-2">

                </div>


                <div>

                    <label class="text-xs text-slate-500">
                        Number of Days
                    </label>

                    <input
                        type="number"
                        id="add-days"
                        value="7"
                        min="0"
                        class="input-style mt-2">

                </div>


                <div>

                    <label class="text-xs text-slate-500">
                        Operation
                    </label>

                    <select
                        id="add-operation"
                        class="select-style mt-2">

                        <option value="add">
                            Add Days
                        </option>

                        <option value="subtract">
                            Subtract Days
                        </option>

                    </select>

                </div>


                <div class="flex items-end">

                    <button
                        onclick="calculateAddSubtractDays()"
                        class="btn-primary">
                        Calculate
                    </button>

                </div>

            </div>


            <div class="result-box mt-5">

                <p class="text-[10px] text-slate-600 uppercase">
                    Result
                </p>

                <p
                    id="add-days-result"
                    class="text-orange-400 font-digital text-xl mt-2">
                    -
                </p>

            </div>

        </div>


        <!-- ========================================================= -->
        <!-- FEATURE 5 - DATE RANGE CALCULATOR -->
        <!-- ========================================================= -->

        <div class="card rounded-2xl p-6 max-w-6xl mx-auto mb-6">

            <div class="flex items-center gap-3 mb-5">

                <span class="w-2 h-2 bg-pink-400 rounded-full pulse-dot"></span>

                <h2 class="text-sm font-semibold text-slate-300 uppercase tracking-wider">
                    Date Range Calculator
                </h2>

            </div>


            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <div>

                    <label class="text-xs text-slate-500">
                        Start Date
                    </label>

                    <input
                        type="date"
                        id="range-start"
                        class="input-style datetime-input mt-2">

                </div>


                <div>

                    <label class="text-xs text-slate-500">
                        End Date
                    </label>

                    <input
                        type="date"
                        id="range-end"
                        class="input-style datetime-input mt-2">

                </div>


                <div class="flex items-end">

                    <button
                        onclick="calculateDateRange()"
                        class="btn-primary">
                        Calculate Range
                    </button>

                </div>

            </div>


            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-5">

                <div class="result-box">

                    <p class="text-[10px] text-slate-600 uppercase">
                        Days
                    </p>

                    <p
                        id="range-days"
                        class="text-pink-400 font-semibold text-xl mt-1">
                        -
                    </p>

                </div>


                <div class="result-box">

                    <p class="text-[10px] text-slate-600 uppercase">
                        Weeks
                    </p>

                    <p
                        id="range-weeks"
                        class="text-slate-300 font-semibold text-xl mt-1">
                        -
                    </p>

                </div>


                <div class="result-box">

                    <p class="text-[10px] text-slate-600 uppercase">
                        Months
                    </p>

                    <p
                        id="range-months"
                        class="text-slate-300 font-semibold text-xl mt-1">
                        -
                    </p>

                </div>


                <div class="result-box">

                    <p class="text-[10px] text-slate-600 uppercase">
                        Years
                    </p>

                    <p
                        id="range-years"
                        class="text-slate-300 font-semibold text-xl mt-1">
                        -
                    </p>

                </div>

            </div>

        </div>


        <!-- ========================================================= -->
        <!-- GLOBAL TIME COMPARISON -->
        <!-- ========================================================= -->

        <div class="card rounded-2xl p-6 md:p-8 max-w-6xl mx-auto">

            <div class="flex items-center justify-between mb-5">

                <h2 class="text-base md:text-lg font-semibold text-slate-300 uppercase tracking-wider">
                    Global Time Comparison
                </h2>

                <span class="text-[10px] text-slate-600 uppercase">
                    Auto Refresh: 1s
                </span>

            </div>


            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead>

                        <tr class="border-b border-slate-800">

                            <th class="text-left py-3 px-3 text-slate-500 text-xs uppercase">
                                Timezone
                            </th>

                            <th class="text-left py-3 px-3 text-slate-500 text-xs uppercase">
                                Date
                            </th>

                            <th class="text-left py-3 px-3 text-slate-500 text-xs uppercase">
                                Time
                            </th>

                            <th class="text-left py-3 px-3 text-slate-500 text-xs uppercase">
                                Daylight
                            </th>

                        </tr>

                    </thead>

                    <tbody id="timezone-table"></tbody>

                </table>

            </div>

        </div>


        <!-- FOOTER -->

        <div class="text-center mt-8">

            <p class="text-slate-700 text-xs uppercase tracking-widest">
                Powered by Laravel 12 API
            </p>

        </div>

    </div>


    <script>
        const API_BASE = '/api';

        const MAIN_TIMEZONE = 'Asia/Kolkata';


        /* ============================================================
           HELPER
        ============================================================ */

        function pad(number) {

            return String(number).padStart(2, '0');

        }


        function getLocalDateTimeValue(date) {

            return date.getFullYear() +
                '-' +
                pad(date.getMonth() + 1) +
                '-' +
                pad(date.getDate()) +
                'T' +
                pad(date.getHours()) +
                ':' +
                pad(date.getMinutes());

        }


        /* ============================================================
           MAIN CLOCK
        ============================================================ */

        function updateClock() {

            const now = new Date();


            const timeFormatter =
                new Intl.DateTimeFormat(
                    'en-GB', {
                        timeZone: MAIN_TIMEZONE,
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: false
                    }
                );


            const dateFormatter =
                new Intl.DateTimeFormat(
                    'en-US', {
                        timeZone: MAIN_TIMEZONE,
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    }
                );


            const parts =
                timeFormatter.formatToParts(now);


            const hours =
                parts.find(
                    part => part.type === 'hour'
                )?.value || '00';


            const minutes =
                parts.find(
                    part => part.type === 'minute'
                )?.value || '00';


            const seconds =
                parts.find(
                    part => part.type === 'second'
                )?.value || '00';


            document.getElementById(
                'clock-hours'
            ).textContent = hours;


            document.getElementById(
                'clock-minutes'
            ).textContent = minutes;


            document.getElementById(
                'clock-seconds'
            ).textContent = seconds;


            document.getElementById(
                    'main-date'
                ).textContent =
                dateFormatter.format(now);

        }


        /* ============================================================
           BUSINESS HOURS
        ============================================================ */

        function updateBusinessHours() {

            fetch(
                    `${API_BASE}/business-hours?tz=${encodeURIComponent(MAIN_TIMEZONE)}`
                )

                .then(response => {

                    if (!response.ok) {
                        throw new Error('Business hours request failed');
                    }

                    return response.json();

                })

                .then(data => {

                    const status =
                        document.getElementById('business-status');

                    const indicator =
                        document.getElementById('business-indicator');

                    const details =
                        document.getElementById('business-details');


                    status.textContent = data.message;


                    status.className =
                        'text-base font-semibold mt-1 ' +
                        (
                            data.is_business_hour ?
                            'status-online' :
                            'status-offline'
                        );


                    indicator.className =
                        'w-2.5 h-2.5 rounded-full ' +
                        (
                            data.is_business_hour ?
                            'bg-emerald-400 pulse-dot' :
                            'bg-red-400'
                        );


                    details.textContent =
                        `Current: ${data.current_day} ${data.current_time}`;

                })

                .catch(error => {

                    console.error(error);

                    document.getElementById(
                        'business-status'
                    ).textContent = 'Error';

                    document.getElementById(
                            'business-details'
                        ).textContent =
                        'Unable to check business hours';

                });

        }


        /* ============================================================
           HOLIDAY
        ============================================================ */

        function updateHolidayStatus() {

            fetch(
                    `${API_BASE}/holiday-check?tz=${encodeURIComponent(MAIN_TIMEZONE)}`
                )

                .then(response => {

                    if (!response.ok) {
                        throw new Error('Holiday request failed');
                    }

                    return response.json();

                })

                .then(data => {

                    const status =
                        document.getElementById('holiday-status');

                    const indicator =
                        document.getElementById('holiday-indicator');

                    const details =
                        document.getElementById('holiday-details');


                    status.textContent = data.message;


                    status.className =
                        'text-base font-semibold mt-1 ' +
                        (
                            data.is_holiday ?
                            'text-orange-400' :
                            'status-online'
                        );


                    indicator.className =
                        'w-2.5 h-2.5 rounded-full ' +
                        (
                            data.is_holiday ?
                            'bg-orange-400 pulse-dot' :
                            'bg-emerald-400'
                        );


                    details.textContent =
                        data.is_holiday ?
                        `Holiday: ${data.holiday?.name || 'Public Holiday'}` :
                        'No holiday today';

                })

                .catch(error => {

                    console.error(error);

                    document.getElementById(
                        'holiday-status'
                    ).textContent = 'Error';

                    document.getElementById(
                            'holiday-details'
                        ).textContent =
                        'Unable to check holiday';

                });

        }


        /* ============================================================
           HEALTH CHECK
        ============================================================ */

        function updateHealthCheck() {

            fetch(`${API_BASE}/health`)

                .then(response => {

                    if (!response.ok) {
                        throw new Error('Health check failed');
                    }

                    return response.json();

                })

                .then(data => {

                    document.getElementById(
                            'server-time'
                        ).textContent =
                        `Server: ${data.current_server_time}`;

                })

                .catch(error => {

                    console.error(error);

                    document.getElementById(
                            'server-time'
                        ).textContent =
                        'Server: Offline';

                });

        }


        /* ============================================================
           TIMEZONE COMPARISON
        ============================================================ */

        function updateTimezoneComparison() {

            fetch(
                    `${API_BASE}/compare-timezones`
                )

                .then(response => {

                    if (!response.ok) {
                        throw new Error(
                            'Timezone request failed'
                        );
                    }

                    return response.json();

                })

                .then(data => {

                    const tbody =
                        document.getElementById(
                            'timezone-table'
                        );


                    tbody.innerHTML = '';


                    if (
                        data.comparison &&
                        Array.isArray(data.comparison)
                    ) {

                        data.comparison.forEach(tz => {

                            const row =
                                document.createElement('tr');


                            row.className =
                                'timezone-row border-b border-slate-800/50';


                            row.innerHTML = `

                        <td class="py-3 px-3">

                            <span class="text-slate-300 text-sm">
                                ${tz.label}
                            </span>

                        </td>


                        <td class="py-3 px-3 text-slate-500 text-sm">
                            ${tz.date}
                        </td>


                        <td class="py-3 px-3">

                            <span class="font-digital text-sky-400 text-sm">
                                ${tz.time}
                            </span>

                        </td>


                        <td class="py-3 px-3">

                            ${
                                tz.is_daylight

                                    ? `
                                        <span class="px-2 py-1 rounded
                                            text-[10px]
                                            bg-amber-500/10
                                            text-amber-300
                                            border border-amber-500/20">
                                            Daylight
                                        </span>
                                    `

                                    : `
                                        <span class="px-2 py-1 rounded
                                            text-[10px]
                                            bg-slate-500/10
                                            text-slate-500
                                            border border-slate-500/20">
                                            Standard
                                        </span>
                                    `
                            }

                        </td>

                    `;


                            tbody.appendChild(row);

                        });

                    }

                })

                .catch(error => {

                    console.error(error);

                    document.getElementById(
                        'timezone-table'
                    ).innerHTML = `

                <tr>

                    <td
                        colspan="4"
                        class="text-center py-6 text-slate-600 text-xs"
                    >
                        Failed to load timezone data
                    </td>

                </tr>

            `;

                });

        }


        /* ============================================================
           FEATURE 1
           DATE INFORMATION
        ============================================================ */

        function updateDateInformation() {

            const input =
                document.getElementById(
                    'date-info-input'
                );


            const date = input.value;


            let url =
                `${API_BASE}/date-info?timezone=${encodeURIComponent(MAIN_TIMEZONE)}`;


            if (date) {

                url +=
                    `&date=${encodeURIComponent(date)}`;

            }


            fetch(url)

                .then(async response => {

                    const data =
                        await response.json();


                    if (!response.ok) {

                        throw new Error(
                            data.message ||
                            'Unable to load date information'
                        );

                    }


                    return data;

                })

                .then(data => {

                    const info =
                        data.information;


                    document.getElementById(
                            'date-info-day'
                        ).textContent =
                        info.day;


                    document.getElementById(
                            'date-info-month'
                        ).textContent =
                        info.month_name;


                    document.getElementById(
                            'date-info-week'
                        ).textContent =
                        `Week ${info.week_of_year}`;


                    document.getElementById(
                            'date-info-quarter'
                        ).textContent =
                        `Q${info.quarter}`;


                    document.getElementById(
                            'date-info-days'
                        ).textContent =
                        info.days_in_month;


                    const weekend =
                        document.getElementById(
                            'date-info-weekend'
                        );


                    weekend.textContent =
                        info.is_weekend ?
                        'Weekend' :
                        'Weekday';


                    weekend.className =
                        'font-semibold mt-1 ' +
                        (
                            info.is_weekend ?
                            'text-orange-400' :
                            'text-emerald-400'
                        );

                })

                .catch(error => {

                    console.error(error);

                    document.getElementById(
                        'date-info-day'
                    ).textContent = 'Error';

                });

        }


        /* ============================================================
           FEATURE 2
           DATETIME DIFFERENCE
        ============================================================ */

        function calculateDateTimeDifference() {

            const start =
                document.getElementById(
                    'difference-start'
                ).value;


            const end =
                document.getElementById(
                    'difference-end'
                ).value;


            const result =
                document.getElementById(
                    'difference-human'
                );


            if (!start || !end) {

                result.textContent =
                    'Please select both date and time';

                return;

            }


            const startDateTime =
                start.replace('T', ' ') + ':00';


            const endDateTime =
                end.replace('T', ' ') + ':00';


            result.textContent =
                'Calculating...';


            fetch(
                    `${API_BASE}/datetime-difference`, {
                        method: 'POST',

                        headers: {
                            'Content-Type': 'application/json',

                            'Accept': 'application/json',

                            'X-CSRF-TOKEN': document
                                .querySelector(
                                    'meta[name="csrf-token"]'
                                )
                                ?.getAttribute('content') || ''
                        },

                        body: JSON.stringify({

                            start: startDateTime,

                            end: endDateTime,

                            timezone: MAIN_TIMEZONE

                        })
                    }
                )

                .then(async response => {

                    const data =
                        await response.json();


                    if (!response.ok) {

                        throw new Error(
                            data.message ||
                            'Calculation failed'
                        );

                    }


                    return data;

                })

                .then(data => {

                    const difference =
                        data.difference;


                    result.textContent =
                        difference.human_readable;


                    document.getElementById(
                            'difference-days'
                        ).textContent =
                        difference.days;


                    document.getElementById(
                            'difference-hours'
                        ).textContent =
                        difference.hours;


                    document.getElementById(
                            'difference-minutes'
                        ).textContent =
                        difference.minutes;


                    document.getElementById(
                            'difference-seconds'
                        ).textContent =
                        difference.seconds;

                })

                .catch(error => {

                    result.textContent =
                        error.message;

                });

        }


        /* ============================================================
           FEATURE 3
           TIMEZONE CONVERTER
        ============================================================ */

        function convertTimezone() {

            const datetime =
                document.getElementById(
                    'convert-datetime'
                ).value;


            const fromTimezone =
                document.getElementById(
                    'from-timezone'
                ).value;


            const toTimezone =
                document.getElementById(
                    'to-timezone'
                ).value;


            if (!datetime) {

                alert(
                    'Please select date and time'
                );

                return;

            }


            const formatted =
                datetime.replace('T', ' ') + ':00';


            fetch(
                    `${API_BASE}/convert`, {

                        method: 'POST',

                        headers: {

                            'Content-Type': 'application/json',

                            'Accept': 'application/json',

                            'X-CSRF-TOKEN': document
                                .querySelector(
                                    'meta[name="csrf-token"]'
                                )
                                ?.getAttribute('content') || ''

                        },

                        body: JSON.stringify({

                            datetime: formatted,

                            from_timezone: fromTimezone,

                            to_timezone: toTimezone

                        })

                    }
                )

                .then(async response => {

                    const data =
                        await response.json();


                    if (!response.ok) {

                        throw new Error(
                            data.message ||
                            'Timezone conversion failed'
                        );

                    }


                    return data;

                })

                .then(data => {

                    document.getElementById(
                        'conversion-result'
                    ).classList.remove('hidden');


                    document.getElementById(
                            'converted-value'
                        ).textContent =
                        data.converted_datetime;


                    document.getElementById(
                            'converted-details'
                        ).textContent =
                        `${data.from_timezone} → ${data.to_timezone}`;

                })

                .catch(error => {

                    alert(error.message);

                });

        }


        /* ============================================================
           FEATURE 4
           ADD / SUBTRACT DAYS
           
           NOTE:
           This feature is frontend calculation.
        ============================================================ */

        function calculateAddSubtractDays() {

            const date =
                document.getElementById(
                    'add-date'
                ).value;


            const days =
                parseInt(
                    document.getElementById(
                        'add-days'
                    ).value
                );


            const operation =
                document.getElementById(
                    'add-operation'
                ).value;


            if (!date || isNaN(days)) {

                alert(
                    'Please enter date and number of days'
                );

                return;

            }


            const selectedDate =
                new Date(
                    date + 'T00:00:00'
                );


            if (operation === 'add') {

                selectedDate.setDate(
                    selectedDate.getDate() + days
                );

            } else {

                selectedDate.setDate(
                    selectedDate.getDate() - days
                );

            }


            const result =
                selectedDate.getFullYear() +
                '-' +
                pad(selectedDate.getMonth() + 1) +
                '-' +
                pad(selectedDate.getDate());


            document.getElementById(
                    'add-days-result'
                ).textContent =
                result;

        }


        /* ============================================================
           FEATURE 5
           DATE RANGE CALCULATOR
           
           NOTE:
           This feature is frontend calculation.
        ============================================================ */

        function calculateDateRange() {

            const start =
                document.getElementById(
                    'range-start'
                ).value;


            const end =
                document.getElementById(
                    'range-end'
                ).value;


            if (!start || !end) {

                alert(
                    'Please select both dates'
                );

                return;

            }


            const startDate =
                new Date(
                    start + 'T00:00:00'
                );


            const endDate =
                new Date(
                    end + 'T00:00:00'
                );


            if (endDate < startDate) {

                alert(
                    'End date must be greater than start date'
                );

                return;

            }


            const milliseconds =
                endDate.getTime() -
                startDate.getTime();


            const days =
                Math.floor(
                    milliseconds /
                    (1000 * 60 * 60 * 24)
                );


            const weeks =
                (days / 7).toFixed(2);


            const months =
                (
                    days / 30.44
                ).toFixed(2);


            const years =
                (
                    days / 365.25
                ).toFixed(2);


            document.getElementById(
                    'range-days'
                ).textContent =
                days;


            document.getElementById(
                    'range-weeks'
                ).textContent =
                weeks;


            document.getElementById(
                    'range-months'
                ).textContent =
                months;


            document.getElementById(
                    'range-years'
                ).textContent =
                years;

        }


        /* ============================================================
           INITIALIZE INPUTS
        ============================================================ */

        function initializeInputs() {

            const now =
                new Date();


            /* DATE */

            const today =
                now.getFullYear() +
                '-' +
                pad(now.getMonth() + 1) +
                '-' +
                pad(now.getDate());


            const dateInput =
                document.getElementById(
                    'date-info-input'
                );


            if (dateInput) {

                dateInput.value =
                    today;

                dateInput.addEventListener(
                    'change',
                    updateDateInformation
                );

            }


            /* DIFFERENCE */

            const currentDateTime =
                getLocalDateTimeValue(now);


            const tomorrow =
                new Date(
                    now.getTime() +
                    (24 * 60 * 60 * 1000)
                );


            document.getElementById(
                    'difference-start'
                ).value =
                currentDateTime;


            document.getElementById(
                    'difference-end'
                ).value =
                getLocalDateTimeValue(tomorrow);


            /* CONVERTER */

            document.getElementById(
                    'convert-datetime'
                ).value =
                currentDateTime;


            /* ADD / SUBTRACT */

            document.getElementById(
                    'add-date'
                ).value =
                today;


            /* RANGE */

            document.getElementById(
                    'range-start'
                ).value =
                today;


            document.getElementById(
                    'range-end'
                ).value =
                today;

        }


        /* ============================================================
           INIT
        ============================================================ */

        function init() {

            updateClock();

            updateBusinessHours();

            updateHolidayStatus();

            updateHealthCheck();

            updateTimezoneComparison();


            initializeInputs();

            updateDateInformation();


            setInterval(
                updateClock,
                1000
            );


            setInterval(
                updateBusinessHours,
                5000
            );


            setInterval(
                updateHolidayStatus,
                30000
            );


            setInterval(
                updateHealthCheck,
                10000
            );


            setInterval(
                updateTimezoneComparison,
                1000
            );

        }


        document.addEventListener(
            'DOMContentLoaded',
            init
        );
    </script>

</body>

</html>