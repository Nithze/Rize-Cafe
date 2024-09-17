<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'table_id',
        'transaction_time',
        'customer',
        'total',
        'qty',
        'pay',
        'return_amount',
    ];

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function table()
    {
        return $this->belongsTo(Meja::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
