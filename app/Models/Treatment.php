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
        'category_id',
        'isDependent',
        'dependentPhilhealthNum',
        'birthday',
        'phMemberName',
        'vitalSign',
        'diagnosis',
        'medication',
        'chiefComplaints',
        'attendingProvider',
        'vitalSign',
    ];


    protected $casts = [
        'vitalSign' => 'json'
    ];





    public function individual(): BelongsTo
    {
        return $this->belongsTo(Individual::class);
    }


    public function pastMedicalhistory(): HasMany
    {
        return $this->hasMany(PastMedicalhistory::class, 'treatments_id');
    }


    public function ob_histories(): HasMany
    {
        return $this->hasMany(ObHistory::class, 'treatments_id');
    }


    public function travel_histories(): HasMany
    {
        return $this->hasMany(TravelHistory::class, 'treatments_id');
    }


    public function family_histories(): HasMany
    {
        return $this->hasMany(FamilyHistory::class, 'treatments_id');
    }


    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }


    public function followupCheckup(): BelongsTo
    {
        return $this->belongsTo(FollowupCheckup::class);
    }








}
