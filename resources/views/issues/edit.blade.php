@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Modifier le Problème</h1>

    <form method="POST" action="{{ route('issues.update', $issue->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="titre">Titre</label>
            <input type="text" name="titre" class="form-control" value="{{ $issue->titre }}" required>
        </div>

        <div class="mb-3">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" required>{{ $issue->description }}</textarea>
        </div>

        <div class="mb-3">
            <label for="priority">Priorité</label>
            <select name="priority" class="form-control">
                <option value="1" {{ $issue->priority == 1 ? 'selected' : '' }}>Normale</option>
                <option value="2" {{ $issue->priority == 2 ? 'selected' : '' }}>Moyenne</option>
                <option value="3" {{ $issue->priority == 3 ? 'selected' : '' }}>Haute</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="state">État</label>
            <select name="state" class="form-control">
                <option value="Ouvert" {{ $issue->state == 'Ouvert' ? 'selected' : '' }}>Ouvert</option>
                <option value="En cours" {{ $issue->state == 'En cours' ? 'selected' : '' }}>En cours</option>
                <option value="Résolu" {{ $issue->state == 'Résolu' ? 'selected' : '' }}>Résolu</option>
                <option value="Fermé" {{ $issue->state == 'Fermé' ? 'selected' : '' }}>Fermé</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="user_ids">Assigner des développeurs</label>
            <select name="user_ids[]" class="form-control" multiple>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $issue->users->contains($user->id) ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>
@endsection
