<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Liste des projets</title>

  <!-- Styles -->
  <style type="text/css">
  body {
    font-family: arial;
    letter-spacing: 0.5px;
  }

  .mobile-report-categ-panel {
    padding: 10px;
    background: #77B5FE;
  }

  table {
    width: 98%;
    border: 1px solid #ccc;
    border-collapse: collapse;
  }

  table tbody tr td {
    padding: 5px;
    border: 1px solid #77B5FE;
  }

  table thead th {
    background: #ccc;
    font-size: 15px;
    padding: 5px;
    border: 1px solid #77B5FE;
  }


  .div-tot {
    padding: 20px;
    text-align: center;
  }

  .table-projet tr td {
    padding: 3px;
    font-size: 15px;

  }
  </style>

</head>

<body>
  <div class="container">
    <table class="table table-striped table-bordered" class=" table table-projet" id="dataTable" cellspacing="0">
      <caption>
        <h2> Liste des projets</h2>
      </caption>
      <br>
      <thead class="thead-dark">
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Libelle</th>
          <th scope="col">Statut</th>
          <th scope="col">Date de debut</th>
          <th scope="col">Date de fin </th>
          <th scope="col">Client</th>
        </tr>
      </thead>
      <tbody id="tablbody">
        @if(count($projets))
        @foreach ($projets as $projet)
        <tr class="tr-off">
          <th scope="row">{{ $projet->id }}</th>
          <td>{{ $projet->libelle }}</td>
          <td>{{ $projet->statut }}</td>
          <td>{{ $projet->date_debut }}</td>
          <td>{{ $projet->date_fin_prevue }}</td>
          <td>{{ $projet->client->nom }}</td>
        </tr>
        @endforeach
        @else
        <tr class="tr-off">
          <td colspan="6" class="div-tot">Aucun projet</td>
        </tr>
        @endif
      </tbody>
    </table>
  </div>
  <!-- Intégration du script Bootstrap (facultatif si non déjà présent) -->
  <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
  <!-- Pour Bootstrap 5, utilisez le script suivant -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>


</html>