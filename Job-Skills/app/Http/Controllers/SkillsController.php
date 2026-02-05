<?php

namespace App\Http\Controllers;

use App\Models\Skills;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SkillsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('access-admin');
        $skills = Skills::all();
        return view('skills.index', compact('skills'));
    }


    public function store(Request $request)
    {
        Gate::authorize('access-admin');
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
        Gate::authorize('access-admin');
        $skill->delete();
        return redirect()->back()->with('success', 'Skill deleted successfully.');
    }
}
