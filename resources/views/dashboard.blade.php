<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

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

        .clock-face {
            background: radial-gradient(
                circle at 30% 30%,
                #1e293b 0%,
                #0f172a 50%,
                #020617 100%
            );

            box-shadow:
                inset 0 2px 20px rgba(0, 0, 0, 0.8),
                0 25px 50px -12px rgba(0, 0, 0, 0.8),
                0 0 0 1px rgba(148, 163, 184, 0.1);
        }

        .digit-segment {
            background: linear-gradient(
                180deg,
                #0f172a 0%,
                #020617 100%
            );

            box-shadow:
                inset 0 2px 4px rgba(0, 0, 0, 0.9),
                inset 0 -1px 2px rgba(148, 163, 184, 0.05);
        }

        .digit-display {
            color: #38bdf8;

            text-shadow:
                0 0 10px rgba(56, 189, 248, 0.8),
                0 0 20px rgba(56, 189, 248, 0.5),
                0 0 40px rgba(56, 189, 248, 0.3),
                0 0 80px rgba(56, 189, 248, 0.2),
                0 0 120px rgba(56, 189, 248, 0.1);

            letter-spacing: 0.05em;
        }

        .colon-glow {
            color: #38bdf8;

            text-shadow:
                0 0 5px rgba(56, 189, 248, 0.9),
                0 0 15px rgba(56, 189, 248, 0.5),
                0 0 30px rgba(56, 189, 248, 0.3);
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
            background: linear-gradient(
                145deg,
                rgba(30, 41, 59, 0.8) 0%,
                rgba(15, 23, 42, 0.9) 100%
            );

            border: 1px solid rgba(148, 163, 184, 0.1);

            backdrop-filter: blur(20px);

            box-shadow:
                0 4px 6px -1px rgba(0, 0, 0, 0.3);
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
                pulse
                2s
                cubic-bezier(0.4, 0, 0.6, 1)
                infinite;
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

        .label-glow {
            text-shadow:
                0 0 8px rgba(148, 163, 184, 0.3);
        }

        .time-glow {
            color: #38bdf8;

            text-shadow:
                0 0 8px rgba(56, 189, 248, 0.4);
        }

        .clock-container {
            background: radial-gradient(
                ellipse at center,
                #0f172a 0%,
                #0a0a0f 70%
            );
        }

        .inner-glow {
            box-shadow:
                inset 0 0 60px rgba(56, 189, 248, 0.03);
        }

        .datetime-input {
            color-scheme: dark;
        }

    </style>

</head>


<body class="min-h-screen text-white">

    <div
        class="clock-container min-h-screen flex flex-col items-center justify-center p-6 md:p-10 inner-glow"
    >

        <!-- ===================================================== -->
        <!-- HEADER -->
        <!-- ===================================================== -->

        <div class="text-center mb-8 fade-in">

            <h1
                class="text-2xl md:text-3xl font-bold mb-1 text-sky-400 tracking-wider label-glow"
            >
                DateTime Dashboard
            </h1>

            <p class="text-slate-500 text-sm md:text-base">
                Real-time Global Time Monitor
            </p>

        </div>


        <!-- ===================================================== -->
        <!-- MAIN CLOCK -->
        <!-- ===================================================== -->

        <div
            class="clock-face rounded-3xl p-10 md:p-16 mb-8 w-full max-w-5xl text-center relative overflow-hidden fade-in"
        >

            <div
                class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-sky-500/30 to-transparent"
            ></div>

            <div
                class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-sky-500/20 to-transparent"
            ></div>


            <div class="relative z-10">

                <div class="mb-6">

                    <span
                        class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-sky-500/10 text-sky-300 text-xs font-medium border border-sky-500/20 tracking-wide"
                    >

                        <span
                            class="w-1.5 h-1.5 bg-emerald-400 rounded-full pulse-dot"
                        ></span>

                        LIVE

                    </span>

                </div>


                <div
                    class="digit-display font-digital text-5xl sm:text-7xl md:text-8xl lg:text-[9rem] font-bold mb-3 tracking-wider leading-none"
                    id="main-clock"
                >

                    <span
                        id="clock-hours"
                        class="inline-block min-w-[1.2em] text-center"
                    >
                        00
                    </span>

                    <span
                        class="colon-blink colon-glow text-4xl sm:text-6xl md:text-7xl lg:text-[8rem] align-middle mx-1"
                    >
                        :
                    </span>

                    <span
                        id="clock-minutes"
                        class="inline-block min-w-[1.2em] text-center"
                    >
                        00
                    </span>

                    <span
                        class="colon-blink colon-glow text-4xl sm:text-6xl md:text-7xl lg:text-[8rem] align-middle mx-1"
                    >
                        :
                    </span>

                    <span
                        id="clock-seconds"
                        class="inline-block min-w-[1.2em] text-center"
                    >
                        00
                    </span>

                </div>


                <div
                    class="text-xl md:text-3xl text-slate-300 font-light mb-5 tracking-wide"
                    id="main-date"
                >
                    Loading...
                </div>


                <div
                    class="flex items-center justify-center gap-2 text-slate-500 text-xs md:text-sm"
                >

                    <span class="uppercase tracking-widest text-[10px]">
                        Timezone
                    </span>

                    <span class="w-px h-3 bg-slate-700"></span>

                    <span
                        class="px-3 py-1 bg-sky-500/10 rounded text-sky-300 font-medium border border-sky-500/20 tracking-wide"
                        id="timezone-display"
                    >
                        Asia/Kolkata (IST)
                    </span>

                </div>

            </div>

        </div>


        <!-- ===================================================== -->
        <!-- EXISTING STATUS CARDS -->
        <!-- ===================================================== -->

        <div
            class="grid grid-cols-1 md:grid-cols-3 gap-4 w-full max-w-5xl mb-6"
        >

            <!-- BUSINESS HOURS -->

            <div
                class="card rounded-2xl p-5 transition-all duration-200 hover:border-sky-500/20"
                id="business-hours-card"
            >

                <div class="flex items-center gap-2.5 mb-3">

                    <div
                        class="w-2 h-2 rounded-full bg-slate-600"
                        id="business-indicator"
                    ></div>

                    <h3
                        class="text-sm font-semibold text-slate-300 uppercase tracking-wider"
                    >
                        Business Hours
                    </h3>

                </div>


                <p
                    class="text-slate-500 text-[10px] uppercase tracking-wider mb-1"
                >
                    Status
                </p>

                <p
                    class="text-base font-semibold"
                    id="business-status"
                >
                    Checking...
                </p>

                <p
                    class="text-[11px] text-slate-600 mt-1.5"
                    id="business-details"
                ></p>

            </div>


            <!-- PUBLIC HOLIDAY -->

            <div
                class="card rounded-2xl p-5 transition-all duration-200 hover:border-sky-500/20"
                id="holiday-card"
            >

                <div class="flex items-center gap-2.5 mb-3">

                    <div
                        class="w-2 h-2 rounded-full bg-slate-600"
                        id="holiday-indicator"
                    ></div>

                    <h3
                        class="text-sm font-semibold text-slate-300 uppercase tracking-wider"
                    >
                        Public Holiday
                    </h3>

                </div>


                <p
                    class="text-slate-500 text-[10px] uppercase tracking-wider mb-1"
                >
                    Today
                </p>

                <p
                    class="text-base font-semibold"
                    id="holiday-status"
                >
                    Checking...
                </p>

                <p
                    class="text-[11px] text-slate-600 mt-1.5"
                    id="holiday-details"
                ></p>

            </div>


            <!-- SYSTEM STATUS -->

            <div
                class="card rounded-2xl p-5 transition-all duration-200 hover:border-sky-500/20"
            >

                <div class="flex items-center gap-2.5 mb-3">

                    <div
                        class="w-2 h-2 rounded-full bg-emerald-400 pulse-dot"
                    ></div>

                    <h3
                        class="text-sm font-semibold text-slate-300 uppercase tracking-wider"
                    >
                        System Status
                    </h3>

                </div>


                <p
                    class="text-slate-500 text-[10px] uppercase tracking-wider mb-1"
                >
                    API Health
                </p>

                <p
                    class="text-base font-semibold status-online"
                >
                    Online
                </p>

                <p
                    class="text-[11px] text-slate-600 mt-1.5"
                    id="server-time"
                >
                    Server: Loading...
                </p>

            </div>

        </div>


        <!-- ===================================================== -->
        <!-- NEW FEATURES -->
        <!-- ===================================================== -->

        <div
            class="grid grid-cols-1 lg:grid-cols-2 gap-4 w-full max-w-5xl mb-6"
        >

            <!-- ================================================= -->
            <!-- DATE INFORMATION -->
            <!-- ================================================= -->

            <div class="card rounded-2xl p-6">

                <div class="flex items-center gap-2.5 mb-5">

                    <div
                        class="w-2 h-2 rounded-full bg-sky-400 pulse-dot"
                    ></div>

                    <h3
                        class="text-sm font-semibold text-slate-300 uppercase tracking-wider"
                    >
                        Date Information
                    </h3>

                </div>


                <div class="mb-4">

                    <p
                        class="text-slate-500 text-[10px] uppercase tracking-wider mb-1"
                    >
                        Select Date
                    </p>

                    <input
                        type="date"
                        id="date-info-input"
                        class="datetime-input w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2.5 text-sm text-slate-300 focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition"
                    >

                </div>


                <div
                    id="date-info-result"
                    class="grid grid-cols-2 gap-3"
                >

                    <!-- DAY -->

                    <div class="bg-slate-900/60 rounded-lg p-3">

                        <p class="text-slate-600 text-[9px] uppercase">
                            Day
                        </p>

                        <p
                            id="date-info-day"
                            class="text-sky-400 font-semibold text-sm mt-1"
                        >
                            Loading...
                        </p>

                    </div>


                    <!-- MONTH -->

                    <div class="bg-slate-900/60 rounded-lg p-3">

                        <p class="text-slate-600 text-[9px] uppercase">
                            Month
                        </p>

                        <p
                            id="date-info-month"
                            class="text-sky-400 font-semibold text-sm mt-1"
                        >
                            Loading...
                        </p>

                    </div>


                    <!-- WEEK -->

                    <div class="bg-slate-900/60 rounded-lg p-3">

                        <p class="text-slate-600 text-[9px] uppercase">
                            Week
                        </p>

                        <p
                            id="date-info-week"
                            class="text-slate-300 font-semibold text-sm mt-1"
                        >
                            -
                        </p>

                    </div>


                    <!-- QUARTER -->

                    <div class="bg-slate-900/60 rounded-lg p-3">

                        <p class="text-slate-600 text-[9px] uppercase">
                            Quarter
                        </p>

                        <p
                            id="date-info-quarter"
                            class="text-slate-300 font-semibold text-sm mt-1"
                        >
                            -
                        </p>

                    </div>


                    <!-- DAYS IN MONTH -->

                    <div class="bg-slate-900/60 rounded-lg p-3">

                        <p class="text-slate-600 text-[9px] uppercase">
                            Days in Month
                        </p>

                        <p
                            id="date-info-days"
                            class="text-slate-300 font-semibold text-sm mt-1"
                        >
                            -
                        </p>

                    </div>


                    <!-- DAY TYPE -->

                    <div class="bg-slate-900/60 rounded-lg p-3">

                        <p class="text-slate-600 text-[9px] uppercase">
                            Day Type
                        </p>

                        <p
                            id="date-info-weekend"
                            class="font-semibold text-sm mt-1"
                        >
                            -
                        </p>

                    </div>

                </div>

            </div>


            <!-- ================================================= -->
            <!-- DATETIME DIFFERENCE -->
            <!-- ================================================= -->

            <div class="card rounded-2xl p-6">

                <div class="flex items-center gap-2.5 mb-5">

                    <div
                        class="w-2 h-2 rounded-full bg-emerald-400 pulse-dot"
                    ></div>

                    <h3
                        class="text-sm font-semibold text-slate-300 uppercase tracking-wider"
                    >
                        DateTime Difference
                    </h3>

                </div>


                <div class="space-y-3">

                    <!-- START -->

                    <div>

                        <p
                            class="text-slate-500 text-[10px] uppercase tracking-wider mb-1"
                        >
                            Start Date & Time
                        </p>

                        <input
                            type="datetime-local"
                            id="difference-start"
                            class="datetime-input w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2.5 text-sm text-slate-300 focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition"
                        >

                    </div>


                    <!-- END -->

                    <div>

                        <p
                            class="text-slate-500 text-[10px] uppercase tracking-wider mb-1"
                        >
                            End Date & Time
                        </p>

                        <input
                            type="datetime-local"
                            id="difference-end"
                            class="datetime-input w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2.5 text-sm text-slate-300 focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition"
                        >

                    </div>


                    <!-- BUTTON -->

                    <button
                        type="button"
                        onclick="calculateDateTimeDifference()"
                        class="w-full py-2.5 rounded-lg bg-sky-500/10 border border-sky-500/20 text-sky-300 text-sm font-semibold hover:bg-sky-500/20 hover:border-sky-400/40 transition duration-200"
                    >
                        Calculate Difference
                    </button>

                </div>


                <!-- RESULT -->

                <div
                    id="difference-result"
                    class="mt-5 bg-slate-900/60 rounded-xl p-4"
                >

                    <p
                        class="text-slate-600 text-[10px] uppercase tracking-wider"
                    >
                        Result
                    </p>


                    <p
                        id="difference-human"
                        class="text-sky-400 font-digital text-lg mt-2"
                    >
                        Enter start and end time
                    </p>


                    <div
                        class="grid grid-cols-2 gap-3 mt-4"
                    >

                        <!-- DAYS -->

                        <div>

                            <p
                                class="text-slate-600 text-[9px] uppercase"
                            >
                                Days
                            </p>

                            <p
                                id="difference-days"
                                class="text-slate-300 text-sm mt-1"
                            >
                                -
                            </p>

                        </div>


                        <!-- HOURS -->

                        <div>

                            <p
                                class="text-slate-600 text-[9px] uppercase"
                            >
                                Hours
                            </p>

                            <p
                                id="difference-hours"
                                class="text-slate-300 text-sm mt-1"
                            >
                                -
                            </p>

                        </div>


                        <!-- MINUTES -->

                        <div>

                            <p
                                class="text-slate-600 text-[9px] uppercase"
                            >
                                Minutes
                            </p>

                            <p
                                id="difference-minutes"
                                class="text-slate-300 text-sm mt-1"
                            >
                                -
                            </p>

                        </div>


                        <!-- SECONDS -->

                        <div>

                            <p
                                class="text-slate-600 text-[9px] uppercase"
                            >
                                Seconds
                            </p>

                            <p
                                id="difference-seconds"
                                class="text-slate-300 text-sm mt-1"
                            >
                                -
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- ===================================================== -->
        <!-- GLOBAL TIME COMPARISON -->
        <!-- ===================================================== -->

        <div
            class="card rounded-2xl p-6 md:p-8 w-full max-w-5xl"
        >

            <div
                class="flex items-center justify-between mb-5"
            >

                <h2
                    class="text-base md:text-lg font-semibold text-slate-300 uppercase tracking-wider"
                >
                    Global Time Comparison
                </h2>

                <span
                    class="text-[10px] text-slate-600 uppercase tracking-wider"
                >
                    Auto-refresh: 1s
                </span>

            </div>


            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead>

                        <tr class="border-b border-slate-800">

                            <th
                                class="text-left py-2.5 px-3 text-slate-500 font-medium text-[10px] uppercase tracking-wider"
                            >
                                Timezone
                            </th>

                            <th
                                class="text-left py-2.5 px-3 text-slate-500 font-medium text-[10px] uppercase tracking-wider"
                            >
                                Date
                            </th>

                            <th
                                class="text-left py-2.5 px-3 text-slate-500 font-medium text-[10px] uppercase tracking-wider"
                            >
                                Time
                            </th>

                            <th
                                class="text-left py-2.5 px-3 text-slate-500 font-medium text-[10px] uppercase tracking-wider"
                            >
                                Daylight
                            </th>

                        </tr>

                    </thead>


                    <tbody id="timezone-table">

                    </tbody>

                </table>

            </div>

        </div>


        <!-- ===================================================== -->
        <!-- FOOTER -->
        <!-- ===================================================== -->

        <div class="mt-6 text-center">

            <p
                class="text-slate-700 text-[10px] uppercase tracking-widest"
            >
                Powered by Laravel 12 API
            </p>

        </div>

    </div>


    <!-- ========================================================= -->
    <!-- JAVASCRIPT -->
    <!-- ========================================================= -->

    <script>

        const API_BASE = '/api';

        const MAIN_TIMEZONE = 'Asia/Kolkata';


        // =========================================================
        // HELPER
        // =========================================================

        function pad(n) {

            return n
                .toString()
                .padStart(2, '0');

        }


        // =========================================================
        // MAIN CLOCK
        // =========================================================

        function updateClock() {

            const now = new Date();


            const timeFormatter =
                new Intl.DateTimeFormat(
                    'en-GB',
                    {
                        timeZone: MAIN_TIMEZONE,
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: false
                    }
                );


            const dateFormatter =
                new Intl.DateTimeFormat(
                    'en-US',
                    {
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


        // =========================================================
        // BUSINESS HOURS
        // =========================================================

        function updateBusinessHours() {

            fetch(
                `${API_BASE}/business-hours?tz=${encodeURIComponent(MAIN_TIMEZONE)}`
            )

                .then(res => {

                    if (!res.ok) {
                        throw new Error(
                            'Business hours request failed'
                        );
                    }

                    return res.json();

                })

                .then(data => {

                    const statusEl =
                        document.getElementById(
                            'business-status'
                        );


                    const indicatorEl =
                        document.getElementById(
                            'business-indicator'
                        );


                    const detailsEl =
                        document.getElementById(
                            'business-details'
                        );


                    statusEl.textContent =
                        data.message;


                    statusEl.className =
                        'text-base font-semibold ' +
                        (
                            data.is_business_hour
                                ? 'status-online'
                                : 'status-offline'
                        );


                    indicatorEl.className =
                        'w-2 h-2 rounded-full ' +
                        (
                            data.is_business_hour
                                ? 'bg-emerald-400 pulse-dot'
                                : 'bg-red-400'
                        );


                    detailsEl.textContent =
                        `Current: ${data.current_day} ${data.current_time}`;

                })

                .catch(error => {

                    console.error(
                        'Business hours error:',
                        error
                    );

                    document.getElementById(
                        'business-status'
                    ).textContent = 'Error';

                    document.getElementById(
                        'business-details'
                    ).textContent =
                        'Unable to check business hours';

                });

        }


        // =========================================================
        // HOLIDAY STATUS
        // =========================================================

        function updateHolidayStatus() {

            fetch(
                `${API_BASE}/holiday-check?tz=${encodeURIComponent(MAIN_TIMEZONE)}`
            )

                .then(res => {

                    if (!res.ok) {
                        throw new Error(
                            'Holiday request failed'
                        );
                    }

                    return res.json();

                })

                .then(data => {

                    const statusEl =
                        document.getElementById(
                            'holiday-status'
                        );


                    const indicatorEl =
                        document.getElementById(
                            'holiday-indicator'
                        );


                    const detailsEl =
                        document.getElementById(
                            'holiday-details'
                        );


                    statusEl.textContent =
                        data.message;


                    statusEl.className =
                        'text-base font-semibold ' +
                        (
                            data.is_holiday
                                ? 'text-orange-400'
                                : 'status-online'
                        );


                    indicatorEl.className =
                        'w-2 h-2 rounded-full ' +
                        (
                            data.is_holiday
                                ? 'bg-orange-400 pulse-dot'
                                : 'bg-emerald-400'
                        );


                    detailsEl.textContent =
                        data.is_holiday
                            ? `Holiday: ${data.holiday?.name || 'Public Holiday'}`
                            : 'No holiday today';

                })

                .catch(error => {

                    console.error(
                        'Holiday error:',
                        error
                    );

                    document.getElementById(
                        'holiday-status'
                    ).textContent = 'Error';

                    document.getElementById(
                        'holiday-details'
                    ).textContent =
                        'Unable to check holiday';

                });

        }


        // =========================================================
        // HEALTH CHECK
        // =========================================================

        function updateHealthCheck() {

            fetch(`${API_BASE}/health`)

                .then(res => {

                    if (!res.ok) {
                        throw new Error(
                            'Health check failed'
                        );
                    }

                    return res.json();

                })

                .then(data => {

                    document.getElementById(
                        'server-time'
                    ).textContent =
                        `Server: ${data.current_server_time}`;

                })

                .catch(error => {

                    console.error(
                        'Health check error:',
                        error
                    );

                    document.getElementById(
                        'server-time'
                    ).textContent =
                        'Server: Offline';

                });

        }


        // =========================================================
        // TIMEZONE COMPARISON
        // =========================================================

        function updateTimezoneComparison() {

            fetch(
                `${API_BASE}/compare-timezones`
            )

                .then(res => {

                    if (!res.ok) {
                        throw new Error(
                            'Timezone request failed'
                        );
                    }

                    return res.json();

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

                                <td class="py-2.5 px-3">

                                    <div
                                        class="font-medium text-slate-300 text-xs md:text-sm label-glow"
                                    >
                                        ${tz.label}
                                    </div>

                                </td>


                                <td
                                    class="py-2.5 px-3 text-slate-500 text-xs md:text-sm"
                                >
                                    ${tz.date}
                                </td>


                                <td class="py-2.5 px-3">

                                    <span
                                        class="font-digital text-sky-400 text-xs md:text-sm time-glow"
                                    >
                                        ${tz.time}
                                    </span>

                                </td>


                                <td class="py-2.5 px-3">

                                    ${
                                        tz.is_daylight

                                            ? `
                                                <span
                                                    class="px-2 py-0.5 bg-amber-500/10 text-amber-300 rounded text-[10px] border border-amber-500/20"
                                                >
                                                    Daylight
                                                </span>
                                            `

                                            : `
                                                <span
                                                    class="px-2 py-0.5 bg-slate-500/10 text-slate-500 rounded text-[10px] border border-slate-500/20"
                                                >
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

                    console.error(
                        'Timezone comparison error:',
                        error
                    );

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


        // =========================================================
        // DATE INFORMATION
        // =========================================================

        function updateDateInformation() {

            const input =
                document.getElementById(
                    'date-info-input'
                );


            if (!input) {
                return;
            }


            const date =
                input.value;


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


                    const weekendEl =
                        document.getElementById(
                            'date-info-weekend'
                        );


                    weekendEl.textContent =
                        info.is_weekend
                            ? 'Weekend'
                            : 'Weekday';


                    weekendEl.className =
                        'font-semibold text-sm mt-1 ' +
                        (
                            info.is_weekend
                                ? 'text-orange-400'
                                : 'text-emerald-400'
                        );

                })

                .catch(error => {

                    console.error(
                        'Date information error:',
                        error
                    );


                    document.getElementById(
                        'date-info-day'
                    ).textContent =
                        'Error';


                    document.getElementById(
                        'date-info-month'
                    ).textContent =
                        '-';


                    document.getElementById(
                        'date-info-week'
                    ).textContent =
                        '-';


                    document.getElementById(
                        'date-info-quarter'
                    ).textContent =
                        '-';


                    document.getElementById(
                        'date-info-days'
                    ).textContent =
                        '-';


                    document.getElementById(
                        'date-info-weekend'
                    ).textContent =
                        'Unable to load';

                });

        }


        // =========================================================
        // DATETIME DIFFERENCE
        // =========================================================

        function calculateDateTimeDifference() {

            const startInput =
                document.getElementById(
                    'difference-start'
                );


            const endInput =
                document.getElementById(
                    'difference-end'
                );


            const start =
                startInput.value;


            const end =
                endInput.value;


            const humanEl =
                document.getElementById(
                    'difference-human'
                );


            if (!start || !end) {

                humanEl.textContent =
                    'Please select both date and time';

                humanEl.className =
                    'text-orange-400 font-digital text-sm mt-2';

                return;

            }


            const startDateTime =
                start.replace('T', ' ') + ':00';


            const endDateTime =
                end.replace('T', ' ') + ':00';


            humanEl.textContent =
                'Calculating...';

            humanEl.className =
                'text-sky-400 font-digital text-sm mt-2';


            fetch(
                `${API_BASE}/datetime-difference`,
                {

                    method: 'POST',

                    headers: {

                        'Content-Type':
                            'application/json',

                        'Accept':
                            'application/json',

                        'X-CSRF-TOKEN':
                            document
                                .querySelector(
                                    'meta[name="csrf-token"]'
                                )
                                ?.getAttribute(
                                    'content'
                                ) || ''

                    },


                    body: JSON.stringify({

                        start:
                            startDateTime,

                        end:
                            endDateTime,

                        timezone:
                            MAIN_TIMEZONE

                    })

                }
            )

                .then(async response => {

                    const data =
                        await response.json();


                    if (!response.ok) {

                        throw new Error(
                            data.message ||
                            'Unable to calculate difference'
                        );

                    }


                    return data;

                })

                .then(data => {

                    const difference =
                        data.difference;


                    humanEl.textContent =
                        difference.human_readable;


                    humanEl.className =
                        'text-sky-400 font-digital text-lg mt-2';


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

                    console.error(
                        'DateTime difference error:',
                        error
                    );


                    humanEl.textContent =
                        error.message ||
                        'Calculation failed';


                    humanEl.className =
                        'text-red-400 font-digital text-sm mt-2';


                    document.getElementById(
                        'difference-days'
                    ).textContent =
                        '-';


                    document.getElementById(
                        'difference-hours'
                    ).textContent =
                        '-';


                    document.getElementById(
                        'difference-minutes'
                    ).textContent =
                        '-';


                    document.getElementById(
                        'difference-seconds'
                    ).textContent =
                        '-';

                });

        }


        // =========================================================
        // SET DEFAULT DATES
        // =========================================================

        function initializeDateInputs() {

            const now =
                new Date();


            // Today's date

            const todayString =
                now.getFullYear() +
                '-' +
                String(
                    now.getMonth() + 1
                ).padStart(2, '0') +
                '-' +
                String(
                    now.getDate()
                ).padStart(2, '0');


            const dateInput =
                document.getElementById(
                    'date-info-input'
                );


            if (dateInput) {

                dateInput.value =
                    todayString;


                dateInput.addEventListener(
                    'change',
                    updateDateInformation
                );

            }


            // Current datetime

            const currentDateTime =
                now.getFullYear() +
                '-' +
                String(
                    now.getMonth() + 1
                ).padStart(2, '0') +
                '-' +
                String(
                    now.getDate()
                ).padStart(2, '0'
                ) +
                'T' +
                String(
                    now.getHours()
                ).padStart(2, '0') +
                ':' +
                String(
                    now.getMinutes()
                ).padStart(2, '0');


            const startInput =
                document.getElementById(
                    'difference-start'
                );


            const endInput =
                document.getElementById(
                    'difference-end'
                );


            if (startInput) {

                startInput.value =
                    currentDateTime;

            }


            if (endInput) {

                const futureDate =
                    new Date(
                        now.getTime() +
                        24 * 60 * 60 * 1000
                    );


                const futureDateTime =
                    futureDate.getFullYear() +
                    '-' +
                    String(
                        futureDate.getMonth() + 1
                    ).padStart(2, '0') +
                    '-' +
                    String(
                        futureDate.getDate()
                    ).padStart(2, '0') +
                    'T' +
                    String(
                        futureDate.getHours()
                    ).padStart(2, '0') +
                    ':' +
                    String(
                        futureDate.getMinutes()
                    ).padStart(2, '0');


                endInput.value =
                    futureDateTime;

            }

        }


        // =========================================================
        // INITIALIZE DASHBOARD
        // =========================================================

        function init() {

            // Existing features

            updateClock();

            updateBusinessHours();

            updateHolidayStatus();

            updateHealthCheck();

            updateTimezoneComparison();


            // New features

            initializeDateInputs();

            updateDateInformation();


            // Refresh intervals

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