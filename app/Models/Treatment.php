<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Treatment extends Model
{
    use HasFactory;
    protected $fillable = [
        'individual_id',
        'past_medicalhistories_id',
        'ob_histories_id',
        'travel_histories_id',
        'family_histories_id',
        'category_id',
        'isDependent',
        'dependentPhilhealthNum',
        'birthday',
        'phMemberName',
    ];



    public function individual(): BelongsTo
    {
        return $this->belongsTo(Individual::class);
    }


    public function past_medicalhistories(): HasMany
    {
        return $this->hasMany(PastMedicalhistory::class, 'treatments_id');
    }


    public function ob_histories(): BelongsTo
    {
        return $this->belongsTo(ObHistory::class);
    }


    public function travel_histories(): BelongsTo
    {
        return $this->belongsTo(TravelHistory::class);
    }


    public function family_histories(): BelongsTo
    {
        return $this->belongsTo(FamilyHistory::class);
    }


    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }






}
