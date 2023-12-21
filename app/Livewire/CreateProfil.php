<?php

namespace App\Livewire;

use App\Models\Profil;
use Livewire\Component;

class CreateProfil extends Component
{
    public $nom;
    public $profil;

    public $successMessage = ''; // Message de succès
    public $showModal = false; // Pour contrôler l'affichage du modal de succès

    public function store(Profil $profil)
    {
        $this->validate([
            'nom' => 'string|required',
        ]);

        try {
            $profil->nom = $this->nom;

            $profil->save();


            // Afficher le message de succès et le modal
            $this->successMessage = 'Enregistrement du profil réussi!';
            $this->showModal = true;

            return redirect()->Route('profils');

            // ->with('success','Nouveau profil ajoutée ! ')
            //;

        } catch (\Exception $e) {

            $this->successMessage = 'Erreur d\'enregistrement du profil !';
            $this->showModal = true;
            return redirect()->back()
                //->with( 'error', 'Erreur d\'enregistrement du profil ')
            ;

        }

    }

    public function render()
    {
        return view('livewire.create-profil');
    }
}
