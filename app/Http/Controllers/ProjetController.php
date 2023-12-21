<?php

namespace App\Http\Controllers;

use App\Models\Projet;
use Gate;
use Illuminate\Http\Request;

class ProjetController extends Controller
{

    public function index()
    {
        if (Gate::allows('viewliste', Projet::class)) {
            return view('projets.liste');
        } else {
            return view('composants.acces_refuser'); // Redirection vers une vue indiquant un accès refusé
        }
    }

    public function create()
    {
        if (Gate::allows('create', Projet::class)) {
            return view('projets.create');
        } else {
            return view('composants.acces_refuser'); // Redirection vers une vue indiquant un accès refusé
        }
    }

    public function edit(Projet $projet)
    {
        if (Gate::allows('edit', $projet)) {
            return view('projets.edit', compact('projet'));
        } else {
            return view('composants.acces_refuser'); // Redirection vers une vue indiquant un accès refusé
        }
    }

    public function show(Projet $projet)
    {
        if (Gate::allows('view', $projet)) {
            return view('projets.show', compact('projet'));
        } else {
            return view('composants.acces_refuser'); // Redirection vers une vue indiquant un accès refusé
        }
    }


}