<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Assessment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::withCount('assessments')
            ->with('latestAssessment');

        if ($search = $request->get('search')) {
            $query->search($search);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $patients = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        $stats = [
            'total'      => Patient::count(),
            'active'     => Patient::where('status', 'active')->count(),
            'discharged' => Patient::where('status', 'discharged')->count(),
            'assessments_today' => Assessment::whereDate('assessed_at', today())->count(),
        ];

        return view('patients.index', compact('patients', 'stats'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'medical_record_number' => 'required|string|max:50|unique:patients',
            'name'                  => 'required|string|max:255',
            'gender'                => 'required|in:male,female',
            'birth_date'            => 'required|date|before:today',
            'phone'                 => 'nullable|string|max:20',
            'address'               => 'nullable|string|max:500',
            'diagnosis'             => 'nullable|string|max:255',
            'admission_date'        => 'nullable|date',
            'status'                => 'required|in:active,discharged,deceased',
            'notes'                 => 'nullable|string|max:1000',
        ]);

        $patient = Patient::create($validated);

        return redirect()
            ->route('patients.show', $patient)
            ->with('success', "Pasien {$patient->name} berhasil ditambahkan.");
    }

    public function show(Patient $patient)
    {
        $patient->load(['assessments' => function ($q) {
            $q->where('is_complete', true)->orderByDesc('assessed_at')->limit(10);
        }]);

        $statistics = $patient->getStatistics();

        // Chart data: last 10 complete assessments (oldest first)
        $chartData = $patient->assessments()
            ->where('is_complete', true)
            ->orderBy('assessed_at')
            ->limit(10)
            ->get()
            ->map(fn($a) => [
                'date'     => $a->assessed_at->format('d/m/Y H:i'),
                'score'    => $a->total_score,
                'severity' => $a->severity_label,
                'color'    => $a->severity_color,
            ]);

        return view('patients.show', compact('patient', 'statistics', 'chartData'));
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'medical_record_number' => ['required','string','max:50', Rule::unique('patients')->ignore($patient->id)],
            'name'                  => 'required|string|max:255',
            'gender'                => 'required|in:male,female',
            'birth_date'            => 'required|date|before:today',
            'phone'                 => 'nullable|string|max:20',
            'address'               => 'nullable|string|max:500',
            'diagnosis'             => 'nullable|string|max:255',
            'admission_date'        => 'nullable|date',
            'status'                => 'required|in:active,discharged,deceased',
            'notes'                 => 'nullable|string|max:1000',
        ]);

        $patient->update($validated);

        return redirect()
            ->route('patients.show', $patient)
            ->with('success', "Data pasien {$patient->name} berhasil diperbarui.");
    }

    public function destroy(Patient $patient)
    {
        $name = $patient->name;
        $patient->delete();

        return redirect()
            ->route('patients.index')
            ->with('success', "Pasien {$name} berhasil dihapus.");
    }
}
