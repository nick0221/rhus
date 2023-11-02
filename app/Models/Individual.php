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
        'category_id',

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





    protected static function booted(): void
    {
        static::created(function ($ind) {
            $ind->fullname = Str::ucfirst($ind->firstname .' '.$ind->middlename.' '.$ind->lastname.' '.$ind->extname);
            $ind->save();
        });

    }









}
