<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private string $menu = 'Dashboard';

    public function __construct() {
        view()->share('menu', $this->menu);
    }

    public function index() {
        $licenses = License::active()->get();
        $activeUsers = $expiredUsers = 0;
        foreach ($licenses as $license) {
            if ($license['expires_on'] > now()) $activeUsers++;
            else $expiredUsers++;
        }
        $totalIncome = $monthIncome = 0;
        $thisMonth = date('Y-m');
        $thisYear = date('Y-');

        $weekLabels = $weekData = $yearLabels = $yearData = [];
        $week = now()->subDays(6);
        for ($i = 0; $i < 7; $i++) {
            $label = $week->format('jS');
            $weekLabels[] = $label;
            $weekData[$label] = 0;
            $week->addDay();
        }
        ($year = now())->setMonths(1);
        for ($i = 0; $i < 12; $i++) {
            $label = $year->format('M');
            $yearLabels[] = $label;
            $yearData[$label] = 0;
            $year->addMonth();
        }

        $transactions = Transaction::completed()->get();
        foreach ($transactions as $transaction) {
            if (stripos($transaction['started_at'], $thisMonth) !== false) {
                $monthIncome += $transaction['amount'];
            }
            $totalIncome += $transaction['amount'];
            if (now()->subDays(6)->format('Y-m-d 00:00:00') <= $transaction['started_at']
                && $transaction['started_at'] <= now()->format('Y-m-d 23:59:59')
            ) {
                $label = date('jS', strtotime($transaction['started_at']));
                $weekData[$label] += $transaction['amount'];
            }
            if (stripos($transaction['started_at'], $thisYear)) {
                $label = date('M', strtotime($transaction['started_at']));
                $yearData[$label] += $transaction['amount'];
            }
        }
        return view('admin.dashboard', [
            'activeUsers' => $activeUsers,
            'expiredUsers' => $expiredUsers,
            'totalIncome' => $totalIncome,
            'monthIncome' => $monthIncome,
            'weekLabels' => $weekLabels,
            'weekData' => $weekData,
            'yearLabels' => $yearLabels,
            'yearData' => $yearData,
        ]);
    }
}
