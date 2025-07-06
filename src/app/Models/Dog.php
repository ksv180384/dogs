<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
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

    public function getImagesDir()
    {
        return 'dogs/' . $this->id . '/images/';
    }

    public function getImageLinkAttribute(): string
    {
        if(empty($this->image)){
            return '';
        }

        $fileName = basename($this->image);
        return Storage::url($this->getImagesDir() . $fileName);
    }
}
