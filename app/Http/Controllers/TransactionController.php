<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Meja;
use App\Models\TransactionItem;
use App\Models\Menu;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer' => 'required|string|max:255',
            'table' => 'required|integer',
            'total' => 'required|integer',
            'qty' => 'required|integer',
            'pay' => 'required|integer',
            'return_amount' => 'required|integer',
            'orderList' => 'required|string',
        ]);

        $orderList = json_decode($validated['orderList'], true);

        // Check if all quantities are available
        foreach ($orderList as $item) {
            $menu = Menu::find($item['id']);
            if ($menu->stock < $item['quantity']) {
                return redirect()->back()->withErrors(['orderList' => 'Quantity for item ' . $menu->name . ' exceeds available stock.'])->withInput();
            }
        }

        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'table_id' => $validated['table'],
            'transaction_time' => now(),
            'customer' => $validated['customer'],
            'total' => $validated['total'],
            'qty' => $validated['qty'],
            'pay' => $validated['pay'],
            'return_amount' => $validated['return_amount'],
        ]);
        $transactionId = $transaction->id;

        foreach ($orderList as $item) {
            TransactionItem::create([
                'transaction_id' => $transactionId,
                'menu_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            Menu::where('id', $item['id'])->decrement('stock', $item['quantity']);
            Menu::where('id', $item['id'])->increment('total_sold', $item['quantity']);
        }

        Meja::where('id', $validated['table'])->update(['status' => 'occupied']);

        return redirect()->back()->with('success', 'Transaction successfully processed.');
    }
}

// namespace App\Http\Controllers;


// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use App\Models\Transaction;
// use App\Models\Meja;
// use App\Models\TransactionItem;
// use App\Models\Menu;

// class TransactionController extends Controller
// {
//     public function store(Request $request)
//     {
//         $validated = $request->validate([
//             'customer' => 'required|string|max:255',
//             'table' => 'required|integer',
//             'total' => 'required|integer',
//             'qty' => 'required|integer',
//             'pay' => 'required|integer',
//             'return_amount' => 'required|integer',
//             'orderList' => 'required|string',
//         ]);

//         $orderList = json_decode($validated['orderList'], true);

//         $transaction = Transaction::create([
//             'user_id' => Auth::id(),
//             'table_id' => $validated['table'],
//             'transaction_time' => now(),
//             'customer' => $validated['customer'],
//             'total' => $validated['total'],
//             'qty' => $validated['qty'],
//             'pay' => $validated['pay'],
//             'return_amount' => $validated['return_amount'],
//         ]);
//         $transactionId = $transaction->id;
//         foreach ($orderList as $item) {
//             TransactionItem::create([
//                 'transaction_id' => $transactionId,
//                 'menu_id' => $item['id'],
//                 'quantity' => $item['quantity'],
//                 'price' => $item['price'],
//             ]);

//             Menu::where('id', $item['id'])->decrement('stock', $item['quantity']);
//             Menu::where('id', $item['id'])->increment('total_sold', $item['quantity']);
//         }

//         Meja::where('id', $validated['table'])->update(['status' => 'occupied']);

//         return redirect()->back()->with('success', 'Transaction successfully processed.');
//     }

// }
