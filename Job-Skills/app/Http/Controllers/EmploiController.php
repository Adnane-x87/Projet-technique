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
        
        if ($request->ajax()) {
            return view('emplois._job_card', compact('emplois'))->render();
        }

        return view('emplois.index', compact('emplois', 'skills'));
    }

    public function show(Emploi $emploi)
    {
        return view('emplois.show', compact('emploi'));
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
