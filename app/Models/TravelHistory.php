<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'dateoftravel',
        'place',
        'daysofstay',
        'individual_id',
        'treatments_id'
    ];



}
