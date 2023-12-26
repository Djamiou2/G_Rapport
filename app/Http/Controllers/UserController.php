<?php

namespace App\Http\Controllers;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Gate;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        if (Gate::allows('viewliste', User::class)) {
            return view('utilisateurs.liste');
        } else {
            return view('composants.acces_refuser');
        }
    }

    public function create()
    {
        return view('utilisateurs.create');
    }

    public function edit(User $user)
    {
        return view('utilisateurs.edit', compact('user'));
    }

    public function show(User $user)
    {
        return view('utilisateurs.show', compact('user'));
    }

    public function generatepdf()
    {

        $data = ['title' => 'Liste des utilisateurs'];
        $pdf = Pdf::loadView('vue_pdf', $data);
        return $pdf->download('liste_des_utilisateurs.pdf');

    }

}