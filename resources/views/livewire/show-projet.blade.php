<link href="{{ asset('css/style-table.css') }}" rel="stylesheet">

<div class="container mt-5">
  <!-- Informations de la société et son logo -->
  <div class="row mb-4">
    <div class="col-md-6">
      <!-- Insérez le logo de la société ici -->
      <img src="{{ asset('images/innov2b.jpg')}}" alt="Logo de la société" class="img-fluid" width="100" height="auto"
        srcset="">

    </div>
    <div class="col-md-6 text-center">
      <!-- libelle de la société -->
      <h3>INNOVATION BULDING BUSINESS </h3>
    </div>
  </div>

  <!-- Tableau d'informations des projets -->
  <div class="row">
    <div class="col-md-12">
      <table class="table">
        <thead>
          <tr>
            <th colspan="2">
              @if ($projets->statut == 'en cours')
              <div class="bg-success text-white p-2">
                Informations sur l'activité : {{ $projets->libelle }}
              </div>
              @elseif ($projets->statut == 'terminé')
              <div class="bg-warning text-white p-2">
                Informations sur l'activité : {{ $projets->libelle }}
              </div>
              @elseif ($projets->statut == 'arrêté')
              <div class="bg-danger text-white p-2">
                Informations sur l'activité : {{ $projets->libelle }}
              </div>
              @endif
            </th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th scope="row">DESCRIPTION</th>
            <td>{{ $projets->description }}</td>
          </tr>
          <tr>
            <th scope="row">Date de début</th>
            <td>
              <p>Date : {{ $projets->date_debut}}</p>

            </td>
          </tr>
          <tr>
            <th scope="row">Date de fin</th>
            <td>
              <p>Date : {{ $projets->date_fin_prevue }}</p>
            </td>
          </tr>
          <tr>
            <th scope="row">Nom du Gestionnaire</th>
            <td>

              <!-- libelle du demandeur -->
            </td>
          </tr>
          <tr>
            <th scope="row"> projet :</th>
            <td>
              <p>Nom : {{ $projets->nom }}</p>
              <p>Contact : {{ $projets->contact }}</p>
            </td>
          </tr>
          <tr>
            <th scope="row">STATUT</th>

            <td>{{ $projets->statut }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div class=" row d-flex justify-content-between mb-3">
    <div class="col-md-2">
      <button type="submit" class="btn btn-secondary text text-bold">
        <a href="{{route('projet.pdf')}}" class="text-white fs-6" style="text-decoration:none;">
          PDF</a></button>
    </div>
    <div class="col-md-3">
      <button type="button" class="btn btn-primary" onclick="imprimerDetailsProjet()">Imprimer</button>
    </div>
  </div>
</div>

@livewireScripts
<script>
function imprimerDetailsProjet() {
  // Cloner la section des détails du besoin à imprimer
  var contenuImprimer = document.getElementById('detailsProjet').cloneNode(true);

  // Créer une nouvelle fenêtre pour l'impression
  var fenetreImprimer = window.open('', '_blank');

  // Ajouter le contenu cloné dans la nouvelle fenêtre
  fenetreImprimer.document.body.appendChild(contenuImprimer);

  // Lancer la commande d'impression après le chargement de la page
  fenetreImprimer.onload = function() {
    fenetreImprimer.focus(); // Focus sur la fenêtre d'impression
    fenetreImprimer.print(); // Lancer l'impression
  };
}
imprimerDetailsProjet();
</script>
