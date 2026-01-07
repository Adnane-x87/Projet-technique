<?php

namespace App\Http\Controllers;

use App\Models\Skills;
use Illuminate\Http\Request;

class SkillsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $skills = Skills::all();
        return view('skills.index', compact('skills'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:skills,name|max:255',
        ]);

        Skills::create($validated);

        return redirect()->back()->with('success', 'Skill added successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Skills $skill) // Note: route binding might require confirming the model name
    {
        $skill->delete();
        return redirect()->back()->with('success', 'Skill deleted successfully.');
    }
}
