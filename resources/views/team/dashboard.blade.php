@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Tableau de bord équipe</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('team.dashboard.export.global') }}"
            class="btn btn-outline-secondary">
                Télécharger le rapport (CSV)
            </a>
            <a href="{{ route('team.dashboard.export.devs') }}"
            class="btn btn-outline-secondary">
                Télécharger détails développeurs (CSV)
            </a>
        </div>
    </div>
    <div class="row mb-4 g-3">
        <div class="col-12 col-md-3">
            <div class="card p-3 h-100">
                <div class="fw-bold">Bugs total</div>
                <div class="display-6">{{ $stats['bugs_total'] ?? 0 }}</div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card p-3 h-100">
                <div class="fw-bold">Ouverts</div>
                <div class="display-6">{{ $stats['bugs_ouverts'] ?? 0 }}</div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card p-3 h-100">
                <div class="fw-bold">En cours</div>
                <div class="display-6">{{ $stats['bugs_en_cours'] ?? 0 }}</div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card p-3 h-100">
                <div class="fw-bold">Résolus / Fermés</div>
                <div class="display-6">
                    {{ ($stats['bugs_resolus'] ?? 0) + ($stats['bugs_fermes'] ?? 0) }}
                </div>
                <small class="text-muted">
                    Résolus : {{ $stats['bugs_resolus'] ?? 0 }} — Fermés : {{ $stats['bugs_fermes'] ?? 0 }}
                </small>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12 col-md-4">
            <div class="card p-3 h-100">
                <div class="fw-bold">Moyenne de résolution</div>
                <div class="display-6">
                    {{ isset($stats['moyenne_resolution']) ? number_format($stats['moyenne_resolution'], 2, ',', ' ') : '0,00' }} j
                </div>
                <small class="text-muted">Calculée sur les tickets résolus/fermés</small>
            </div>
        </div>
    </div>
    <h5 class="mt-2 mb-3">Charge par développeur</h5>
    <div class="table-responsive">
        <table class="table table-sm align-middle">
            <thead>
                <tr>
                    <th>Développeur</th>
                    <th class="text-end">Bugs assignés</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($stats['stats_devs'] ?? []) as $dev)
                    <tr>
                        <td>{{ $dev->name ?? ('User #'.($dev->id ?? '')) }}</td>
                        <td class="text-end">{{ $dev->assigned_issues_count ?? 0 }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-muted">Aucun développeur trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
