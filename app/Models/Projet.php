<?php

namespace App\Models;

use App\Models\Activite;
use App\Models\Besoin;
use App\Models\Client;
use App\Models\Rapport;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projet extends Model
{
    use HasFactory;

    protected $guarded = [''];

    // un projet a plusieurs intervenants
    public function intervenant()
    {
        return $this->belongsToMany(Intervenant::class, 'id_projet');
    }

    // un projet a un seul chef chantier
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // un projet a plusieurs rapports
    public function rapport()
    {
        return $this->belongsToMany(Rapport::class, 'id_projet');
    }

    // un projet a plusieurs besoins
    public function besoins()
    {
        return $this->belongsToMany(Besoin::class, 'id_projet');
    }

    // un projet a plusieurs activités
    public function activite()
    {
        return $this->belongsToMany(Activite::class, 'id_projet');
    }


    // un projet appartient à un seul client
    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client');
    }
}