@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row g-4">
        <div class="col-lg-3 order-lg-1">
            <h4 class="mb-3">Équipes</h4>
            @if(session('team_success'))
                <div class="alert alert-success">{{ session('team_success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger p-2">
                    <ul class="m-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card mb-3">
                <div class="card-header">Ajouter une équipe</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('teams.store') }}">
                        @csrf
                        <div class="mb-2">
                            <label for="team_name" class="form-label">Nom</label>
                            <input id="team_name" name="name" class="form-control" required placeholder="Nom de l'équipe" value="{{ old('name') }}">
                        </div>
                        <div class="mb-2">
                            <label for="team_desc" class="form-label">Description (optionnel)</label>
                            <textarea id="team_desc" name="description" class="form-control" rows="2" placeholder="Courte description">{{ old('description') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Ajouter</button>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">Gérer les équipes</div>
                <div class="card-body p-0">
                    @if(isset($teams) && $teams->count())
                        <div class="list-group list-group-flush">
                            @foreach($teams as $team)
                                <div class="list-group-item">
                                    <form method="POST" action="{{ route('teams.update', $team->id) }}" class="mb-2">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-2">
                                            <label class="form-label small mb-1">Nom</label>
                                            <input name="name" class="form-control form-control-sm" value="{{ old('name', $team->name) }}" required>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label small mb-1">Description</label>
                                            <textarea name="description" class="form-control form-control-sm" rows="2">{{ old('description', $team->description) }}</textarea>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-sm btn-warning">Modifier</button>
                                            <form method="POST" action="{{ route('teams.destroy', $team->id) }}" onsubmit="return confirm('Supprimer cette équipe ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                            </form>
                                        </div>
                                    </form>
                                    <div class="text-muted small">
                                        Membres : {{ method_exists($team,'users_count') ? $team->users_count : ($team->users->count() ?? 0) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-3 text-muted">Aucune équipe pour l’instant.</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-9 order-lg-2">
            <h2>Gestion des utilisateurs</h2>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Équipe</th>
                        <th>Rôle actuel</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ optional($user->team)->name ?? '—' }}</td>
                            <td>{{ ucfirst($user->role) }}</td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">Modifier</a>

                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection
