<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FollowupCheckup extends Model
{
    use HasFactory;

    protected $fillable = [
        'followupDate',
        'individual_id',
        'treatment_id',
        'remarksNote',
        'followupStatus',
    ];




    public function individual(): BelongsTo
    {
        return $this->belongsTo(Individual::class);
    }



    public function treatment(): BelongsTo
    {
        return $this->belongsTo(Treatment::class);
    }





}
