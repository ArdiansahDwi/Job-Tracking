<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Count per status
        $statuses = ['wishlist', 'applied', 'interview', 'offer', 'rejected'];
        $statusCounts = [];
        foreach ($statuses as $status) {
            $statusCounts[$status] = JobApplication::where('user_id', $user->id)
                ->where('status', $status)
                ->count();
        }

        // Monthly trend (last 6 months)
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = JobApplication::where('user_id', $user->id)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $monthlyData[] = [
                'month' => $date->format('M Y'),
                'count' => $count,
            ];
        }

        // Recent 5 applications
        $recentJobs = JobApplication::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get(['id', 'company', 'position', 'status', 'applied_date', 'created_at']);

        return Inertia::render('Dashboard', [
            'statusCounts' => $statusCounts,
            'totalJobs' => array_sum($statusCounts),
            'monthlyData' => $monthlyData,
            'recentJobs' => $recentJobs,
        ]);
    }
}