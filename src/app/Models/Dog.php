<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dog extends Model
{
    protected $fillable = [
      'name',
      'image',
      'description',
      'birthdate',
      'status',
    ];
}
