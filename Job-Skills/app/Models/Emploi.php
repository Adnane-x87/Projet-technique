<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emploi extends Model
{
    protected $fillable = ['tille' , 'company','image', 'description' , 'user_id'];


    function user(){
        return $this->belongsTO(User::class);
    }

    function skills(){

        return $this->belongsToMany(Skills::class);
    }
}
