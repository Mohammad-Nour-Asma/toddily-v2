<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildCourse extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
protected $with = ['course'];

    public function course(){
        return $this->belongsTo(Course::class , 'course_id');
    }

    public function child(){
        return $this->belongsTo(Child::class , 'child_id');
    }
    public function status(){
        return $this->hasMany(ChildCourseStatus::class , 'child_course_id');
    }
}
