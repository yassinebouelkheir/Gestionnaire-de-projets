@extends('layouts.app')

@section('content')
<div class="container px-4">
    <div class="row">
        <div class="col-md-6">
            <h2>{{ $issue->titre }}</h2>
            <p><strong>Description :</strong> {{ $issue->description }}</p>

            <p><strong>Priorité :</strong>
                @if($issue->priority == 3)
                    Haute
                @elseif($issue->priority == 2)
                    Moyenne
                @else
                    Normale
                @endif
            </p>

            <p><strong>État :</strong> {{ ucfirst($issue->state) }}</p>
            <p><strong>Créé par l'utilisateur :</strong> {{ $issue->creator->name ?? 'Inconnu' }}</p>

            @if(auth()->user()->role === 'admin' || $issue->users->contains(auth()->user()))
                <div class="mt-2">
                    <a href="{{ route('issues.edit', $issue->id) }}" class="btn btn-warning">Modifier</a>

                    <form action="{{ route('issues.destroy', $issue->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                </div>
            @endif

            <div class="mt-4">
                <h4>Joindre un fichier</h4>
                <form action="{{ route('attachments.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="attachable_type" value="App\\Models\\Issue">
                    <input type="hidden" name="attachable_id" value="{{ $issue->id }}">

                    <div class="mb-2">
                        <input type="file" name="attachment" required>
                    </div>
                    <button type="submit" class="btn btn-secondary">Joindre un fichier</button>
                </form>

                @if($issue->attachments->count())
                    <h5 class="mt-3">Fichiers joints :</h5>
                    <ul class="list-unstyled">
                        @foreach($issue->attachments as $attachment)
                            <li>
                                <a href="{{ asset("storage/{$attachment->path}") }}" target="_blank">
                                    {{ $attachment->filename }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <h5>Ajouter un commentaire</h5>
            <form action="{{ route('comments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="commentable_type" value="App\Models\Issue">
                <input type="hidden" name="commentable_id" value="{{ $issue->id }}">
                <div class="mb-2">
                    <textarea name="content" class="form-control" placeholder="Votre commentaire..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Publier</button>
            </form>

            <h5 class="mt-4">Commentaires</h5>

        @if($issue->comments->isEmpty())
            <p class="text-muted">Aucun commentaire pour le moment.</p>
        @else
            <ul class="list-group">
                @foreach($issue->comments as $comment)
                    <li class="list-group-item">
                        <strong>{{ $comment->user->name ?? 'Utilisateur inconnu' }}</strong>
                        <small class="text-muted">— {{ $comment->created_at->format('d/m/Y H:i') }}</small>
                        <div class="mt-1">
                            {{ $comment->content }}
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
        </div>
    </div>
</div>
@endsection
