<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    // tabel terkait 
    protected $table = 'menu';

    // (mass assignable)
    protected $fillable = [
        'name',
        'price',
        'category',
        'stock',
        'total_sold',
    ];

    // cast
    protected $casts = [
        'price' => 'integer',
        'total_sold' => 'integer',
    ];
}
