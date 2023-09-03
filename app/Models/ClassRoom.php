<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $with = ['teacher','ageSection'];

    public function children(){
        return $this->hasMany(Child::class,'classRoom_id');
    }

    public function teacher(){
        return $this->belongsTo(User::class,'teacher_id');
    }

    public function ageSection(){
        return $this->belongsTo(AgeSection::class,'age_section_id');
    }
}
