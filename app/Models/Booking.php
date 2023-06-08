<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'booking';

    public function scopeWhereCreatedDateBetween($query, $startDate, $endDate){
            return $query->where('date_created','>=',$startDate)->where('date_created','<=',$endDate);
    }
}
