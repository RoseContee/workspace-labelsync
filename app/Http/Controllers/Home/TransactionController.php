<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    private string $menu = 'Transactions';

    public function __construct() {
        view()->share('menu', $this->menu);
    }

    public function index(Request $request) {
        $status = strtolower($request['status']);
        if ($status && !in_array($status, ['pending', 'completed', 'canceled', 'expired'])) {
            $status = null;
        }
        $transactions = Transaction::with(['membership'])
            ->where(function ($query) use ($status) {
                if ($status) {
                    if (in_array($status, ['canceled', 'expired'])) {
                        $query->whereIn('status', ['canceled', 'expired']);
                    } else {
                        $query->where('status', $status);
                    }
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();
        return view('home.transactions', [
            'status' => $status,
            'transactions' => $transactions,
        ]);
    }
}
