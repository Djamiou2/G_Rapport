<?php

namespace App\Livewire;

use App\Models\Intervenant;
use Livewire\Component;
use Livewire\WithPagination;

class ListeIntervenant extends Component
{

    use WithPagination;
    public $intervenant;

    public $selectedItemId;
    // fonction pour supprimer un Intervenant avec une confirmation avant de suppression
    public function confirmDelete($id)
    {
        //  selectionner le Intervenant à supprimer avec la fonction find() et le supprimeravec la fonction delete()
        $selectedItemId = Intervenant::find($id)->delete();

        $this->selectedItemId = $selectedItemId;
        return redirect("intervenants")->with('delete', 'Le Intervenant à été supprimé');
    }

    public function render()
    {
        $intervenants = Intervenant::latest()->paginate(10);
        return view('livewire.liste-intervenant', compact('intervenants'));
    }
}