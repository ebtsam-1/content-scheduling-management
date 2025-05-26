<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts Calendar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #333;
            margin-bottom: 20px;
        }

        .controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .navigation {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .nav-btn {
            background: #007bff;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }

        .nav-btn:hover {
            background: #0056b3;
        }

        .month-year {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .filters {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filters label {
            font-weight: bold;
        }

        .filters select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .calendar {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .calendar th {
            background: #007bff;
            color: white;
            padding: 12px 8px;
            text-align: center;
            font-weight: bold;
        }

        .calendar td {
            border: 1px solid #ddd;
            height: 100px;
            width: 14.28%;
            vertical-align: top;
            padding: 5px;
            position: relative;
        }

        .calendar td:hover {
            background-color: #f8f9fa;
        }

        .day-number {
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        .today {
            background-color: #e3f2fd;
        }

        .today .day-number {
            color: #007bff;
        }

        .post-item {
            background: #28a745;
            color: white;
            padding: 2px 6px;
            margin: 1px 0;
            border-radius: 3px;
            font-size: 11px;
            cursor: pointer;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .post-item.scheduled {
            background: #ffc107;
            color: #000;
        }

        .post-item.published {
            background: #28a745;
        }

        .post-item.draft {
            background: #6c757d;
        }

        .post-item:hover {
            opacity: 0.8;
        }

        .empty-cell {
            background: #f8f9fa;
            color: #6c757d;
        }

        .legend {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
        }

        .legend-color {
            width: 20px;
            height: 15px;
            border-radius: 3px;
        }

        .legend-color.scheduled {
            background: #ffc107;
        }

        .legend-color.published {
            background: #28a745;
        }

        .legend-color.draft {
            background: #6c757d;
        }

        @media (max-width: 768px) {
            .controls {
                flex-direction: column;
                align-items: stretch;
            }

            .navigation {
                justify-content: center;
            }

            .calendar td {
                height: 80px;
                font-size: 12px;
            }

            .post-item {
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Posts Calendar</h1>
        </div>

        <div class="controls">
            <div class="navigation">
                <a href="?month={{ $currentDate->copy()->subMonth()->month }}&year={{ $currentDate->copy()->subMonth()->year }}&status={{ $status }}" class="nav-btn">
                    ← Previous
                </a>
                <div class="month-year">
                    {{ $currentDate->format('F Y') }}
                </div>
                <a href="?month={{ $currentDate->copy()->addMonth()->month }}&year={{ $currentDate->copy()->addMonth()->year }}&status={{ $status }}" class="nav-btn">
                    Next →
                </a>
            </div>

            <div class="filters">
                <label for="status">Filter by Status:</label>
                <select id="status" onchange="filterByStatus()">
                    @foreach($statuses as $statusOption)
                        <option value="{{ $statusOption }}" {{ $status == $statusOption ? 'selected' : '' }}>
                            {{ ucfirst($statusOption) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <table class="calendar">
            <thead>
                <tr>
                    <th>Sunday</th>
                    <th>Monday</th>
                    <th>Tuesday</th>
                    <th>Wednesday</th>
                    <th>Thursday</th>
                    <th>Friday</th>
                    <th>Saturday</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $currentDay = 1;
                    $today = now()->format('Y-m-d');
                @endphp

                @for($week = 0; $week < 6; $week++)
                    @if($currentDay <= $daysInMonth)
                        <tr>
                            @for($day = 0; $day < 7; $day++)
                                @php
                                    $cellDate = null;
                                    $showDay = false;
                                    
                                    if($week == 0 && $day >= $firstDayOfWeek) {
                                        $cellDate = $currentDate->copy()->day($currentDay);
                                        $showDay = true;
                                        $currentDay++;
                                    } elseif($week > 0 && $currentDay <= $daysInMonth) {
                                        $cellDate = $currentDate->copy()->day($currentDay);
                                        $showDay = true;
                                        $currentDay++;
                                    }
                                    
                                    $dateStr = $cellDate ? $cellDate->format('Y-m-d') : '';
                                    $isToday = $dateStr === $today;
                                    $dayPosts = $cellDate && isset($postsByDate[$dateStr]) ? $postsByDate[$dateStr] : collect();
                                @endphp

                                <td class="{{ $showDay ? '' : 'empty-cell' }} {{ $isToday ? 'today' : '' }}">
                                    @if($showDay)
                                        <div class="day-number">{{ $cellDate->day }}</div>
                                        
                                        @foreach($dayPosts as $post)
                                            <div class="post-item {{ $post->status }}" 
                                                 title="{{ $post->title }} - {{ \Carbon\Carbon::parse($post->scheduled_time)->format('H:i') }}">
                                                {{ Str::limit($post->title, 15) }}
                                            </div>
                                        @endforeach
                                    @endif
                                </td>
                            @endfor
                        </tr>
                    @endif
                @endfor
            </tbody>
        </table>

        <div class="legend">
            <div class="legend-item">
                <div class="legend-color scheduled"></div>
                <span>Scheduled</span>
            </div>
            <div class="legend-item">
                <div class="legend-color published"></div>
                <span>Published</span>
            </div>
            <div class="legend-item">
                <div class="legend-color draft"></div>
                <span>Draft</span>
            </div>
        </div>
    </div>

    <script>
        function filterByStatus() {
            const status = document.getElementById('status').value;
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('status', status);
            window.location.search = urlParams.toString();
        }
    </script>
</body>
</html>