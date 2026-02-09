<?php

namespace App\Services;

use App\Models\Emploi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class EmploiService {

    public function getAllJobs($perPage = null) {
        $query = Emploi::with(['skills', 'user'])->latest();
        return $perPage ? $query->paginate($perPage) : $query->get();
    }

    public function getJobById($id) {
        return Emploi::with(['skills', 'user'])->findOrFail($id);
    }

    public function searchAndFilter($search = null, $skillId = null, $perPage = null) {
        $query = Emploi::with(['skills', 'user'])->latest();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhereHas('skills', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($skillId) {
            $query->whereHas('skills', function($q) use ($skillId) {
                $q->where('skills.id', $skillId);
            });
        }

        return $perPage ? $query->paginate($perPage) : $query->get();
    }

    public function createJob(array $data, $imageFile = null) {
        if ($imageFile) {
            $data['image'] = $imageFile->store('emplois', 'public');
        }

        $data['user_id'] = auth()->id() ?? 1;

        $job = Emploi::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'company' => $data['company'],
            'image' => $data['image'] ?? null,
            'user_id' => $data['user_id']
        ]);

        if (isset($data['skills'])) {
            $job->skills()->attach($data['skills']);
        }

        return $job;
    }

    public function updateJob($id, array $data, $imageFile = null) {
        $job = Emploi::findOrFail($id);

        if ($imageFile) {
            if ($job->image) {
                Storage::disk('public')->delete($job->image);
            }
            $data['image'] = $imageFile->store('emplois', 'public');
        }

        $job->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'company' => $data['company'],
            'image' => $data['image'] ?? $job->image
        ]);

        if (isset($data['skills'])) {
            $job->skills()->sync($data['skills']);
        }

        return $job;
    }

    public function deleteJob($id) {
        $job = Emploi::findOrFail($id);
        
        if ($job->image) {
            Storage::disk('public')->delete($job->image);
        }
        
        $job->skills()->detach();
        $job->delete();
    }

    public function formatJobsForApi($emplois) {
        return $emplois->map(function($emploi) {
            return [
                'id' => $emploi->id,
                'title' => $emploi->title,
                'company' => $emploi->company,
                'description' => $emploi->description,
                'image' => $emploi->image,
                'skills' => $emploi->skills->map(fn($s) => ['id' => $s->id, 'name' => $s->name]),
                'date' => $emploi->created_at->format('d/m/Y'),
                'url' => route('emplois.show', $emploi),
                'can_update' => Gate::allows('update-job', $emploi),
                'can_delete' => Gate::allows('delete-job', $emploi),
            ];
        });
    }
}