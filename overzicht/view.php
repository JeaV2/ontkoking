<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/overzicht.css">
</head>

<body>
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Stop de Ontkoking</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Recepten</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Inloggen</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!--! Change the amount of rows and columns as needed for the page layout! -->
  <div class="container-fluid no-margin">
    <div class="row">
      <div class="col-md-12">
        <div class="content">
          <h1 class="text-center">Recepten overzicht.</h1>
          <p class="text-center">Kies een lekker recept en kook het!</p>
          <div class="d-flex flex-wrap justify-content-center gap-4">
            <?php foreach ($recepten as $recept): ?>
              <div class="card">
                <img src="<?= $recept['Plaatje']; ?>" class="card-img-top" alt="<?= $recept['ReceptNaam']; ?>" >
                <div class="card-body">
                  <h5 class="card-title"><?= $recept['ReceptNaam']; ?></h5>
                  <p class="card-text">Categorie: <?= $recept['Categorie']; ?></p>
                  <p class="card-text"><small class="text-muted">Toegevoegd door: <?= $recept['Naam']; ?></small></p>
                  <a href="../recept?id=<?= $recept['ReceptID']; ?>" class="btn btn-primary">Bekijk recept!</a>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>