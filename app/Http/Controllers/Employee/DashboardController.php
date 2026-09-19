<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\CustomOrder;

class DashboardController extends Controller
{
    /**
     * Display the employee workshop dashboard.
     */
    public function index()
    {
        $baseQuery = CustomOrder::whereIn('status', ['confirmed', 'in_production', 'completed']);

        $readyOrdersCount = (clone $baseQuery)
            ->where('status', 'confirmed')
            ->where(function ($q) {
                $q->whereHas('payments', function ($p) {
                    $p->where('status', 'verified');
                });
            })
            ->count();

        $inProductionCount = (clone $baseQuery)->where('status', 'in_production')->count();
        $completedCount = (clone $baseQuery)->where('status', 'completed')->count();
        $totalActiveCount = $readyOrdersCount + $inProductionCount;

        // Average progress of active production orders
        $avgProgress = (clone $baseQuery)->where('status', 'in_production')->avg('progress_percentage') ?? 0;
        $avgProgress = round($avgProgress);

        // Recent active tasks (in production or ready to start)
        $activeTasks = CustomOrder::with(['user', 'catalogProduct'])
            ->whereIn('status', ['confirmed', 'in_production'])
            ->orderBy('updated_at', 'desc')
            ->take(6)
            ->get();

        // Recently completed tasks
        $recentlyCompleted = CustomOrder::with('user')
            ->where('status', 'completed')
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('employee.dashboard', compact(
            'readyOrdersCount',
            'inProductionCount',
            'completedCount',
            'totalActiveCount',
            'avgProgress',
            'activeTasks',
            'recentlyCompleted'
        ));
    }
}
