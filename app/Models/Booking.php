<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'table_id',
        'customer_name',
        'start_time',
        'duration',
        'total_price',
        'status'
    ];

    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}