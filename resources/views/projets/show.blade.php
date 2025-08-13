@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ $projet->name }}</h2>
    <p>{{ $projet->description }}</p>
    <p><strong>Equipe :</strong> {{ $projet->team->name }}</p>
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

    <div class="my-4 d-flex gap-3">
        <button class="btn btn-danger btn-lg" onclick="toggleModal('issueModal')">Déclarer un problème</button>
        <button class="btn btn-success btn-lg" onclick="toggleModal('improvementModal')">Proposer une amélioration</button>
    </div>

    <div id="issueModal" class="modal" style="display:none;">
        <div class="modal-content p-4 border rounded bg-white shadow">
            <h4>Déclarer un problème</h4>
            <form action="{{ route('issues.store') }}" method="POST">
                @csrf
                <input type="hidden" name="projet_id" value="{{ $projet->id }}">
                <div class="mb-2">
                    <input type="text" name="titre" class="form-control" placeholder="Titre" required>
                </div>
                <div class="mb-2">
                    <textarea name="description" class="form-control" placeholder="Description..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Soumettre</button>
                <button type="button" class="btn btn-secondary" onclick="toggleModal('issueModal')">Annuler</button>
            </form>
        </div>
    </div>

    <div id="improvementModal" class="modal" style="display:none;">
        <div class="modal-content p-4 border rounded bg-white shadow">
            <h4>Proposer une amélioration</h4>
            <form action="{{ route('improvements.store') }}" method="POST">
                @csrf
                <input type="hidden" name="projet_id" value="{{ $projet->id }}">
                <div class="mb-2">
                    <input type="text" name="titre" class="form-control" placeholder="Titre" required>
                </div>
                <div class="mb-2">
                    <textarea name="description" class="form-control" placeholder="Description..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Soumettre</button>
                <button type="button" class="btn btn-secondary" onclick="toggleModal('improvementModal')">Annuler</button>
            </form>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-4">
            <h4>Problèmes ouverts</h4>
            <ul>
                @foreach($projet->issues->where('state', 'Ouvert') as $issue)
                    <li><a href="{{ route('issues.show', $issue->id) }}">{{ $issue->titre }}</a></li>
                @endforeach
            </ul>
            <h4>Problèmes en attente</h4>
            <ul>
                @foreach($projet->issues->where('state', 'En cours') as $issue)
                    <li><a href="{{ route('issues.show', $issue->id) }}">{{ $issue->titre }}</a></li>
                @endforeach
            </ul>
            <h4>Problèmes résolus</h4>
            <ul>
                @foreach($projet->issues->where('state', 'Résolu') as $issue)
                    <li><a href="{{ route('issues.show', $issue->id) }}">{{ $issue->titre }}</a></li>
                @endforeach
            </ul>
            <h4>Problèmes fermés</h4>
            <ul>
                @foreach($projet->issues->where('state', 'Fermé') as $issue)
                    <li><a href="{{ route('issues.show', $issue->id) }}">{{ $issue->titre }}</a></li>
                @endforeach
            </ul>
        </div>

        <div class="col-md-4">
            <h4>Améliorations ouverts</h4>
            <ul>
                @foreach($projet->improvements->where('state', 'Ouvert') as $imp)
                    <li><a href="{{ route('improvements.show', $imp->id) }}">{{ $imp->titre }}</a></li>
                @endforeach
            </ul>

            <h4>Améliorations en attente</h4>
            <ul>
                @foreach($projet->improvements->where('state', 'En cours') as $imp)
                    <li><a href="{{ route('improvements.show', $imp->id) }}">{{ $imp->titre }}</a></li>
                @endforeach
            </ul>

            <h4>Améliorations résolus</h4>
            <ul>
                @foreach($projet->improvements->where('state', 'Résolu') as $imp)
                    <li><a href="{{ route('improvements.show', $imp->id) }}">{{ $imp->titre }}</a></li>
                @endforeach
            </ul>

            <h4>Améliorations fermés</h4>
            <ul>
                @foreach($projet->improvements->where('state', 'Fermé') as $imp)
                    <li><a href="{{ route('improvements.show', $imp->id) }}">{{ $imp->titre }}</a></li>
                @endforeach
            </ul>
        </div>

        <div class="col-md-4">
            <h5>Ajouter un commentaire</h5>
            <form action="{{ route('comments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="commentable_type" value="App\Models\Projet">
                <input type="hidden" name="commentable_id" value="{{ $projet->id }}">
                <div class="mb-2">
                    <textarea name="content" class="form-control" placeholder="Votre commentaire..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Publier</button>
            </form>

            <h5 class="mt-4">Commentaires</h5>
            @if($projet->comments->isEmpty())
                <p class="text-muted">Aucun commentaire pour le moment.</p>
            @else
                <ul class="list-group">
                    @foreach($projet->comments as $comment)
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

    @if(auth()->user() && auth()->user()->role === 'admin')
        <form action="{{ route('projets.destroy', $projet->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?');" style="margin-top: 20px;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Supprimer le projet</button>
        </form>
    @endif
</div>

<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        modal.style.display = modal.style.display === 'none' ? 'flex' : 'none';
    }

    window.onclick = function(event) {
        ['issueModal', 'improvementModal'].forEach(modalId => {
            const modal = document.getElementById(modalId);
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });
    }
</script>
@endsection
