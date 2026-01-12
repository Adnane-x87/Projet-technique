<?php

namespace App\Services;

use App\Models\Skills;

class SkillsService {

  public function getAllSkills(){
    return Skills::all();
  }
}
