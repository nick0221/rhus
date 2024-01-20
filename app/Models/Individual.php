<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Individual extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'extname',
        'fullname',
        'address',
        'civilstatus',
        'birthdate',
        'gender',
        'height',
        'weight',
        'philhealthnum',
        'isMember',
        'image',
        //'category_id',
        'mobile',
        'educAttainment',
        'guardianName',
        'placeofbirth',
        'occupation',
        'guardianContact',

    ];



    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }



    public function past_medical_histories(): HasMany
    {
        return $this->hasMany(PastMedicalhistory::class);
    }


    public function family_histories(): HasMany
    {
        return $this->hasMany(FamilyHistory::class);
    }


    public function travel_histories(): HasMany
    {
        return $this->hasMany(TravelHistory::class);
    }



    public function obstetrics_histories(): HasMany
    {
        return $this->hasMany(ObHistory::class);
    }


    public function treatmentRecords(): HasMany
    {
        return $this->hasMany(Treatment::class);
    }


    public function followupCheckup(): HasMany
    {
        return $this->hasMany(FollowupCheckup::class);
    }



    protected static function booted(): void
    {
        static::created(function ($ind) {
            $ind->fullname = (ucfirst($ind->firstname) .' '.ucfirst($ind->middlename).' '.ucfirst($ind->lastname).' '.ucfirst($ind->extname));
            $ind->save();
        });

    }









}
