<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentAnswer;
use App\Models\FormSection;
use App\Models\Patient;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function create(Patient $patient)
    {
        $sections = FormSection::active()
            ->with(['questions.options'])
            ->get();

        return view('assessments.create', compact('patient', 'sections'));
    }

    public function store(Request $request, Patient $patient)
    {
        $request->validate([
            'assessed_at'    => 'required|date',
            'clinical_notes' => 'nullable|string|max:2000',
            'answers'        => 'required|array',
            'answers.*'      => 'nullable|exists:form_options,id',
        ]);

        // Build answer map: question_id => option
        $sections = FormSection::active()->with(['questions.options'])->get();
        $allQuestions = $sections->flatMap(fn($s) => $s->questions);

        $answers     = $request->input('answers', []);
        $totalScore  = 0;
        $answerRows  = [];

        foreach ($allQuestions as $question) {
            $optionId = $answers[$question->id] ?? null;
            $score    = 0;

            if ($optionId) {
                $option = $question->options->firstWhere('id', $optionId);
                if ($option) {
                    $score = $option->score;
                    $totalScore += $score;
                }
            }

            $answerRows[] = [
                'question_id' => $question->id,
                'option_id'   => $optionId ?: null,
                'score'       => $score,
            ];
        }

        $severity = Assessment::calculateSeverity($totalScore);

        $assessment = Assessment::create([
            'patient_id'     => $patient->id,
            'assessor_id'    => auth()->id(),
            'total_score'    => $totalScore,
            'severity'       => $severity,
            'assessed_at'    => $request->assessed_at,
            'clinical_notes' => $request->clinical_notes,
            'is_complete'    => true,
        ]);

        foreach ($answerRows as $row) {
            $assessment->answers()->create($row);
        }

        return redirect()
            ->route('assessments.show', $assessment)
            ->with('success', "Penilaian berhasil disimpan. Total Skor: {$totalScore} ({$assessment->severity_label})");
    }

    public function show(Assessment $assessment)
    {
        $assessment->load([
            'patient',
            'answers.question.section',
            'answers.option',
        ]);

        $sections = FormSection::active()
            ->with(['questions' => function ($q) use ($assessment) {
                $q->with(['options', 'answers' => function ($a) use ($assessment) {
                    $a->where('assessment_id', $assessment->id);
                }]);
            }])
            ->get();

        return view('assessments.show', compact('assessment', 'sections'));
    }

    public function destroy(Assessment $assessment)
    {
        $patient = $assessment->patient;
        $assessment->delete();

        return redirect()
            ->route('patients.show', $patient)
            ->with('success', 'Pemeriksaan berhasil dihapus.');
    }

    /**
     * API endpoint: calculate score in real-time (AJAX)
     */
    public function calculateScore(Request $request)
    {
        $answers   = $request->input('answers', []);
        $total     = 0;
        $breakdown = [];

        foreach ($answers as $questionId => $optionId) {
            if (!$optionId) continue;

            $option = \App\Models\FormOption::with('question.section')->find($optionId);
            if ($option) {
                $total += $option->score;
                $breakdown[] = [
                    'section'  => $option->question->section->name ?? '',
                    'question' => $option->question->question_text,
                    'option'   => $option->option_text,
                    'score'    => $option->score,
                ];
            }
        }

        return response()->json([
            'total'     => $total,
            'severity'  => Assessment::calculateSeverity($total),
            'label'     => Assessment::severityLabel(Assessment::calculateSeverity($total)),
            'color'     => Assessment::severityColor(Assessment::calculateSeverity($total)),
            'breakdown' => $breakdown,
        ]);
    }
}
