<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;

class Dog extends Model
{
    protected $fillable = [
      'parent_id',
      'name',
      'image',
      'description',
      'birthdate',
      'status',
    ];

    protected $casts = [
        'comment' => PurifyHtmlOnGet::class,
    ];

    public function parent()
    {
        return $this->belongsTo(Dog::class, 'parent_id');
    }

    public function puppy()
    {
        return $this->hasMany(Dog::class, 'parent_id');
    }
}
