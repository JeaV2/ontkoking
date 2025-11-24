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
  <link rel="stylesheet" href="../css/recept.css">
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
            <a class="nav-link" href="../">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../overzicht/">Recepten</a>
          </li>
          <?php if(isset($_SESSION['id'])): ?>
            <li class="nav-item">
              <a class="nav-link" href="../toevoegen/">Recept Toevoegen</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../login/logout.php">Uitloggen</a>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="../login/">Inloggen</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../aanmelden/">Aanmelden</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container-fluid no-margin">
    <div class="row ">
      <div class="col-md-12 d-flex justify-content-center mt-3">
        <div class="img-container">
          <img src="<?= $recept['Plaatje'] ?>" alt="">
          <p class="recept-naam"><?= $recept['ReceptNaam'] ?></p>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="content">
        <div class="alert alert-light recept-beschrijving">
          <p><?= $recept['ReceptInfo'] ?></p>
          <h2>Ingrediënten</h2>
          <ul>
            <?php foreach ($ingredienten as $ingredient): ?>
              <li>
                <?php if (!empty($ingredient['Aantal'])): ?>
                  <?= $ingredient['Aantal'] ?>
                <?php endif; ?>
                <?php if (!empty($ingredient['Grootte'])): ?>
                  <?= $ingredient['Grootte'] ?>
                <?php endif; ?>
                <?= $ingredient['Ingredient'] ?>
              </li>
            <?php endforeach; ?>
          </ul>
          <h2>Bereiding</h2>
          <p>
            <?= nl2br($recept['Beschrijving']) ?>
          </p>
          <br><br>
          <h2>Einde!</h2>
          <p>
            Geplaatst door: <?= htmlspecialchars($recept['Naam']) ?>
            <br>
            <?php if (isset($_SESSION['id']) && $_SESSION['id'] == $recept['GebruikerID']): ?>
              <a href="../bewerken/?id=<?= $recept['ReceptID'] ?>" class="btn btn-warning">Recept Bewerken</a>
            <?php endif; ?>
            <!-- Heb jij dit recept gekookt?
            <button class="btn btn-success" onclick="">Ja!</button> -->
          </p>
        </div>
      </div>
    </div>
  </div>
</body>

</html>