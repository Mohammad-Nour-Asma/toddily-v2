<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgeSection extends Model

{
    protected $guarded = [];
    use HasFactory;

    public function classRoom () {
        return $this->hasMany(ClassRoom::class , 'age_section_id');
    }

    public function status () {
        return $this->hasMany(Status::class,'ageSection_id');
    }
}
