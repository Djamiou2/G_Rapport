<?php

namespace App\Http\Controllers;

use App\Models\Activite;
use App\Models\Besoin;
use App\Models\Bilan;
use App\Models\Projet;
use App\Models\Rapport;
use App\Models\User;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BilanController extends Controller
{


    public function index(Bilan $bilan)
    {
        return view('Bilans.bilan', compact('bilan'));
    }

    public function create()
    {
        //return view('Bilans.bilan-journalier');
    }

    public function generateBilan()
    {
        $user = Auth::user();
        $user_id = $user->id;
        $dateToday = Carbon::now();

        if ($user->id_profil == 1) {

            // Récupérer les projets en cours aujourd'hui
            $projetsEnCoursAujourdhui = Projet::where('date_debut', '<=', $dateToday)
                ->where('date_fin_prevue', '>=', $dateToday)
                ->where('statut', '=', 'en cours')
                ->get();

            $projetEnAttenteAjourdhui = Projet::where('date_debut', '>', $dateToday)
                ->where('date_fin_prevue', '>', $dateToday)
                ->where('statut', '=', 'en attente')
                ->get();

            // Récupérer les rapports créés aujourd'hui
            $rapportsCreesAujourdhui = Rapport::whereDate('created_at', $dateToday)->orwhere('updated_at', $dateToday)->get();

            // Récupérer les besoins en cours aujourd'hui

            $besoinsEnCoursAujourdhui = Besoin::whereBetween(
                'created_at',
                [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()]
            )->orwhere('updated_at', $dateToday)->get();

            $activitesEnCours = Activite::where('date_debut', '<=', $dateToday)
                ->where('date_fin', '>=', $dateToday)
                ->where('statut', '=', 'en cours')->get();

            $activitesEnAttentes = Activite::where('date_debut', '>', $dateToday)
                ->where('date_fin', '>', $dateToday)
                ->where('statut', '=', 'en attente')->get();

            // récupérer les activités terminées aujourd'hui
            $activitesTermineesAjourdhui = Activite::where('date_fin', '<=', $dateToday)
                ->where('statut', '=', 'terminé')->get();

            // récupérer les projets terminés aujourdhui
            $projetsTerminesAujourdhui = Projet::where('date_fin_prevue', '<=', $dateToday)
                ->where('statut', '=', 'terminé')->get();

            // récupérer les rapports créés aujourd'hui
            $rapportsCreesAujourdhui = Rapport::whereBetween(
                'created_at',
                [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()]
            )->orwhere('updated_at', $dateToday)->get();


            $pdf = Pdf::loadView(
                'Bilans.bilan-journalier',
                compact(
                    'activitesEnAttentes',
                    'activitesTermineesAjourdhui',
                    'dateToday',
                    'rapportsCreesAujourdhui',
                    'activitesEnCours',
                    'projetEnAttenteAjourdhui',
                    'projetsEnCoursAujourdhui',
                    'besoinsEnCoursAujourdhui',
                    'projetsTerminesAujourdhui',
                    'activitesTermineesAjourdhui'
                )
            );
            $pdf->setPaper('a4', 'landscape');
            return $pdf->stream();

        } else {

            // 1  :  Pour les projet en cours
            // Récupérer les projets de l'utilisateur qui son en cours
            $projetsEnCoursAujourdhui = Projet::where('id_gestionnaire', '=', $user_id)
                ->where('date_debut', '<=', $dateToday)
                ->where('date_fin_prevue', '>=', $dateToday)
                ->where('statut', '=', 'en cours')->get();

            // Récupérer les projets de l'utilisateur qui son en attente
            $projetEnAttenteAjourdhui = Projet::where('id_gestionnaire', '=', $user_id)
                ->where('date_debut', '>', $dateToday)
                ->where('date_fin_prevue', '>', $dateToday)
                ->where('statut', '=', 'en attente')->get();

            // Pour les activités en cours de l'utilisateur
            // Extraire uniquement les IDs des projets
            $idsProjetsEncours = $projetsEnCoursAujourdhui->pluck('id')->toArray();
            // Récupérer les activités dont le champ 'id_projet' est parmi les IDs extraits
            $activitesEnCours = Activite::whereIn('id_projet', $idsProjetsEncours)
                ->where('date_debut', '<=', $dateToday)
                ->where('date_fin', '>=', $dateToday)
                ->where('statut', '=', 'en cours')->get();

            // Pour les activites en attentes de l'utilisateur
            // Extraire uniquement les IDs des projets
            $idsProjetsEnAttentes = $projetEnAttenteAjourdhui->pluck('id')->toArray();
            // Récupérer les activités en attentes dont le champ 'id_projet' est parmi les IDs extraits
            $activitesEnAttentes = Activite::whereIn('id_projet', $idsProjetsEnAttentes)
                ->where('date_debut', '>', $dateToday)
                ->where('date_fin', '>', $dateToday)
                ->where('statut', '=', 'en cours')->get();

            $projetsTerminesAujourdhui = Projet::where('id_gestionnaire', '=', $user_id)
                ->where('date_fin_prevue', '<=', $dateToday)
                ->where('statut', '=', 'terminé')->get();

            $activitesTermineesAjourdhui = Activite::whereIn('id_projet', $idsProjetsEncours)
                ->where('date_fin', '<=', $dateToday)
                ->where('statut', '=', 'terminé')->get();

            //  Extraire uniquement les IDs des activités en cours
            $idsActiviteEncours = $activitesEnCours->pluck('id')->toArray();

            $rapportsCreesAujourdhui = Rapport::whereIn('id_activite', $idsActiviteEncours)
                ->whereBetween(
                    'created_at',
                    [
                        Carbon::now()->startOfDay(),
                        Carbon::now()->endOfDay()
                    ]
                )->orwhere('updated_at', $dateToday)->get();

            $besoinsEnCoursAujourdhui = Besoin::whereIn('id_activite', $idsActiviteEncours)
                ->whereBetween(
                    'created_at',
                    [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()]
                )->orwhere('updated_at', $dateToday)->get();

            $pdf = Pdf::loadView(
                'Bilans.bilan_journalier_gestionnaire',
                compact(
                    'dateToday',
                    'activitesEnCours',
                    'activitesEnAttentes',
                    'projetEnAttenteAjourdhui',
                    'projetsEnCoursAujourdhui',
                    'rapportsCreesAujourdhui',

                    'activitesTermineesAjourdhui',
                    'projetsTerminesAujourdhui',
                    'besoinsEnCoursAujourdhui'
                )
            );
            $pdf->setPaper('a4', 'landscape');
            return $pdf->stream();


        }

    }

    public $selectedActiviteId;

    public function updatedSelectedActiviteId()
    {
        // Vous pouvez accéder à l'ID choisi ici

        // Vous pouvez maintenant utiliser $idActiviteChoisie dans votre logique du contrôleur
    }
    public $id;
    public function generateActiviteBilan()
    {
        $idActiviteChoisie = $this->selectedActiviteId;
        dd($idActiviteChoisie);



        $activites = Activite::find($this->selectedActiviteId)->first();

        $dateToday = Carbon::now();
        // Récupérer le rapport de l'activité en cours
        $rapportsSelectedActivity = Rapport::where('id_activite', $activites->id)
            ->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
            ->orwhere('updated_at', $dateToday)->get();

        // récupérer les besoins de l'activité selectionnée
        $besoins = Besoin::where('id_activite', $activites->id)->whereBetween(
            'created_at',
            [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()]
        )->orwhere('updated_at', $dateToday)->get();

        // récuperer le projet de l'activité sélectionnée
        $projet = Projet::where('id', $activites->id_projet)->get();

        $pdf = Pdf::loadView(
            'Bilans.bilan-activite',
            compact(
                'activites',
                'projet',
                'besoins',
                'rapportsSelectedActivity'
            )
        );
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream();
    }

}
