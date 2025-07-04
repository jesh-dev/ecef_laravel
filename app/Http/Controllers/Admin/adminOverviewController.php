<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; // You forgot this import
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payment;

class AdminOverviewController extends Controller
{
   public function adminOverview(Request $request)
{
    $filter = $request->query('filter', 'today'); // default to today

    $now = now();
    switch ($filter) {
        case 'week':
            $startDate = $now->startOfWeek();
            break;
        case 'month':
            $startDate = $now->startOfMonth();
            break;
        default:
            $startDate = $now->startOfDay();
            break;
    }

    $totalDonations = Payment::sum('amount'); // Total from all users
    $donors = Payment::distinct('user_id')->count('user_id'); // Unique donors
    $totalUsers = User::count(); // All registered users
    $totalRegularUsers = User::where('role', 'user')->count(); // Only regular users

    $recentActivity = Payment::where('created_at', '>=', $startDate)->sum('amount'); // Donations within filter

    $latestDonations = Payment::with('user')
        ->where('created_at', '>=', $startDate)
        ->latest()
        ->take(10)
        ->get()
        ->map(function ($payment) {
            return [
                'name' => $payment->user->firstname . ' ' . $payment->user->lastname,
                'amount' => $payment->amount,
                'date' => $payment->created_at->toDayDateTimeString(),
            ];
        });

    return response()->json([
        'totalAmount' => $totalDonations,
        'totalDonors' => $donors,
        'totalUsers' => $totalUsers,
        'totalRegularUsers' => $totalRegularUsers,
        'recentActivity' => $recentActivity,
        'latest' => $latestDonations,
    ]);
}

}
