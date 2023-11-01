<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
    ];








    protected static function booted(): void
    {
        static::created(function ($ind) {
            $ind->fullname = Str::ucfirst($ind->firstname .' '.$ind->middlename.' '.$ind->lastname.' '.$ind->extname);
            $ind->save();
        });

    }









}
