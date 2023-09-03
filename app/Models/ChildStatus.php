<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildStatus extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $with = ['childSubstatus'];


    public function status(){
        return $this->belongsTo(Status::class,'status_id');
    }

        public function childSubstatus(){
        return $this->hasMany(ChildSubstatus::class,'childStatus_id');
    }
}
