<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Emploi;
use App\Models\User;
use App\Services\EmploiService;
use App\Services\SkillsService;


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
        $emplois = $this->emploiService->getJobs($request->all(), 5);

        return view('emplois.index', [
            'emplois' => $emplois,
            'skills'  => $this->skillsService->getAllSkills(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'company' => 'required',
            'description' => 'required',
            'skills' => 'array',
            'image' => 'nullable|image',
        ]);

        $emploi = $this->emploiService->createJob($data);

        return response()->json([
            'success' => true,
            'message' => 'Emploi ajouté avec succès',
            'emploi' => $emploi
        ]);
    }

    public function search(Request $request)
    {
        $emplois = $this->emploiService->getJobs($request->all());

        return view('emplois._table_body', [
            'emplois' => $emplois,
            'skills' => $this->skillsService->getAllSkills() 
        ]);
    }
}

