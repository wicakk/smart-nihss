<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\Assessment;
use App\Models\FormQuestion;
use App\Models\FormOption;
use Carbon\Carbon;

class DemoPatientSeeder extends Seeder
{
    public function run(): void
    {
        $patients = [
            ['name'=>'Budi Santoso',    'mrn'=>'RM-2024-001','gender'=>'male',  'birth_date'=>'1960-03-15','diagnosis'=>'Stroke Iskemik','status'=>'active'],
            ['name'=>'Siti Rahayu',     'mrn'=>'RM-2024-002','gender'=>'female','birth_date'=>'1955-07-22','diagnosis'=>'Stroke Hemoragik','status'=>'active'],
            ['name'=>'Ahmad Fauzi',     'mrn'=>'RM-2024-003','gender'=>'male',  'birth_date'=>'1972-11-08','diagnosis'=>'TIA','status'=>'discharged'],
            ['name'=>'Dewi Anggraeni',  'mrn'=>'RM-2024-004','gender'=>'female','birth_date'=>'1968-05-30','diagnosis'=>'Stroke Iskemik','status'=>'active'],
        ];

        // Get all active questions with options
        $questions = FormQuestion::active()->with('options')->get();
        if ($questions->isEmpty()) return;

        foreach ($patients as $pd) {
            $patient = Patient::create([
                'medical_record_number' => $pd['mrn'],
                'name'                  => $pd['name'],
                'gender'                => $pd['gender'],
                'birth_date'            => $pd['birth_date'],
                'diagnosis'             => $pd['diagnosis'],
                'admission_date'        => Carbon::now()->subDays(rand(5,30))->toDateString(),
                'status'                => $pd['status'],
            ]);

            // Create 2-4 assessments per patient
            $numAssessments = rand(2, 4);
            $baseScore = rand(8, 22);

            for ($i = $numAssessments; $i >= 1; $i--) {
                $totalScore = 0;
                $assessedAt = Carbon::now()->subDays($i * rand(3, 7));

                $assessment = Assessment::create([
                    'patient_id'  => $patient->id,
                    'total_score' => 0,
                    'severity'    => 'moderate',
                    'assessed_at' => $assessedAt,
                    'is_complete' => true,
                ]);

                $targetScore = max(0, $baseScore - ($numAssessments - $i) * rand(1, 3));

                foreach ($questions as $question) {
                    $options = $question->options;
                    if ($options->isEmpty()) continue;

                    // Pick a random option biased toward lower scores
                    $option = $options->sortBy('score')->first();
                    $r = rand(0, 100);
                    if ($r > 50 && $options->count() > 1) $option = $options->random();

                    $totalScore += $option->score;

                    $assessment->answers()->create([
                        'question_id' => $question->id,
                        'option_id'   => $option->id,
                        'score'       => $option->score,
                    ]);
                }

                $severity = Assessment::calculateSeverity($totalScore);
                $assessment->update(['total_score' => $totalScore, 'severity' => $severity]);
            }
        }
    }
}
