@foreach($projets as $projet)
<div wire:ignore.self class="modal fade fullscreen-modal" id="confirmProfilModal{{$projet->id }}" tabindex="-1"
  role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"> Statut du projet </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <br>
        <div class="text-left">
          <label class="font-weight-bold d-block" for="statut">Sélectionnez un statut :</label>
        </div>
        <br>
        <form>

          <div class="form-group">
            <div>
              <input type="radio" id="statutEnAttente{{$projet->id}}" wire:model="statut" value="en attente"
                name="statut" @if($projet->statut === 'en attente') checked @endif">
              <label for="statutEnAttente{{$projet->id}}">En attente</label>
            </div>

            <div>
              <input type="radio" id="statutEnAttente{{$projet->id}}" wire:model="statut" value="en cours" name="statut"
                @if($projet->statut === 'en cours') checked @endif">
              <label for="statutEnAttente{{$projet->id}}">En cours</label>
            </div>

            <div>
              <input type="radio" id="statutEnAttente{{$projet->id}}" wire:model="statut" value="terminé" name="statut"
                @if($projet->statut === 'terminé') checked @endif">
              <label for="statutEnAttente{{$projet->id}}">Terminé</label>
            </div>
            <div>
              <input type="radio" id="statutEnAttente{{$projet->id}}" wire:model="statut" value="arrêté" name="statut"
                @if($projet->statut === 'arrêté') checked @endif">
              <label for="statutEnAttente{{$projet->id}}">Arrêté</label>
            </div>

            <div class="mb-3">
              <label for="date_debut" class="form-label">Date Debut :</label>
              <input type="date" class="form-control @error('date_debut') is-invalid @enderror" id="date_debut"
                wire:model="date_debut" name="date_debut" required wire:change="handleDateDebutChange">
              <div class="error-message invalid-feedback">Le champ date_debut est requis.</div>
            </div>

            <div class="mb-3">
              <label for="date_fin_prevue" class="form-label">Date Fin :</label>
              <input type="date" class="form-control @error('date_fin_prevue') is-invalid @enderror"
                id="date_fin_prevue" wire:model="date_fin_prevue" name="date_fin_prevue" required
                wire:change="handleDateFinPrevueChange">
              <div id="date_fin_prevue_error" class="error-message invalid-feedback" style="display: none;">La date de
                fin
                ne peut pas être antérieure à la date de début.</div>
              <!-- Affiche le message d'erreur si le champ est vide -->
              @error('date_fin_prevue')
              <div class="error-message invalid-feedback">Le champ date fin est requis.</div>
              @enderror
            </div>

            <div>
              <a href="{{route('projets')}}">
                <button type="button" class="btn btn-danger">Annuler</button>
              </a>
              <button type="submit" wire:click="ValidationStatutProjet('{{$projet->id}}')"
                class="btn btn-primary">Enregistrer</button>
            </div>
          </div>
        </form>
      </div>

    </div>

  </div>
</div>
</div>
@endforeach

@livewireScripts()
<!-- <script src="{{asset('js\js_modal\js_statut_modal_projet.js')}}"></script> -->

<script src="{{asset('js\js_projet\condition-statut_dates.js')}}"></script>
<!-- <script src="{{asset('js\js_projet\date-condition_projet.js')}}"></script> -->

<script>
document.addEventListener('DOMContentLoaded', function() {
  var statutSelect = document.getElementById('statut');
  var dateDebutInput = document.getElementById('date_debut');
  var dateFinInput = document.getElementById('date_fin_prevue');

  // Fonction pour désactiver les dates dans le passé
  function disablePastDates() {
    var today = new Date().toISOString().split('T')[0];
    dateDebutInput.setAttribute('min', today);
  }

  // Fonction pour ajouter +1 jour à la date actuelle
  function addOneDayToCurrentDate() {
    var today = new Date();
    today.setDate(today.getDate() + 1);
    return today.toISOString().split('T')[0];
  }

  // Fonction pour gérer les conditions en fonction du statut sélectionné
  function gererChangementStatut() {
    var selectedStatut = statutSelect.value;

    // Réinitialiser les contraintes des champs de date
    dateDebutInput.removeAttribute('max');
    dateFinInput.removeAttribute('min');
    dateFinInput.removeAttribute('max');

    // Réinitialiser les messages d'erreur
    var errorMessages = document.querySelectorAll('.error-message');
    errorMessages.forEach(function(element) {
      element.style.display = 'none';
    });

    // Appliquer les contraintes en fonction du statut sélectionné
    switch (selectedStatut) {
      case 'en attente':
        disablePastDates();
        dateDebutInput.setAttribute('min', addOneDayToCurrentDate());
        break;
      case 'en cours':
        disablePastDates();
        dateDebutInput.setAttribute('max', new Date().toISOString().split('T')[0]);
        dateDebutInput.setAttribute('min', new Date().toISOString().split('T')[0]);
        dateFinInput.setAttribute('min', new Date().toISOString().split('T')[
          0]); // Date de début ou égale à la date du jour
        break;
      case 'terminé':
        dateDebutInput.setAttribute('max', new Date().toISOString().split('T')[0]);
        dateDebutInput.removeAttribute('min'); // Permettre une date de début antérieure à la date du jour
        dateFinInput.setAttribute('min', dateDebutInput.value);
        dateFinInput.setAttribute('max', new Date().toISOString().split('T')[
          0]); // Fixer la date de fin à la date du jour
        break;
      case 'arrêté':
        dateDebutInput.setAttribute('max', new Date().toISOString().split('T')[0]);
        dateDebutInput.removeAttribute('min');
        dateFinInput.setAttribute('min', addOneDayToCurrentDate());
        dateFinInput.removeAttribute('max');
        break;
      default:
        // Si le statut n'est pas géré, réinitialiser toutes les contraintes
        disablePastDates();
    }
  }

  // Ajouter un gestionnaire d'événements pour le changement de statut
  statutSelect.addEventListener('change', gererChangementStatut);

  // Appeler la fonction une fois au chargement de la page pour gérer le statut initial
  gererChangementStatut();
});

document.addEventListener('DOMContentLoaded', function() {
      var statutSelect = document.getElementById('statut');
      var dateDebutInput = document.getElementById('date_debut');
      var dateFinInput = document.getElementById('date_fin_prevue');

      // Fonction pour désactiver les dates dans le passé
      function disablePastDates() {
        var today = new Date().toISOString().split('T')[0];
        dateDebutInput.setAttribute('min', today);
      }

      // Fonction pour ajouter +1 jour à la date actuelle
      function addOneDayToCurrentDate() {
        var today = new Date();
        today.setDate(today.getDate() + 1);
        return today.toISOString().split('T')[0];
      }


      // Fonction de gestion de dates
      document.addEventListener("DOMContentLoaded", function() {
        let dateDebut = document.getElementById('date_debut');
        let dateFin = document.getElementById('date_fin_prevue');
        let dateFinError = document.getElementById('date_fin_prevue_error');

        dateDebut.addEventListener('change', function() {
          dateFin.min = dateDebut.value; // Définit la date minimum pour le champ de date de fin
          if (dateFin.value !== '' && dateFin.value < dateDebut.value) {
            dateFin.value = ''; // Réinitialise la date de fin si elle est antérieure à la date de début
            dateFinError.style.display = 'block'; // Affiche le message d'erreur
          } else {
            dateFinError.style.display = 'none'; // Masque le message d'erreur si les dates sont valides
          }
        });

        dateFin.addEventListener('change', function() {
          if (dateFin.value !== '' && dateFin.value < dateDebut.value) {
            dateFin.value = ''; // Réinitialise la date de fin si elle est antérieure à la date de début
            dateFinError.style.display = 'block'; // Affiche le message d'erreur
          } else {
            dateFinError.style.display = 'none'; // Masque le message d'erreur si les dates sont valides
          }
        });
      });
</script>