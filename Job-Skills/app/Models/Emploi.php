<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emploi extends Model
{
    protected $fillable = ['title' , 'company','image', 'description' , 'user_id'];


    function user(){
        return $this->belongsTo(User::class);
    }

    function skills(){

        return $this->belongsToMany(Skills::class);
    }
}
