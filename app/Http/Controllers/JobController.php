<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = JobApplication::where('user_id', $user->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('company', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%");
            });
        }

        $jobs = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Jobs/Index', [
            'jobs' => $jobs,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'status' => 'required|in:wishlist,applied,interview,offer,rejected',
            'applied_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'job_url' => 'nullable|url|max:500',
            'salary_min' => 'nullable|integer|min:0',
            'salary_max' => 'nullable|integer|min:0',
            'location' => 'nullable|string|max:255',
        ]);

        $validated['user_id'] = Auth::id();

        JobApplication::create($validated);

        return back()->with('success', 'Lamaran berhasil ditambahkan!');
    }

    public function update(Request $request, JobApplication $job)
    {
        abort_if($job->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'company' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'status' => 'required|in:wishlist,applied,interview,offer,rejected',
            'applied_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'job_url' => 'nullable|url|max:500',
            'salary_min' => 'nullable|integer|min:0',
            'salary_max' => 'nullable|integer|min:0',
            'location' => 'nullable|string|max:255',
        ]);

        $job->update($validated);

        return back()->with('success', 'Lamaran berhasil diperbarui!');
    }

    public function destroy(JobApplication $job)
    {
        abort_if($job->user_id !== Auth::id(), 403);

        $job->delete();

        return back()->with('success', 'Lamaran berhasil dihapus!');
    }
}