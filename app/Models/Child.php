<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $with = ['course'];

    public function report(){
        return $this->hasMany(Report::class, 'child_id');
    }

    public function images(){
        return $this->hasMany(ChildImage::class, 'child_id');
    }

    public function status(){
        return $this->hasMany(ChildStatus::class, 'child_id');
    }

    public function classRoom(){
        return $this->belongsTo(ClassRoom::class, 'classRoom_id');
    }

    public function course(){
        return $this->hasMany(ChildCourse::class, 'child_id');
    }
}
