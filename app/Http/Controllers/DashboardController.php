<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_patients'    => Patient::count(),
            'active_patients'   => Patient::where('status', 'active')->count(),
            'total_assessments' => Assessment::where('is_complete', true)->count(),
            'today_assessments' => Assessment::whereDate('assessed_at', today())->count(),
            'avg_score'         => round(Assessment::where('is_complete', true)->avg('total_score') ?? 0, 1),
        ];

        // Recent assessments
        $recentAssessments = Assessment::with('patient')
            ->where('is_complete', true)
            ->orderByDesc('assessed_at')
            ->limit(5)
            ->get();

        // Severity distribution
        $severityDist = Assessment::where('is_complete', true)
            ->select('severity', DB::raw('count(*) as count'))
            ->groupBy('severity')
            ->pluck('count', 'severity')
            ->toArray();

        // Monthly assessments (last 6 months)
        $monthlyTrend = Assessment::where('is_complete', true)
            ->where('assessed_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw('DATE_FORMAT(assessed_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count'),
                DB::raw('ROUND(AVG(total_score), 1) as avg_score')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('dashboard', compact('stats', 'recentAssessments', 'severityDist', 'monthlyTrend'));
    }
}
