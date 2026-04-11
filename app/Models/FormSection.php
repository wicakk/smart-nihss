<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormSection extends Model
{
    protected $fillable = ['code', 'name', 'description', 'order_number', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function questions()
    {
        return $this->hasMany(FormQuestion::class, 'section_id')
                    ->where('is_active', true)
                    ->orderBy('order_number');
    }

    public function allQuestions()
    {
        return $this->hasMany(FormQuestion::class, 'section_id')->orderBy('order_number');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order_number');
    }
}
