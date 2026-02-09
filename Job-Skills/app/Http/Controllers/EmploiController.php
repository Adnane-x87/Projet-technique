<?php

namespace App\Http\Controllers;

use App\Models\Emploi;
use App\Services\EmploiService;
use App\Services\SkillsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EmploiController extends Controller
{
    protected $skillsService;

    public function __construct(EmploiService $emploiService, SkillsService $skillsService)
    {
        $this->emploiService = $emploiService;
        $this->skillsService = $skillsService;
    }

    public function index(Request $request)
    {
        $emplois = $this->emploiService->searchAndFilter(
            $request->get('search'),
            $request->get('skill'),
            5
        );
        $skills = $this->skillsService->getAllSkills();
        
        if ($request->ajax()) {
            return view('emplois._job_card', compact('emplois'))->render();
        }

        return view('emplois.index', compact('emplois', 'skills'));
    }

    public function manage(Request $request)
    {
        // Only admin can access dashboard
        Gate::authorize('access-admin');

        $emplois = $this->emploiService->searchAndFilter(
            $request->get('search'),
            $request->get('skill'),
            5
        );
        $skills = $this->skillsService->getAllSkills();
        
        return view('admin.dashboard', compact('emplois', 'skills'));
    }

    public function create()
    {
        // Only admin can create jobs (as per PROMPT.md 'manage-jobs' gate)
        Gate::authorize('manage-jobs');

        $skills = $this->skillsService->getAllSkills();
        return view('emplois.create', compact('skills'));
    }

    public function store(Request $request)
    {
        // Only admin can store jobs
        Gate::authorize('manage-jobs');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'company' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'skills' => 'array|exists:skills,id'
        ]);

        $emploi = $this->emploiService->createJob(
            $validated,
            $request->file('image')
        );

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Job created successfully.', 'emploi' => $emploi], 201);
        }

        return redirect()->route('emplois.index')->with('success', 'Job created successfully.');
    }

    public function show(Emploi $emploi)
    {
        return view('emplois.show', compact('emploi'));
    }

    public function edit(Emploi $emploi)
    {
        // Admin OR Owner
        Gate::authorize('update-job', $emploi);

        $skills = $this->skillsService->getAllSkills();
        return view('emplois.edit', compact('emploi', 'skills'));
    }

    public function update(Request $request, Emploi $emploi)
    {
        // Admin OR Owner
        Gate::authorize('update-job', $emploi);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'company' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'skills' => 'array|exists:skills,id'
        ]);

        $this->emploiService->updateJob(
            $emploi->id,
            $validated,
            $request->file('image')
        );

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Job updated successfully.', 'emploi' => $emploi->fresh()], 200);
        }

        return redirect()->route('emplois.index')->with('success', 'Job updated successfully.');
    }

    public function destroy(Emploi $emploi)
    {
        // Admin OR Owner
        Gate::authorize('delete-job', $emploi);

        $this->emploiService->deleteJob($emploi->id);
        return redirect()->route('admin.dashboard')->with('success', 'Job deleted successfully.');
    }

    public function search(Request $request)
    {
        $emplois = $this->emploiService->searchAndFilter(
            $request->get('search'),
            $request->get('skill')
        );

        $formattedJobs = $this->emploiService->formatJobsForApi($emplois);

        return response()->json([
            'count' => $formattedJobs->count(),
            'emplois' => $formattedJobs
        ]);
    }
}
