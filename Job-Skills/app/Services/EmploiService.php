<?php

namespace App\Services;

use App\Models\Emploi;

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


}