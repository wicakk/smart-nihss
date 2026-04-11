<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assessment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'assessor_id',
        'total_score',
        'severity',
        'assessed_at',
        'clinical_notes',
        'is_complete',
    ];

    protected $casts = [
        'assessed_at' => 'datetime',
        'is_complete' => 'boolean',
    ];

    // ─── Severity constants ───────────────────────────────────────
    const SEVERITY_NORMAL     = 'normal';
    const SEVERITY_MILD       = 'mild';
    const SEVERITY_MODERATE   = 'moderate';
    const SEVERITY_SEVERE     = 'severe';
    const SEVERITY_VERY_SEVERE= 'very_severe';

    public static function calculateSeverity(int $score): string
    {
        return match (true) {
            $score === 0         => self::SEVERITY_NORMAL,
            $score <= 4          => self::SEVERITY_MILD,
            $score <= 15         => self::SEVERITY_MODERATE,
            $score <= 20         => self::SEVERITY_SEVERE,
            default              => self::SEVERITY_VERY_SEVERE,
        };
    }

    public static function severityLabel(string $severity): string
    {
        return match ($severity) {
            self::SEVERITY_NORMAL      => 'Normal',
            self::SEVERITY_MILD        => 'Ringan',
            self::SEVERITY_MODERATE    => 'Sedang',
            self::SEVERITY_SEVERE      => 'Berat',
            self::SEVERITY_VERY_SEVERE => 'Sangat Berat',
            default => $severity,
        };
    }

    public static function severityColor(string $severity): string
    {
        return match ($severity) {
            self::SEVERITY_NORMAL      => '#10b981',
            self::SEVERITY_MILD        => '#3b82f6',
            self::SEVERITY_MODERATE    => '#f59e0b',
            self::SEVERITY_SEVERE      => '#ef4444',
            self::SEVERITY_VERY_SEVERE => '#7c3aed',
            default => '#6b7280',
        };
    }

    public static function severityBadgeClass(string $severity): string
    {
        return match ($severity) {
            self::SEVERITY_NORMAL      => 'badge-normal',
            self::SEVERITY_MILD        => 'badge-mild',
            self::SEVERITY_MODERATE    => 'badge-moderate',
            self::SEVERITY_SEVERE      => 'badge-severe',
            self::SEVERITY_VERY_SEVERE => 'badge-very-severe',
            default => 'badge-gray',
        };
    }

    // ─── Relationships ───────────────────────────────────────────
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function assessor()
    {
        return $this->belongsTo(\App\Models\User::class, 'assessor_id');
    }

    public function answers()
    {
        return $this->hasMany(AssessmentAnswer::class);
    }

    // ─── Accessors ────────────────────────────────────────────────
    public function getSeverityLabelAttribute(): string
    {
        return self::severityLabel($this->severity);
    }

    public function getSeverityColorAttribute(): string
    {
        return self::severityColor($this->severity);
    }

    public function getSeverityBadgeClassAttribute(): string
    {
        return self::severityBadgeClass($this->severity);
    }
}
