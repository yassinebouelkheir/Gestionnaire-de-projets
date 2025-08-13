@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Modifier l'Amélioration</h1>

    <form method="POST" action="{{ route('improvements.update', $improvement->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="titre">Titre</label>
            <input type="text" name="titre" class="form-control" value="{{ $improvement->titre }}" required>
        </div>

        <div class="mb-3">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" required>{{ $improvement->description }}</textarea>
        </div>

        <div class="mb-3">
            <label for="state">État</label>
            <select name="state" class="form-control">
                <option value="Ouvert" {{ $improvement->state == 'Ouvert' ? 'selected' : '' }}>Ouvert</option>
                <option value="En cours" {{ $improvement->state == 'En cours' ? 'selected' : '' }}>En cours</option>
                <option value="Résolu" {{ $improvement->state == 'Résolu' ? 'selected' : '' }}>Résolu</option>
                <option value="Fermé" {{ $improvement->state == 'Fermé' ? 'selected' : '' }}>Fermé</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="user_ids">Assigner des développeurs</label>
            <select name="user_ids[]" class="form-control" multiple>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $improvement->users->contains($user->id) ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>
@endsection
