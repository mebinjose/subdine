<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStats extends Model
{
    use HasFactory;

    protected $table = 'order_stats';

    protected $fillable = ['dish_id', 'date', 'count'];
}
