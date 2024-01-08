<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\Projet;
use App\Models\User;
use Livewire\Component;

class ShowProjet extends Component
{
    public $projets;

    public function nomClient()
    {

    }

    public function render()
    {

        //$projets = Projet::find(id);
        //dd($projets);


        $projets = $this->projets;

        $userProjet = User::where('id', $projets->id_user)->first();

        // pour recupérer le client du projet
        $clients = Client::where('id', $projets->id_client)->first();

        return view('livewire.show-projet', compact('projets', 'clients', 'userProjet'));
    }
}
