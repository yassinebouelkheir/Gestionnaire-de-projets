@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Créer un Projet</h1>

    <form method="POST" action="{{ route('projets.store') }}">
        @csrf

        <div class="mb-3">
            <label for="name">Nom du projet</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label for="priority">Priorité</label>
            <select name="priority" class="form-control">
                <option value="1">Normale</option>
                <option value="2">Moyenne</option>
                <option value="3">Haute</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="avancement">Avancement (%)</label>
            <input type="number" name="avancement" class="form-control" min="0" max="100">
        </div>

        <div class="mb-3">
            <label for="user_ids">Assigner des utilisateurs</label>
            <select name="user_ids[]" class="form-control" multiple>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="team_id">Équipe</label>
            <select name="team_id" class="form-control" required>
                @foreach($teams as $team)
                    <option value="{{ $team->id }}">{{ $team->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Créer</button>
    </form>
</div>
@endsection
