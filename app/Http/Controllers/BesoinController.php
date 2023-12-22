<?php

namespace App\Http\Controllers;

use App\Models\Besoin;
use Gate;
use Illuminate\Http\Request;

class BesoinController extends Controller
{

    public function index()
    {
        if (Gate::allows('viewliste', Besoin::class)) {
            return view('besoins.liste');
        } else {
            return view('composants.redirection-new-user'); // Redirection vers une vue indiquant un accès refusé
        }
    }

    public function create()
    {
        return view('besoins.create');
    }

    public function edit(Besoin $besoin)
    {
        if (Gate::allows('edit', $besoin)) {
            return view('besoins.edit', compact('besoin'));
        } else {
            return view('composants.acces_refuser'); // Redirection vers une vue indiquant un accès refusé
        }
    }

    public function show(Besoin $besoin)
    {
        if (Gate::allows('view', $besoin)) {
            return view('besoins.show', compact('besoin'));
        } else {
            return view('composants.acces_refuser'); // Redirection vers une vue indiquant un accès refusé
        }
    }

}
