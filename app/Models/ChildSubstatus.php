<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildSubstatus extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $with = ['substatus'];

    public function substatus(){
        return $this->belongsTo(Substatus::class,'subStatus_id');
    }
}
