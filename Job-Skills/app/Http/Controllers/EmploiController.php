<?php

namespace App\Http\Controllers;

use App\Models\Emploi;
use App\Services\EmploiService;
use App\Services\SkillsService;
use Illuminate\Http\Request;

class EmploiController extends Controller
{
    protected $emploiService;
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
        
        return view('emplois.index', compact('emplois', 'skills'));
    }

    public function manage(Request $request)
    {
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
        $skills = $this->skillsService->getAllSkills();
        return view('emplois.create', compact('skills'));
    }

    public function store(Request $request)
    {
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
        $skills = $this->skillsService->getAllSkills();
        return view('emplois.edit', compact('emploi', 'skills'));
    }

    public function update(Request $request, Emploi $emploi)
    {
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
        $this->emploiService->deleteJob($emploi->id);
        return redirect()->route('emplois.index')->with('success', 'Job deleted successfully.');
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
