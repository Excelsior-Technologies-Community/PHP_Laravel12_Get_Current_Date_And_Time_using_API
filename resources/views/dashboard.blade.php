<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            background: radial-gradient(circle at 30% 30%, #1e293b 0%, #0f172a 50%, #020617 100%);
            box-shadow:
                inset 0 2px 20px rgba(0, 0, 0, 0.8),
                0 25px 50px -12px rgba(0, 0, 0, 0.8),
                0 0 0 1px rgba(148, 163, 184, 0.1);
        }

        .digit-segment {
            background: linear-gradient(180deg, #0f172a 0%, #020617 100%);
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
            0%, 100% { opacity: 1; }
            50% { opacity: 0.2; }
        }

        .card {
            background: linear-gradient(145deg, rgba(30, 41, 59, 0.8) 0%, rgba(15, 23, 42, 0.9) 100%);
            border: 1px solid rgba(148, 163, 184, 0.1);
            backdrop-filter: blur(20px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
        }

        .status-online {
            color: #4ade80;
            text-shadow: 0 0 10px rgba(74, 222, 128, 0.5);
        }

        .status-offline {
            color: #f87171;
            text-shadow: 0 0 10px rgba(248, 113, 113, 0.5);
        }

        .pulse-dot {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.1); }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .timezone-row {
            transition: all 0.2s ease;
        }

        .timezone-row:hover {
            background: rgba(56, 189, 248, 0.05);
        }

        .label-glow {
            text-shadow: 0 0 8px rgba(148, 163, 184, 0.3);
        }

        .time-glow {
            color: #38bdf8;
            text-shadow: 0 0 8px rgba(56, 189, 248, 0.4);
        }

        .clock-container {
            background: radial-gradient(ellipse at center, #0f172a 0%, #0a0a0f 70%);
        }

        .inner-glow {
            box-shadow: inset 0 0 60px rgba(56, 189, 248, 0.03);
        }
    </style>
</head>
<body class="min-h-screen text-white">

    <div class="clock-container min-h-screen flex flex-col items-center justify-center p-6 md:p-10 inner-glow">

        <div class="text-center mb-8 fade-in">
            <h1 class="text-2xl md:text-3xl font-bold mb-1 text-sky-400 tracking-wider label-glow">DateTime Dashboard</h1>
            <p class="text-slate-500 text-sm md:text-base">Real-time Global Time Monitor</p>
        </div>

        <div class="clock-face rounded-3xl p-10 md:p-16 mb-8 w-full max-w-5xl text-center relative overflow-hidden fade-in">
            <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-sky-500/30 to-transparent"></div>
            <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-sky-500/20 to-transparent"></div>

            <div class="relative z-10">
                <div class="mb-6">
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-sky-500/10 text-sky-300 text-xs font-medium border border-sky-500/20 tracking-wide">
                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full pulse-dot"></span>
                        LIVE
                    </span>
                </div>

                <div class="digit-display font-digital text-5xl sm:text-7xl md:text-8xl lg:text-[9rem] font-bold mb-3 tracking-wider leading-none" id="main-clock">
                    <span id="clock-hours" class="inline-block min-w-[1.2em] text-center">00</span><span class="colon-blink colon-glow text-4xl sm:text-6xl md:text-7xl lg:text-[8rem] align-middle mx-1">:</span><span id="clock-minutes" class="inline-block min-w-[1.2em] text-center">00</span><span class="colon-blink colon-glow text-4xl sm:text-6xl md:text-7xl lg:text-[8rem] align-middle mx-1">:</span><span id="clock-seconds" class="inline-block min-w-[1.2em] text-center">00</span>
                </div>

                <div class="text-xl md:text-3xl text-slate-300 font-light mb-5 tracking-wide" id="main-date">
                    Loading...
                </div>

                <div class="flex items-center justify-center gap-2 text-slate-500 text-xs md:text-sm">
                    <span class="uppercase tracking-widest text-[10px]">Timezone</span>
                    <span class="w-px h-3 bg-slate-700"></span>
                    <span class="px-3 py-1 bg-sky-500/10 rounded text-sky-300 font-medium border border-sky-500/20 tracking-wide" id="timezone-display">
                        Asia/Kolkata (IST)
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 w-full max-w-5xl mb-6">
            <div class="card rounded-2xl p-5 transition-all duration-200 hover:border-sky-500/20" id="business-hours-card">
                <div class="flex items-center gap-2.5 mb-3">
                    <div class="w-2 h-2 rounded-full bg-slate-600" id="business-indicator"></div>
                    <h3 class="text-sm font-semibold text-slate-300 uppercase tracking-wider">Business Hours</h3>
                </div>
                <p class="text-slate-500 text-[10px] uppercase tracking-wider mb-1">Status</p>
                <p class="text-base font-semibold" id="business-status">Checking...</p>
                <p class="text-[11px] text-slate-600 mt-1.5" id="business-details"></p>
            </div>

            <div class="card rounded-2xl p-5 transition-all duration-200 hover:border-sky-500/20" id="holiday-card">
                <div class="flex items-center gap-2.5 mb-3">
                    <div class="w-2 h-2 rounded-full bg-slate-600" id="holiday-indicator"></div>
                    <h3 class="text-sm font-semibold text-slate-300 uppercase tracking-wider">Public Holiday</h3>
                </div>
                <p class="text-slate-500 text-[10px] uppercase tracking-wider mb-1">Today</p>
                <p class="text-base font-semibold" id="holiday-status">Checking...</p>
                <p class="text-[11px] text-slate-600 mt-1.5" id="holiday-details"></p>
            </div>

            <div class="card rounded-2xl p-5 transition-all duration-200 hover:border-sky-500/20">
                <div class="flex items-center gap-2.5 mb-3">
                    <div class="w-2 h-2 rounded-full bg-emerald-400 pulse-dot"></div>
                    <h3 class="text-sm font-semibold text-slate-300 uppercase tracking-wider">System Status</h3>
                </div>
                <p class="text-slate-500 text-[10px] uppercase tracking-wider mb-1">API Health</p>
                <p class="text-base font-semibold status-online">Online</p>
                <p class="text-[11px] text-slate-600 mt-1.5" id="server-time">Server: Loading...</p>
            </div>
        </div>

        <div class="card rounded-2xl p-6 md:p-8 w-full max-w-5xl">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-base md:text-lg font-semibold text-slate-300 uppercase tracking-wider">Global Time Comparison</h2>
                <span class="text-[10px] text-slate-600 uppercase tracking-wider">Auto-refresh: 1s</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-800">
                            <th class="text-left py-2.5 px-3 text-slate-500 font-medium text-[10px] uppercase tracking-wider">Timezone</th>
                            <th class="text-left py-2.5 px-3 text-slate-500 font-medium text-[10px] uppercase tracking-wider">Date</th>
                            <th class="text-left py-2.5 px-3 text-slate-500 font-medium text-[10px] uppercase tracking-wider">Time</th>
                            <th class="text-left py-2.5 px-3 text-slate-500 font-medium text-[10px] uppercase tracking-wider">Daylight</th>
                        </tr>
                    </thead>
                    <tbody id="timezone-table">
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6 text-center">
            <p class="text-slate-700 text-[10px] uppercase tracking-widest">Powered by Laravel 12 API</p>
        </div>
    </div>

    <script>
        const API_BASE = '/api';
        const MAIN_TIMEZONE = 'Asia/Kolkata';

        function pad(n) {
            return n.toString().padStart(2, '0');
        }

        function updateClock() {
            const now = new Date();
            const h = pad(now.getHours());
            const m = pad(now.getMinutes());
            const s = pad(now.getSeconds());

            document.getElementById('clock-hours').textContent = h;
            document.getElementById('clock-minutes').textContent = m;
            document.getElementById('clock-seconds').textContent = s;

            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('main-date').textContent = now.toLocaleDateString('en-US', options);
        }

        function updateBusinessHours() {
            fetch(`${API_BASE}/business-hours?tz=${MAIN_TIMEZONE}`)
                .then(res => res.json())
                .then(data => {
                    const statusEl = document.getElementById('business-status');
                    const indicatorEl = document.getElementById('business-indicator');
                    const detailsEl = document.getElementById('business-details');

                    statusEl.textContent = data.message;
                    statusEl.className = 'text-base font-semibold ' + (data.is_business_hour ? 'status-online' : 'status-offline');
                    indicatorEl.className = 'w-2 h-2 rounded-full ' + (data.is_business_hour ? 'bg-emerald-400 pulse-dot' : 'bg-red-400');
                    detailsEl.textContent = `Current: ${data.current_day} ${data.current_time}`;
                })
                .catch(() => {
                    document.getElementById('business-status').textContent = 'Error';
                });
        }

        function updateHolidayStatus() {
            fetch(`${API_BASE}/holiday-check?tz=${MAIN_TIMEZONE}`)
                .then(res => res.json())
                .then(data => {
                    const statusEl = document.getElementById('holiday-status');
                    const indicatorEl = document.getElementById('holiday-indicator');
                    const detailsEl = document.getElementById('holiday-details');

                    statusEl.textContent = data.message;
                    statusEl.className = 'text-base font-semibold ' + (data.is_holiday ? 'text-orange-400' : 'status-online');
                    indicatorEl.className = 'w-2 h-2 rounded-full ' + (data.is_holiday ? 'bg-orange-400 pulse-dot' : 'bg-emerald-400');
                    detailsEl.textContent = data.is_holiday ? `Holiday: ${data.holiday?.name}` : 'No holiday today';
                })
                .catch(() => {
                    document.getElementById('holiday-status').textContent = 'Error';
                });
        }

        function updateHealthCheck() {
            fetch(`${API_BASE}/health`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('server-time').textContent = `Server: ${data.current_server_time}`;
                })
                .catch(() => {
                    document.getElementById('server-time').textContent = 'Server: Offline';
                });
        }

        function updateTimezoneComparison() {
            fetch(`${API_BASE}/compare-timezones`)
                .then(res => res.json())
                .then(data => {
                    const tbody = document.getElementById('timezone-table');
                    tbody.innerHTML = '';

                    if (data.comparison) {
                        data.comparison.forEach(tz => {
                            const row = document.createElement('tr');
                            row.className = 'timezone-row border-b border-slate-800/50';
                            row.innerHTML = `
                                <td class="py-2.5 px-3">
                                    <div class="font-medium text-slate-300 text-xs md:text-sm label-glow">${tz.label}</div>
                                </td>
                                <td class="py-2.5 px-3 text-slate-500 text-xs md:text-sm">${tz.date}</td>
                                <td class="py-2.5 px-3">
                                    <span class="font-digital text-sky-400 text-xs md:text-sm time-glow">${tz.time}</span>
                                </td>
                                <td class="py-2.5 px-3">
                                    ${tz.is_daylight 
                                        ? '<span class="px-2 py-0.5 bg-amber-500/10 text-amber-300 rounded text-[10px] border border-amber-500/20">Daylight</span>' 
                                        : '<span class="px-2 py-0.5 bg-slate-500/10 text-slate-500 rounded text-[10px] border border-slate-500/20">Standard</span>'
                                    }
                                </td>
                            `;
                            tbody.appendChild(row);
                        });
                    }
                })
                .catch(() => {
                    document.getElementById('timezone-table').innerHTML = '<tr><td colspan="4" class="text-center py-6 text-slate-600 text-xs">Failed to load timezone data</td></tr>';
                });
        }

        function init() {
            updateClock();
            updateBusinessHours();
            updateHolidayStatus();
            updateHealthCheck();
            updateTimezoneComparison();

            setInterval(updateClock, 1000);
            setInterval(updateBusinessHours, 5000);
            setInterval(updateHolidayStatus, 30000);
            setInterval(updateHealthCheck, 10000);
            setInterval(updateTimezoneComparison, 1000);
        }

        document.addEventListener('DOMContentLoaded', init);
    </script>
</body>
</html>
