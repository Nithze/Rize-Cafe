<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Meja;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashierController extends Controller
{

    public function cashier()
    {
        $menu = Menu::where('stock', '>', 0)->get();
        $mejas = Meja::where('status', 'available')->get();
        return view('cashier.cashier', compact('menu', 'mejas'));
    }
    public function transactions()
    {
        return view('cashier.transactions');
    }
    public function showTransactions()
    {
        $mejaAll = Meja::all();
        $user = Auth::user();
        $transactions = Transaction::where('user_id', $user->id)->get();
        return view('cashier.transactions', compact('transactions', 'mejaAll'));
    }
    public function show($id)
    {
        $transaction = Transaction::with('items.menu', 'user')->findOrFail($id);

        $response = [
            'id' => $transaction->id,
            'customer' => $transaction->customer,
            'table' => 'Table - ' . $transaction->table_id,
            'items' => [],
            'total_amount' => $transaction->total,
            'pay' => $transaction->pay,
            'return' => $transaction->return_amount,
            'cashier' => $transaction->user->name,
            'date' => $transaction->transaction_time,
        ];

        foreach ($transaction->items as $item) {
            $response['items'][] = [
                'menu' => $item->menu->name,
                'price' => $item->price,
                'quantity' => $item->quantity,
            ];
        }

        return response()->json($response);
    }


    public function changeTableStatus($id)
    {
        $meja = Meja::findOrFail($id);
        $meja->status = 'available';
        $meja->save();
        return redirect()->back()->with('status', 'Status meja berhasil diubah.');

    }




}
