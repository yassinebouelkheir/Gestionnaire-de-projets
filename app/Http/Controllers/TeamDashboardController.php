<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class TeamDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $issuesQuery = Issue::with('projet');
        $usersQuery  = User::query()->whereIn('role', ['admin','developer']);

        if ($user->role !== 'admin') {
            $team = $user->team;
            if (!$team) {
                abort(403, 'Vous n\'êtes pas associé à une équipe.');
            }
            $issuesQuery->whereHas('projet', function ($q) use ($team) {
                $q->where('team_id', $team->id);
            });
            $usersQuery->where('team_id', $team->id);
        }

        $bugs_total = $issuesQuery->count();
        $idsIssues  = $issuesQuery->pluck('id');

        $bugs_ouverts  = Issue::whereIn('id', $idsIssues)->where('state', 'Ouvert')->count();
        $bugs_en_cours = Issue::whereIn('id', $idsIssues)->where('state', 'En cours')->count();
        $bugs_resolus  = Issue::whereIn('id', $idsIssues)->where('state', 'Résolu')->count();
        $bugs_fermes   = Issue::whereIn('id', $idsIssues)->where('state', 'Fermé')->count();

        $resolus = Issue::whereIn('id', $idsIssues)
            ->whereIn('state', ['Résolu', 'Fermé'])
            ->whereNotNull('resolved_at')
            ->get(['created_at', 'resolved_at']);

        $minutes = [];
        foreach ($resolus as $i) {
            $minutes[] = $i->created_at->diffInMinutes($i->resolved_at, true);
        }
        $moyenne_resolution = count($minutes)
            ? round(array_sum($minutes) / count($minutes) / 1440, 2)
            : 0.0;

        $stats_devs = $usersQuery
            ->withCount(['issues as assigned_issues_count' => function ($q) use ($idsIssues) {
                $q->whereIn('issues.id', $idsIssues);
            }])
            ->orderByDesc('assigned_issues_count')
            ->get(['id','name']);

        $stats = [
            'bugs_total'   => $bugs_total,
            'bugs_ouverts' => $bugs_ouverts,
            'bugs_en_cours'=> $bugs_en_cours,
            'bugs_resolus' => $bugs_resolus,
            'bugs_fermes'  => $bugs_fermes,
            'moyenne_resolution' => $moyenne_resolution,
            'stats_devs'   => $stats_devs,
        ];

        return view('team.dashboard', compact('stats'));
    }

    public function exportGlobalCsv(Request $request)
    {
        [$idsIssues, $stats] = $this->computeStats();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="rapport_global.csv"',
        ];

        return response()->stream(function () use ($stats) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, ['Bugs total', $stats['bugs_total']]);
            fputcsv($out, ['Bugs ouverts', $stats['bugs_ouverts']]);
            fputcsv($out, ['Bugs en cours', $stats['bugs_en_cours']]);
            fputcsv($out, ['Bugs résolus', $stats['bugs_resolus']]);
            fputcsv($out, ['Bugs fermés', $stats['bugs_fermes']]);
            fputcsv($out, ['Moyenne de résolution (jours)', number_format($stats['moyenne_resolution'], 2, ',', ' ')]);

            fclose($out);
        }, 200, $headers);
    }

    public function exportDevsCsv(Request $request)
    {
        [$idsIssues, $stats] = $this->computeStats();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="rapport_developpeurs.csv"',
        ];

        return response()->stream(function () use ($stats) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, ['Développeur', 'Bugs assignés']);
            foreach ($stats['stats_devs'] as $dev) {
                fputcsv($out, [
                    $dev->name ?? ('User #'.($dev->id ?? '')),
                    $dev->assigned_issues_count ?? 0
                ]);
            }

            fclose($out);
        }, 200, $headers);
    }

    private function computeStats(): array
    {
        $user = Auth::user();

        $issuesQuery = Issue::with('projet');
        $usersQuery  = User::query()->whereIn('role', ['admin','developer']);

        if ($user->role !== 'admin') {
            $team = $user->team;
            if (!$team) {
                abort(403, 'Vous n\'êtes pas associé à une équipe.');
            }
            $issuesQuery->whereHas('projet', function ($q) use ($team) {
                $q->where('team_id', $team->id);
            });
            $usersQuery->where('team_id', $team->id);
        }

        $bugs_total = $issuesQuery->count();
        $idsIssues  = $issuesQuery->pluck('id');

        $bugs_ouverts  = Issue::whereIn('id', $idsIssues)->where('state', 'Ouvert')->count();
        $bugs_en_cours = Issue::whereIn('id', $idsIssues)->where('state', 'En cours')->count();
        $bugs_resolus  = Issue::whereIn('id', $idsIssues)->where('state', 'Résolu')->count();
        $bugs_fermes   = Issue::whereIn('id', $idsIssues)->where('state', 'Fermé')->count();

        $resolus = Issue::whereIn('id', $idsIssues)
            ->whereIn('state', ['Résolu', 'Fermé'])
            ->whereNotNull('resolved_at')
            ->get(['created_at', 'resolved_at']);

        $minutes = [];
        foreach ($resolus as $i) {
            $minutes[] = $i->created_at->diffInMinutes($i->resolved_at, true);
        }
        $moyenne_resolution = count($minutes)
            ? round(array_sum($minutes) / count($minutes) / 1440, 2)
            : 0.0;

        $stats_devs = $usersQuery
            ->withCount(['issues as assigned_issues_count' => function ($q) use ($idsIssues) {
                $q->whereIn('issues.id', $idsIssues);
            }])
            ->orderByDesc('assigned_issues_count')
            ->get(['id','name']);

        $stats = [
            'bugs_total'   => $bugs_total,
            'bugs_ouverts' => $bugs_ouverts,
            'bugs_en_cours'=> $bugs_en_cours,
            'bugs_resolus' => $bugs_resolus,
            'bugs_fermes'  => $bugs_fermes,
            'moyenne_resolution' => $moyenne_resolution,
            'stats_devs'   => $stats_devs,
        ];

        return [$idsIssues, $stats];
    }
}
