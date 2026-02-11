<?php

namespace App\Http\Controllers;

use App\Models\Emploi;
use App\Services\EmploiService;
use App\Services\SkillsService;
use App\Http\Requests\StoreEmploiRequest;
use App\Http\Requests\UpdateEmploiRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
        $formattedJobs = $this->emploiService->formatJobsForApi($emplois);
        
        if ($request->ajax()) {
            return view('emplois._job_card', ['emplois' => $emplois])->render();
        }

        return view('emplois.index', [
            'emplois' => $emplois,
            'skills' => $skills,
            'initialJobs' => $formattedJobs
        ]);
    }

    public function manage(Request $request)
    {
        Gate::authorize('access-admin');

        $emplois = $this->emploiService->searchAndFilter(
            $request->get('search'),
            $request->get('skill'),
            5
        );
        $skills = $this->skillsService->getAllSkills();
        $formattedJobs = $this->emploiService->formatJobsForApi($emplois);
        
        // Prepare pagination data
        $formattedJobsArray = $formattedJobs->toArray();
        $paginationData = [
            'data' => $formattedJobsArray,
            'current_page' => $emplois->currentPage(),
            'last_page' => $emplois->lastPage(),
            'total' => $emplois->total(),
        ];
        
        return view('admin.dashboard', [
            'emplois' => $emplois, 
            'skills' => $skills,
            'initialJobs' => $paginationData
        ]);
    }

    public function create()
    {
        Gate::authorize('manage-jobs');
        $skills = $this->skillsService->getAllSkills();
        return view('emplois.create', compact('skills'));
    }

    public function store(StoreEmploiRequest $request)
    {
        // Validation and Authorization handled by StoreEmploiRequest

        $validated = $request->validated();

        $emploi = $this->emploiService->createJob(
            $validated + ['user_id' => auth()->id()], // Ensure user_id is passed
            $request->file('image')
        );

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Job created successfully.', 'emploi' => $emploi], 201);
        }

        return redirect()->route('admin.dashboard')->with('success', 'Job created successfully.');
    }

    public function show(Emploi $emploi)
    {
        return view('emplois.show', compact('emploi'));
    }

    public function edit(Emploi $emploi)
    {
        Gate::authorize('update-job', $emploi);
        $skills = $this->skillsService->getAllSkills();
        return view('emplois.edit', compact('emploi', 'skills'));
    }

    public function update(UpdateEmploiRequest $request, Emploi $emploi)
    {
        // Validation and Authorization handled by UpdateEmploiRequest

        $validated = $request->validated();

        $this->emploiService->updateJob(
            $emploi->id,
            $validated,
            $request->file('image')
        );

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Job updated successfully.', 'emploi' => $emploi->fresh()], 200);
        }

        return redirect()->route('admin.dashboard')->with('success', 'Job updated successfully.');
    }

    public function destroy(Emploi $emploi)
    {
        Gate::authorize('delete-job', $emploi);
        $this->emploiService->deleteJob($emploi->id);
        return redirect()->route('admin.dashboard')->with('success', 'Job deleted successfully.');
    }

    public function search(Request $request)
    {
        $emplois = $this->emploiService->searchAndFilter(
            $request->get('search'),
            $request->get('skill'),
            5 // Ensure pagination in search too
        );

        $formattedJobs = $this->emploiService->formatJobsForApi($emplois);

        return response()->json([
            'data' => $formattedJobs,
            'current_page' => $emplois->currentPage(),
            'last_page' => $emplois->lastPage(),
            'total' => $emplois->total(),
        ]);
    }
}
