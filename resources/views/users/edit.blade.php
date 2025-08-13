@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Modifier rôle de {{ $user->name }}</h2>
    <p class="text-muted" style="margin-top:-6px;">
        Équipe actuelle :
        <strong>{{ optional($user->team)->name ?? '— Aucune —' }}</strong>
    </p>

    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="role" class="form-label">Rôle</label>
            <select name="role" id="role" class="form-control" required>
                @foreach($roles as $role)
                    <option value="{{ $role }}" {{ $user->role === $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
        </div>

        @isset($teams)
        <div class="mb-3">
            <label for="team_id" class="form-label">Équipe</label>
            <select name="team_id" id="team_id" class="form-control">
                <option value="">— Aucune —</option>
                @foreach($teams as $team)
                    <option value="{{ $team->id }}" {{ (old('team_id', $user->team_id ?? null) == $team->id) ? 'selected' : '' }}>
                        {{ $team->name }}
                    </option>
                @endforeach
            </select>
        </div>
        @endisset

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
