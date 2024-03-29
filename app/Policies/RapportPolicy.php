<?php

namespace App\Policies;

use App\Models\Rapport;
use App\Models\User;
use Auth;
use Illuminate\Auth\Access\HandlesAuthorization;

class RapportPolicy
{

    use HandlesAuthorization;


    public function viewliste(User $user)
    {
        return in_array($user->id_profil, [1, 2]);
    }

    public function create(User $user)
    {
        //Tout le monde peut créer un rapport
        return true;
    }

    public function edit(User $user, Rapport $rapport)
    {
        $user = Auth::user();
        // Seuls l'utilisateur ayant l'id profil 2 ou l'utilisateur propriétaire du rapport peuvent éditer un rapport
        return $user->id_profil === 1 || $user->id === $rapport->user_id;
    }

    public function view(User $user, Rapport $rapport)
    {
        // Autoriser tous les utilisateurs à voir un rapport
        return $user->id_profil === 1 || $user->id === $rapport->user_id;
    }

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {

    }
}
