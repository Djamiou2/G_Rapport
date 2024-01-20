<?php

namespace App\Livewire;

use App\Models\Activite;
use App\Models\Client;
use App\Models\Intervenant;
use App\Models\Notification;
use App\Models\Projet;
use App\Models\User;
use Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ListeProjet extends Component
{

    use WithPagination;

    public $projet;
    public $id;
    public $search;

    public $statut;
    public $date_debut;
    public $date_fin_prevue;
    public $id_gestionnaire;


    // la fonction pour afficher les projets en fonction de la recherche et de l'utilisateur connecté
    public function s()
    {
    }



    public function confirmDelete($id)
    {
        try {
            // Trouver le projet
            $projet = Projet::findOrFail($id);

            if (!$this->canDeleteProjet($projet)) {
                throw new \Exception('Impossible de supprimer ce projet.');
            }
            // Créer une notification pour la suppression du projet
            $notification = new Notification;
            $notification->user_id = Auth::user()->id;
            $notification->type = "Projet";
            $notification->titre = "Suppression d'une Projet";
            $notification->message = "Le Projet : " . $projet->libelle . " vient d'être supprimé par : " . Auth::user()->email;
            $notification->read = false;
            $notification->save();
            // Supprimer le projet
            $projet->delete();
            return redirect("projets")->with('delete', 'Le projet a été supprimé avec succès');
        } catch (\Exception $e) {
            return redirect("projets")
                ->with('error', 'Impossible de supprimer ce projet  : Vous devez supprimer tous les enregistrements liés à ce projet avant de le supprimer ');
        }
    }

    private function canDeleteProjet($projet)
    {
        // Vérifier s'il existe des relations avec d'autres modèles
        $references = [
            'activite',
            'intervenant',
        ];

        foreach ($references as $relation) {
            if ($projet->$relation()->count() > 0) {
                return false;
            }
        }

        $projectID = $projet->id;

        // Vérification pour la table 'Activite'
        if ($this->hasRecordsInActivite($projectID)) {
            return false;
        }

        // Vérification pour la table 'Intervenant'
        if ($this->hasRecordsInIntervenant($projectID)) {
            return false;
        }

        // Ajoutez d'autres vérifications spécifiques ici si nécessaire

        return true;
    }

    private function hasRecordsInActivite($projectID)
    {
        return Activite::where('id_projet', $projectID)->exists();
    }

    private function hasRecordsInIntervenant($projectID)
    {
        return Intervenant::where('id_projet', $projectID)->exists();
    }



    protected $rules = [
        'date_debut' => 'required|date',
        'date_fin_prevue' => 'required|date|after_or_equal:date_debut',
    ];

    public function ValidationStatutProjet($id)
    {
        $projet = Projet::find($id);

        $this->createNotification($projet);

        // Associer le statut du projet
        if ($this->statut != null && $this->date_debut != null && $this->date_fin_prevue != null) {
            $projet->statut = $this->statut;
            $projet->date_debut = $this->date_debut;
            $projet->date_fin_prevue = $this->date_fin_prevue;

            if ($projet->statut == "en cours") {
                $this->rules['date_debut'] = 'required|date|before_or_equal:' . now()->toDateString();
                $projet->save();
                return redirect("projets")->with('Encours', 'Le Projet est en cours');
            } elseif ($projet->statut == "terminé") {
                $this->rules['date_fin_prevue'] = 'required|date|before_or_equal:' . now()->toDateString();
                $projet->save();
                return redirect("projets")->with('terminer', 'Vous venez de notifier la finalisation du projet');
            } elseif ($projet->statut == "arrêté") {
                $this->rules['date_debut'] = 'required|date|before:' . now()->toDateString();
                $this->rules['date_fin_prevue'] = 'required|date|after:' . now()->toDateString();
                $projet->save();
                return redirect("projets")->with('arreter', 'Le Projet a été arrêté');
            } elseif ($projet->statut == "en attente") {
                $this->rules['date_debut'] = 'required|date|after:' . now()->toDateString();
                $projet->save();
                return redirect("projets")->with('Enattente', 'Le Projet est toujours en cours');
            }
        }

        $this->validateOnly('date_debut');
        $this->validateOnly('date_fin_prevue');
    }

    protected function createNotification($projet)
    {
        $notification = new Notification;
        $notification->user_id = Auth::user()->id;
        $notification->read = false;

        switch ($this->statut) {
            case 'en cours':
                $notification->titre = "Démarrage d'un Projet";
                $notification->message = "Le démarrage du Projet : " . $projet->libelle . " vient d'être notifié démarré par :" . Auth::user()->email;
                break;
            case 'terminé':
                $notification->titre = "Finition d'un Projet";
                $notification->message = "La finition du Projet : " . $projet->libelle . " vient d'être validée par :" . Auth::user()->email;
                break;
            case 'arrêté':
                $notification->titre = "Arrêt d'un Projet";
                $notification->message = "Le Projet : " . $projet->libelle . " vient d'être arrêté par :" . Auth::user()->email;
                break;
            case 'en attente':
                $notification->titre = "Mise en attente de Projet";
                $notification->message = "Le Projet : " . $projet->libelle . " est mis en attente par :" . Auth::user()->email;
                break;
            // Ajoutez d'autres cas au besoin...
            default:
                // Gérer le cas par défaut ou ne rien faire
                break;
        }

        $notification->type = "Projet";
        $notification->save();
    }

    // Pour récuperer le nom et prenom du gestionnaire de projet
    public function nomGestionnaire()
    {
        // récuperer l'id du gestionnaire de projet
        $id_projetProjet = Projet::where('id_gestionnaire', $this->id_gestionnaire)->first();
        // récuperer l'utilisateur dont le champ id est égal au champ id_gestionnaire du projet
        $selectGestionnaire = User::where('id', $id_projetProjet)->first();
        dd($selectGestionnaire);
        // return $selectGestionnaire->nom . " " . $selectGestionnaire->prenom;
    }

    public function mount()
    {

        // recuperer l'id du gestionnaire de projet
        $gestionnaireId = Projet::where('id_gestionnaire', '=', Auth::user()->id)->get();
        //$gestionnaireId = User::where('id', '=', 'id_gestionnaire')->get();
        // dd($gestionnaireId);

    }

    public function render()
    {
        $word = '%' . $this->search . '%';

        $projets = Projet::where(
            'libelle',
            'like',
            $word
        )->orwhere('description', 'like', $word)
            ->orwhere('lieu', 'like', $word)
            ->paginate(10);


        // $currentProjet = Projet::where('id_gestionnaire', '=', Auth::user()->id)->get();
        $user = Auth::user();
        $user_id = Auth::user()->id;

        // Verifier si le gestionnaire de projet est le même que l'utilisateur
        if ($user->id_profil == 1) {
            // tout les projets
            $projets = Projet::paginate(10);
        } else {
            $projets = Projet::where('id_gestionnaire', '=', $user_id)->paginate(10);
        }

        return view('livewire.liste-projet', compact('projets'));
    }
}
