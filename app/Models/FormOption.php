<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormOption extends Model
{
    protected $fillable = [
        'question_id', 'option_text', 'score',
        'description', 'order_number', 'is_active',
    ];
    protected $casts = ['score' => 'integer', 'is_active' => 'boolean'];

    public function question()
    {
        return $this->belongsTo(FormQuestion::class, 'question_id');
    }
}
