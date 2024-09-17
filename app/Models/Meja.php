<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{
    use HasFactory;

    protected $table = 'meja';
    protected $fillable = ['number', 'status'];
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
