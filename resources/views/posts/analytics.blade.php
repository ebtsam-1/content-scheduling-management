<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Analytics Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .dashboard-container {
            padding: 2rem 1rem;
        }
        .dashboard-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin-bottom: 1rem;
        }
        .stat-icon.primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stat-icon.success { background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%); }
        .stat-icon.warning { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .stat-icon.info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .stat-icon.danger { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
        }
        .stat-label {
            color: #7f8c8d;
            font-weight: 600;
            margin: 0;
        }
        .chart-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .platform-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .platform-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        .platform-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
            margin-right: 1rem;
        }
        .progress-modern {
            height: 8px;
            border-radius: 10px;
            background: #e9ecef;
            overflow: hidden;
        }
        .progress-bar-modern {
            height: 100%;
            border-radius: 10px;
            transition: width 0.6s ease;
        }
        .table-modern {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        .table-modern thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .table-modern th {
            border: none;
            padding: 1rem;
            font-weight: 600;
        }
        .table-modern td {
            border: none;
            padding: 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        .badge-modern {
            padding: 0.5rem 0.75rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
        }
        .filter-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 1rem 0.5rem;
            }
            .stat-number {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid dashboard-container">
        <!-- Header -->
        <div class="dashboard-header text-center">
            <h1 class="display-4 fw-bold mb-2">
                <i class="fas fa-chart-line me-3"></i>
                Post Analytics Dashboard
            </h1>
            <p class="lead text-muted mb-0">Comprehensive insights into your social media performance</p>
        </div>

        <!-- Filters -->
        <div class="filter-card">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Date Range</label>
                    <select class="form-select" id="dateRange">
                        <option value="7">Last 7 days</option>
                        <option value="30" selected>Last 30 days</option>
                        <option value="90">Last 90 days</option>
                        <option value="365">Last year</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Platform</label>
                    <select class="form-select" id="platformFilter">
                        <option value="all">All Platforms</option>
                        @if(isset($platforms))
                            @foreach($platforms as $platform)
                                <option value="{{ $platform->id }}">{{ $platform->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Status</label>
                    <select class="form-select" id="statusFilter">
                        <option value="all">All Status</option>
                        <option value="published">Published</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary w-100" onclick="refreshData()">
                        <i class="fas fa-sync-alt me-2"></i>Refresh Data
                    </button>
                </div>
            </div>
        </div>

        <!-- Key Statistics -->
        <div class="row">
            <div class="col-md-2 col-sm-6">
                <div class="stat-card text-center">
                    <div class="stat-icon primary mx-auto">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3 class="stat-number" id="totalPosts">{{ $analytics['total_posts'] ?? 0 }}</h3>
                    <p class="stat-label">Total Posts</p>
                </div>
            </div>
            <div class="col-md-2 col-sm-6">
                <div class="stat-card text-center">
                    <div class="stat-icon success mx-auto">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3 class="stat-number" id="publishedPosts">{{ $analytics['published_posts'] ?? 0 }}</h3>
                    <p class="stat-label">Published</p>
                </div>
            </div>
            <div class="col-md-2 col-sm-6">
                <div class="stat-card text-center">
                    <div class="stat-icon warning mx-auto">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="stat-number" id="scheduledPosts">{{ $analytics['scheduled_posts'] ?? 0 }}</h3>
                    <p class="stat-label">Scheduled</p>
                </div>
            </div>
            <div class="col-md-2 col-sm-6">
                <div class="stat-card text-center">
                    <div class="stat-icon info mx-auto">
                        <i class="fas fa-edit"></i>
                    </div>
                    <h3 class="stat-number" id="draftPosts">{{ $analytics['draft_posts'] ?? 0 }}</h3>
                    <p class="stat-label">Drafts</p>
                </div>
            </div>
            <div class="col-md-2 col-sm-6">
                <div class="stat-card text-center">
                    <div class="stat-icon danger mx-auto">
                        <i class="fas fa-percentage"></i>
                    </div>
                    <h3 class="stat-number" id="successRate">{{ $analytics['success_rate'] ?? 0 }}%</h3>
                    <p class="stat-label">Success Rate</p>
                </div>
            </div>
            <div class="col-md-2 col-sm-6">
                <div class="stat-card text-center">
                    <div class="stat-icon primary mx-auto">
                        <i class="fas fa-share-alt"></i>
                    </div>
                    <h3 class="stat-number" id="activePlatforms">{{ $analytics['active_platforms'] ?? 0 }}</h3>
                    <p class="stat-label">Platforms</p>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <div class="col-lg-8">
                <div class="chart-container">
                    <h4 class="fw-bold mb-4">
                        <i class="fas fa-chart-bar me-2"></i>
                        Posts Distribution by Platform
                    </h4>
                    <canvas id="platformChart" height="100"></canvas>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="chart-container">
                    <h4 class="fw-bold mb-4">
                        <i class="fas fa-chart-pie me-2"></i>
                        Status Distribution
                    </h4>
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Platform Performance -->
        <div class="row">
            <div class="col-lg-6">
                <div class="chart-container">
                    <h4 class="fw-bold mb-4">
                        <i class="fas fa-trophy me-2"></i>
                        Platform Performance
                    </h4>
                    <div id="platformPerformance">
                        @if(isset($platform_stats))
                            @foreach($platform_stats as $stat)
                                <div class="platform-card">
                                    <div class="d-flex align-items-center">
                                        <div class="platform-icon" style="background: {{ $stat['color'] ?? '#667eea' }}">
                                            <i class="{{ $stat['icon'] ?? 'fas fa-share-alt' }}"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-1">{{ $stat['name'] }}</h6>
                                            <small class="text-muted">{{ $stat['total_posts'] }} posts total</small>
                                        </div>
                                        <div class="text-end">
                                            <h5 class="fw-bold mb-0">{{ $stat['success_rate'] }}%</h5>
                                            <small class="text-muted">Success Rate</small>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <div class="progress-modern">
                                            <div class="progress-bar-modern" 
                                                 style="width: {{ $stat['success_rate'] }}%; background: {{ $stat['color'] ?? '#667eea' }}"></div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-4 text-center">
                                            <small class="fw-bold text-success">{{ $stat['published'] }}</small>
                                            <br><small class="text-muted">Published</small>
                                        </div>
                                        <div class="col-4 text-center">
                                            <small class="fw-bold text-warning">{{ $stat['scheduled'] }}</small>
                                            <br><small class="text-muted">Scheduled</small>
                                        </div>
                                        <div class="col-4 text-center">
                                            <small class="fw-bold text-info">{{ $stat['draft'] }}</small>
                                            <br><small class="text-muted">Drafts</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="chart-container">
                    <h4 class="fw-bold mb-4">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Publishing Timeline
                    </h4>
                    <canvas id="timelineChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Posts Table -->
        <div class="chart-container">
            <h4 class="fw-bold mb-4">
                <i class="fas fa-list me-2"></i>
                Recent Posts Overview
            </h4>
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th>Post Title</th>
                            <th>Platforms</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Scheduled</th>
                            <th>Success Rate</th>
                        </tr>
                    </thead>
                    <tbody id="recentPostsTable">
                        @if(isset($recentPosts))
                            @foreach($recentPosts as $post)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ Str::limit($post->title, 40) }}</div>
                                        <small class="text-muted">{{ Str::limit($post->content, 60) }}</small>
                                    </td>
                                    <td>
                                        @foreach($post->platforms as $platform)
                                            <span class="badge bg-primary me-1">{{ $platform->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($post->status == 'published')
                                            <span class="badge badge-modern bg-success">Published</span>
                                        @elseif($post->status == 'scheduled')
                                            <span class="badge badge-modern bg-warning">Scheduled</span>
                                        @else
                                            <span class="badge badge-modern bg-secondary">Draft</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $post->created_at->format('M d, Y') }}</small>
                                        <br><small class="text-muted">{{ $post->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        @if($post->scheduled_at)
                                            <small>{{ $post->scheduled_at->format('M d, Y') }}</small>
                                            <br><small class="text-muted">{{ $post->scheduled_at->format('H:i') }}</small>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="progress-modern" style="width: 60px;">
                                            <div class="progress-bar-modern bg-success" 
                                                 style="width: {{ $post->success_rate ?? 100 }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ $post->success_rate ?? 100 }}%</small>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Chart configurations
        const chartColors = {
            primary: '#667eea',
            success: '#56ab2f',
            warning: '#f093fb',
            info: '#4facfe',
            danger: '#fa709a'
        };

        // Platform Distribution Chart
        const platformCtx = document.getElementById('platformChart').getContext('2d');
        const platformChart = new Chart(platformCtx, {
            type: 'bar',
            data: {
                labels: [
                    @if(isset($platform_stats))
                        @foreach($platform_stats as $stat)
                            '{{ $stat["name"] }}',
                        @endforeach
                    @endif
                ],
                datasets: [{
                    label: 'Total Posts',
                    data: [
                        @if(isset($platform_stats))
                            @foreach($platform_stats as $stat)
                                {{ $stat['total_posts'] }},
                            @endforeach
                        @endif
                    ],
                    backgroundColor: [
                        @if(isset($platform_stats))
                            @foreach($platform_stats as $stat)
                                '{{ $stat["color"] ?? "#667eea" }}',
                            @endforeach
                        @endif
                    ],
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Status Distribution Pie Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Published', 'Scheduled', 'Draft'],
                datasets: [{
                    data: [
                        {{ $analytics['published_posts'] ?? 0 }},
                        {{ $analytics['scheduled_posts'] ?? 0 }},
                        {{ $analytics['draft_posts'] ?? 0 }}
                    ],
                    backgroundColor: [
                        chartColors.success,
                        chartColors.warning,
                        chartColors.info
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Timeline Chart (dummy data - replace with actual data)
        const timelineCtx = document.getElementById('timelineChart').getContext('2d');
        const timelineChart = new Chart(timelineCtx, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [{
                    label: 'Posts Published',
                    data: [12, 19, 15, 25],
                    borderColor: chartColors.primary,
                    backgroundColor: chartColors.primary + '20',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Refresh data function
        function refreshData() {
            const dateRange = document.getElementById('dateRange').value;
            const platformFilter = document.getElementById('platformFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            
            // Show loading state
            const refreshBtn = document.querySelector('button[onclick="refreshData()"]');
            const originalText = refreshBtn.innerHTML;
            refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
            refreshBtn.disabled = true;
            
            // Simulate API call
            setTimeout(() => {
                refreshBtn.innerHTML = originalText;
                refreshBtn.disabled = false;
                // Here you would typically make an AJAX call to refresh the data
                console.log('Refreshing data with filters:', { dateRange, platformFilter, statusFilter });
            }, 2000);
        }

        // Auto-refresh every 5 minutes
        setInterval(refreshData, 300000);
    </script>
</body>
</html>