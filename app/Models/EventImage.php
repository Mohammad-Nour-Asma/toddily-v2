<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventImage extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function setSrcAttribute($src)
    {
        $newImageName = uniqid().''.'image'.'.'.$src->extension();
        $src->move(public_path('images'),$newImageName);
        return $this->attributes['src'] = '/images/'. $newImageName;
    }
}
