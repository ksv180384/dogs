<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DogImage extends Model
{
    protected $fillable = [
        'dog_id',
        'image',
    ];

    public function dog()
    {
        return $this->belongsTo(Dog::class);
    }

    public function getImagesDir()
    {
        return 'dogs/' . $this->dog_id . '/images/';
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
