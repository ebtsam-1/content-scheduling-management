<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontendPostController extends Controller
{
    public function create()
    {
        $platforms = Platform::all(); // or your preferred query
        return view('posts.create', compact('platforms'));
    }

    public function viewDashboard(Request $request)
    {
        $status = $request->get('status', 'all');
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        // Create date range for the month
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        // Query posts
        $query = Post::whereBetween('scheduled_time', [$startDate, $endDate]);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $posts = $query->get();

        // Group posts by date
        $postsByDate = $posts->groupBy(function($post) {
            return Carbon::parse($post->scheduled_time)->format('Y-m-d');
        });

        // Calendar data
        $currentDate = Carbon::create($year, $month, 1);
        $daysInMonth = $currentDate->daysInMonth;
        $firstDayOfWeek = $currentDate->dayOfWeek;

        // Get available statuses
        $statuses = ['all', 'scheduled', 'published', 'draft'];

        return view('dashboard', compact(
            'postsByDate',
            'statuses',
            'status',
            'month',
            'year',
            'currentDate',
            'daysInMonth',
            'firstDayOfWeek'
        ));

    }

    public function analytics()
    {
        $platforms = Platform::all();
        $recentPosts = Auth::user()->posts()->with('platforms')->latest()->take(10)->get();
        $analytics = [
            'total_posts' => $recentPosts->count(),
            'published_posts' => Auth::user()->posts()->where('status', 'published')->count(),
            'scheduled_posts' => Auth::user()->posts()->where('status', 'scheduled')->count(),
            'draft_posts' => Auth::user()->posts()->where('status', 'draft')->count(),
            'active_platforms' => Platform::count()
        ];

        return view('posts.analytics', compact('platforms', 'recentPosts', 'analytics'));
    }
}
