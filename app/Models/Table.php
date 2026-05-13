<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    // Ini biar database-nya boleh diisi data
    protected $fillable = ['table_number', 'type', 'price_per_hour'];
}
