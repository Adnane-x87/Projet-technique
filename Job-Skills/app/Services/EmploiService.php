<?php

namespace App\Services;

use App\Models\Emploi;
use App\Models\User;

class EmploiService {

  public function getJobs(array $filters = [], int $perPage = 0)
  {
      $query = Emploi::with(['skills', 'user'])->latest();

      if (isset($filters['search']) && $filters['search'] !== '') {
          $query->where(function($q) use ($filters) {
              $q->where('title', 'LIKE', "%{$filters['search']}%")
                ->orWhere('company', 'LIKE', "%{$filters['search']}%");
          });
      }

      if (isset($filters['skill']) && $filters['skill'] !== '') {
          $query->whereHas('skills', function($q) use ($filters) {
              $q->where('skills.id', $filters['skill']);
          });
      }

      return $perPage > 0 ? $query->paginate($perPage) : $query->get();
  }

  public function getJobId($id){
    return Emploi::with(['skills','user'])->findOrFail($id);
  }

  public function createJob(array $data){
    $imagePath = null;
    if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
        $imagePath = $data['image']->store('jobs', 'public');
    }

    $job = Emploi::create([
      'title' => $data['title'],
      'description' => $data['description'],
      'company' => $data['company'],
      'image' => $imagePath,
      'user_id' => auth()->id() ?? User::first()?->id ?? 1
    ]);

    if (isset($data['skills'])){
      $job->skills()->attach($data['skills']);
    }

    return $job;
  }

  public function updateJob($id , array $data){
    $job = Emploi::findOrFail($id);
    
    $imagePath = $job->image;
    if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
        $imagePath = $data['image']->store('jobs', 'public');
    }

    $job->update([
      'title' => $data['title'],
      'description' => $data['description'],
      'company' => $data['company'],
      'image' => $imagePath
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


}