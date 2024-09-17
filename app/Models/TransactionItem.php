<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    use HasFactory;
    protected $table = 'transaction_items';
    protected $fillable = [
        'transaction_id',
        'menu_id',
        'quantity',
        'price',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    // Function to get transaction details
    public function getTransactionDetails($id)
    {
        $transaction = Transaction::with(['items.menu'])->findOrFail($id);
        return view('cashier.transaction_details', compact('transaction'));
    }
}
