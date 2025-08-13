@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Mes Projets</h2>

    @php
        $user = auth()->user();
        $isAdmin = $user->role === 'admin';
    @endphp

    @if($isAdmin)
        <div class="mb-4">
            <a href="{{ route('projets.create') }}" class="btn btn-primary">Créer un projet</a>
        </div>
    @endif

    @foreach($projects as $projet)
        <div class="card mb-4">
            <div class="card-header">
                <h4><a href="{{ route('projets.show', $projet->id) }}">{{ $projet->name }}</a></h4>
                <p>{{ $projet->description }}</p>
            </div>
            <div class="card-body">
                <p><strong>Avancement :</strong> {{ $projet->avancement }}%</p>
                <p><strong>Priorité :</strong>
                    @if($projet->priority == 3)
                        Haute
                    @elseif($projet->priority == 2)
                        Moyenne
                    @else
                        Normale
                    @endif
                </p>

                <p><strong>Nombre total de problèmes :</strong> {{ $projet->issues->count() }}</p>
                <p><strong>Nombre total d'améliorations :</strong> {{ $projet->improvements->count() }}</p>

                @if($isAdmin || $projet->creator_id == $user->id)
                    <a href="{{ route('projets.edit', $projet->id) }}" class="btn btn-sm btn-outline-warning">Modifier</a>
                @endif

                @if($isAdmin)
                    <form action="{{ route('projets.destroy', $projet->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Supprimer ce projet ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                    </form>
                @endif

                @if(!$isAdmin)
                    <div class="mt-3">
                        <h5>Problèmes visibles</h5>
                        <ul>
                            @foreach($projet->issues->filter(fn($issue) => $issue->creator_id == $user->id || $issue->users->contains($user->id)) as $issue)
                                <li>
                                    <a href="{{ route('issues.show', $issue->id) }}">{{ $issue->titre }}</a> — <small>{{ ucfirst($issue->state) }}</small>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mt-3">
                        <h5>Améliorations visibles</h5>
                        <ul>
                            @foreach($projet->improvements->filter(fn($imp) => $imp->creator_id == $user->id || $imp->users->contains($user->id)) as $improvement)
                                <li>
                                    <a href="{{ route('improvements.show', $improvement->id) }}">{{ $improvement->titre }}</a> — <small>{{ ucfirst($improvement->state) }}</small>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
