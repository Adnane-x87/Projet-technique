<?php

namespace App\Services;

use App\Models\Emploi;

class EmploiService {

  public function getAllJobs(){
    return Emploi::with(['skills','user'])->latest()->get();
  }

  public function getJobId($id){
    return Emploi::with(['skills','user'])->findOrFail($id);
  }

  public function createJob(array $data){
    $job = Emploi::create([
      'title' => $data['title'],
      'description' => $data['description'],
      'company' => $data['company'],
      'image' => $data['image'] ?? null,
      'user_id' => auth()->id() ?? 1
    ]);

    if (isset($data['skills'])){
      $job->skills()->attach($data['skills']);
    }

    return $job;
  }

  public function updateJob($id , array $data){
    $job = Emploi::findOrFail($id);

    $job->update([
      'title' => $data['title'],
      'description' => $data['description'],
      'company' => $data['company'],
      'image' => $data['image'] ?? $job->image
    ]);

    if (isset($data['skills'])){
      $job->skills()->sync($data['skills']);
    }

    return $job;
  }

  public function deleteJob($id){
    $job = Emploi::findOrFail($id);
    $job->skills()->detach();
    $job->delete();
  }

  public function searchJobs($search){
    return Emploi::with(['skills','user'])
      ->where('title','LIKE',"%{$search}%")
      ->get();
  }

  public function filterBySkill($skillId){
    return Emploi::with(['skills','user'])
      ->whereHas('skills', function($query) use ($skillId){
        $query->where('skills.id', $skillId);
      })
      ->get();
  }
}