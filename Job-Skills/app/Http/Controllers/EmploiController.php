<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Emploi;
use App\Models\Skills;
use App\Models\User;


    class EmploiController extends Controller
{
    public function index(Request $request)
    {
        $query = Emploi::with('skills')->latest();

        if ($request->filled('skill')) {
            $query->whereHas('skills', function ($q) use ($request) {
                $q->where('skills.id', $request->skill);
            });
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        return view('emplois.index', [
            'emplois' => $query->paginate(5),
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

        $data['user_id'] = auth()->id() ?? User::first()->id;

        $emploi = Emploi::create($data);

        if ($request->skills) {
            $emploi->skills()->attach($request->skills);
        }

        return response()->json([
            'success' => true,
            'message' => 'Emploi ajouté avec succès',
            'emploi' => $emploi
        ]);
    }

    public function search(Request $request)
    {
        $query = Emploi::with('skills')->latest();

        if ($request->filled('skill')) {
            $query->whereHas('skills', fn ($q) =>
                $q->where('skills.id', $request->skill)
            );
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('company', 'like', '%' . $request->search . '%');
            });
        }

        return view('emplois._table_body', [
            'emplois' => $query->get(),
            'skills' => Skills::all() 
        ]);
    }
}


