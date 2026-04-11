<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormQuestion extends Model
{
    protected $fillable = [
        'section_id', 'question_text', 'instruction',
        'order_number', 'is_required', 'is_active',
    ];
    protected $casts = ['is_required' => 'boolean', 'is_active' => 'boolean'];

    public function section()
    {
        return $this->belongsTo(FormSection::class, 'section_id');
    }

    public function options()
    {
        return $this->hasMany(FormOption::class, 'question_id')
                    ->where('is_active', true)
                    ->orderBy('order_number');
    }

    public function allOptions()
    {
        return $this->hasMany(FormOption::class, 'question_id')->orderBy('order_number');
    }

    public function answers()
    {
        return $this->hasMany(AssessmentAnswer::class, 'question_id');
    }

    public function getMaxScoreAttribute(): int
    {
        return $this->options()->max('score') ?? 0;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order_number');
    }
}
