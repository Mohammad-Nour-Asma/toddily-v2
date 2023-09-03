<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    protected $guarded =[];
    protected $with = ['substatus'];

    public function substatus(){
        return $this->hasMany(Substatus::class);
    }
}
