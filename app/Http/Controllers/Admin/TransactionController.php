<?php

namespace App\Http\Controllers\Admin;

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
        if ($status && !in_array($status, ['completed', 'declined', 'other'])) {
            $status = null;
        }
        $transactions = Transaction::with(['membership'])
            ->where(function ($query) use ($status) {
                if ($status === 'completed') {
                    $query->where('status', $status);
                } else if ($status === 'declined') {
                    $query->whereIn('status', ['declined', 'cancelled', 'canceled']);
                } else if ($status === 'other') {
                    $query->whereNotIn('status', ['completed', 'declined', 'cancelled', 'canceled']);
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.transactions', [
            'status' => $status,
            'transactions' => $transactions,
        ]);
    }
}
