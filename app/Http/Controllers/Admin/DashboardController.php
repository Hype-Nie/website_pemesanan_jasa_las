<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomOrder;
use App\Models\Payment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show admin dashboard with stats.
     */
    public function index()
    {
        $totalOrders = CustomOrder::count();
        $pendingOrders = CustomOrder::where('status', 'pending')->count();
        $inProductionOrders = CustomOrder::where('status', 'in_production')->count();
        $completedOrders = CustomOrder::where('status', 'completed')->count();

        $revenue = Payment::where('status', 'verified')->sum('amount');

        $recentOrders = CustomOrder::with('user')
            ->latest()
            ->take(5)
            ->get();

        $pendingPayments = Payment::where('status', 'pending')->count();

        return view('admin.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'inProductionOrders',
            'completedOrders',
            'revenue',
            'recentOrders',
            'pendingPayments'
        ));
    }
}
