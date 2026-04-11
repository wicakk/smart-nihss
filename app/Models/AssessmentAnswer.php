<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentAnswer extends Model
{
    protected $fillable = ['assessment_id', 'question_id', 'option_id', 'score', 'notes'];
    protected $casts = ['score' => 'integer'];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function question()
    {
        return $this->belongsTo(FormQuestion::class, 'question_id');
    }

    public function option()
    {
        return $this->belongsTo(FormOption::class, 'option_id');
    }
}
