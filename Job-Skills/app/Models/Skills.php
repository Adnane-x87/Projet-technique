<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skills extends Model
{
    
    protected $fillable = ['name'];

    function emplois (){

        return $this->belongsToMany(Emploi::class, 'emploi_skill', 'skill_id', 'emploi_id');
    }
}
