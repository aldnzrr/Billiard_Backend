<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [

        'name',
        'date',
        'time',
        'room_type',
        'table_qty',
        'playing_hour',
        'total_price',
        'status',
        'receipt_image'

    ];
}