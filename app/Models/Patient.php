<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'medical_record_number',
        'name',
        'gender',
        'birth_date',
        'phone',
        'address',
        'diagnosis',
        'admission_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'birth_date'     => 'date',
        'admission_date' => 'date',
    ];

    // ─── Relationships ───────────────────────────────────────────
    public function assessments()
    {
        return $this->hasMany(Assessment::class)->orderByDesc('assessed_at');
    }

    public function latestAssessment()
    {
        return $this->hasOne(Assessment::class)->latestOfMany('assessed_at');
    }

    // ─── Accessors ────────────────────────────────────────────────
    public function getAgeAttribute(): int
    {
        return Carbon::parse($this->birth_date)->age;
    }

    public function getGenderLabelAttribute(): string
    {
        return $this->gender === 'male' ? 'Laki-laki' : 'Perempuan';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'active'    => 'Aktif',
            'discharged'=> 'Pulang',
            'deceased'  => 'Meninggal',
            default     => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'active'    => 'green',
            'discharged'=> 'blue',
            'deceased'  => 'red',
            default     => 'gray',
        };
    }

    // ─── Statistics ───────────────────────────────────────────────
    public function getStatistics(): array
    {
        $scores = $this->assessments()
            ->where('is_complete', true)
            ->pluck('total_score');

        if ($scores->isEmpty()) {
            return [
                'count'   => 0,
                'avg'     => null,
                'min'     => null,
                'max'     => null,
                'latest'  => null,
                'trend'   => null,
            ];
        }

        $count  = $scores->count();
        $latest = $this->assessments()
            ->where('is_complete', true)
            ->orderByDesc('assessed_at')
            ->first();

        // Trend: compare last two assessments
        $trend = null;
        if ($count >= 2) {
            $last   = $scores->first();     // most recent
            $before = $scores->get(1);      // second most recent
            if ($last < $before)      $trend = 'improving';
            elseif ($last > $before)  $trend = 'worsening';
            else                      $trend = 'stable';
        }

        return [
            'count'   => $count,
            'avg'     => round($scores->avg(), 1),
            'min'     => $scores->min(),
            'max'     => $scores->max(),
            'latest'  => $latest?->total_score,
            'trend'   => $trend,
        ];
    }

    // ─── Scopes ───────────────────────────────────────────────────
    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('medical_record_number', 'like', "%{$term}%")
              ->orWhere('diagnosis', 'like', "%{$term}%");
        });
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
