<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    use HasFactory;

    protected $fillable = [
        'individual_id',
        'past_medicalhistories_id',
        'ob_histories_id',
        'travel_histories_id',
        'family_histories_id',
    ];











}
