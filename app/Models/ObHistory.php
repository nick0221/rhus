<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'lmp',
        'aog',
        'edc',
        'individual_id',
        'treatments_id'
    ];






}
