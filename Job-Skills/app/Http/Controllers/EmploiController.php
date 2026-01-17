<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Emploi;
use App\Models\Skills;
use App\Models\User;
use App\Services\EmploiService;


class EmploiController extends Controller
{
    protected $emploiService;

    public function __construct(EmploiService $emploiService)
    {
        $this->emploiService = $emploiService;
    }

    public function index(Request $request)
    {
        $emplois = $this->emploiService->getJobs($request->all(), 5);

        return view('emplois.index', [
            'emplois' => $emplois,
            'skills'  => Skills::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'description' => 'required|string',
            'skills' => 'array',
            'skills.*' => 'exists:skills,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('emplois', 'public');
        }

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
            'skills' => Skills::all() 
        ]);
    }
}


