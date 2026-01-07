<?php

namespace App\Http\Controllers;

use App\Models\Emploi;
use App\Models\Skills;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmploiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Emploi::with('skills', 'user')->latest();

        // Text search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhereHas('skills', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by skill
        if ($request->filled('skill')) {
            $skillId = $request->get('skill');
            $query->whereHas('skills', function($q) use ($skillId) {
                $q->where('skills.id', $skillId);
            });
        }

        $emplois = $query->get();
        $skills = Skills::orderBy('name')->get();
        
        return view('emplois.index', compact('emplois', 'skills'));
    }

    /**
     * Display admin dashboard.
     */
    public function manage(Request $request)
    {
        $query = Emploi::with('skills')->latest();

        // Text search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhereHas('skills', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by skill
        if ($request->filled('skill')) {
            $skillId = $request->get('skill');
            $query->whereHas('skills', function($q) use ($skillId) {
                $q->where('skills.id', $skillId);
            });
        }

        $emplois = $query->get();
        $skills = Skills::all();
        return view('admin.dashboard', compact('emplois', 'skills'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $skills = Skills::all();
        return view('emplois.create', compact('skills'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'company' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'skills' => 'array|exists:skills,id'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('emplois', 'public');
        }

        // Assign current user or fallback to admin (ID 1)
        $validated['user_id'] = Auth::id() ?? 1;

        $emploi = Emploi::create($validated);

        if ($request->has('skills')) {
            $emploi->skills()->attach($request->skills);
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Job created successfully.', 'emploi' => $emploi], 201);
        }

        return redirect()->route('emplois.index')->with('success', 'Job created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Emploi $emploi)
    {
        return view('emplois.show', compact('emploi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Emploi $emploi)
    {
        // Add authorization check here if needed
        $skills = Skills::all();
        return view('emplois.edit', compact('emploi', 'skills'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Emploi $emploi)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'company' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'skills' => 'array|exists:skills,id'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($emploi->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($emploi->image);
            }
            $validated['image'] = $request->file('image')->store('emplois', 'public');
        }

        $emploi->update($validated);

        if ($request->has('skills')) {
            $emploi->skills()->sync($request->skills);
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Job updated successfully.', 'emploi' => $emploi], 200);
        }

        return redirect()->route('emplois.index')->with('success', 'Job updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Emploi $emploi)
    {
        $emploi->delete();
        return redirect()->route('emplois.index')->with('success', 'Job deleted successfully.');
    }

    /**
     * AJAX search for jobs
     */
    public function search(Request $request)
    {
        $query = Emploi::with('skills')->latest();

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhereHas('skills', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('skill')) {
            $skillId = $request->get('skill');
            $query->whereHas('skills', function($q) use ($skillId) {
                $q->where('skills.id', $skillId);
            });
        }

        $emplois = $query->get()->map(function($emploi) {
            return [
                'id' => $emploi->id,
                'title' => $emploi->title,
                'company' => $emploi->company,
                'description' => $emploi->description,
                'image' => $emploi->image,
                'skills' => $emploi->skills->map(fn($s) => ['id' => $s->id, 'name' => $s->name]),
                'date' => $emploi->created_at->format('d/m/Y'),
                'url' => route('emplois.show', $emploi)
            ];
        });

        return response()->json([
            'count' => $emplois->count(),
            'emplois' => $emplois
        ]);
    }
}
