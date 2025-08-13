@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Modifier le Projet</h2>

    <form action="{{ route('projets.update', $projet->id) }}" method="POST">
        @csrf
        @method('PUT')

        @php
            $user = auth()->user();
            $isAdmin = $user->role === 'admin';
            $isCreator = $projet->creator_id === $user->id;
        @endphp

        <div class="mb-3">
            <label for="name" class="form-label">Nom</label>
            <input type="text" name="name" class="form-control" value="{{ $projet->name }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="form-control" required>{{ $projet->description }}</textarea>
        </div>

        @if($isAdmin)
            <div class="mb-3">
                <label for="priority" class="form-label">Priorité</label>
                <select name="priority" class="form-control">
                    <option value="1" {{ $projet->priority == 1 ? 'selected' : '' }}>Normale</option>
                    <option value="2" {{ $projet->priority == 2 ? 'selected' : '' }}>Moyenne</option>
                    <option value="3" {{ $projet->priority == 3 ? 'selected' : '' }}>Haute</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="avancement" class="form-label">Avancement (%)</label>
                <input type="number" name="avancement" class="form-control" value="{{ $projet->avancement }}" min="0" max="100">
            </div>

            <div class="mb-3">
                <label for="user_ids" class="form-label">Visibilité (utilisateurs assignés)</label>
                <select name="user_ids[]" class="form-control" multiple>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $projet->users->contains($user->id) ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
    </form>
</div>
@endsection
