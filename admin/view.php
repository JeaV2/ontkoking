<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin dashboard - Stop de Ontkoking</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../css/style.css">
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
            <a class="nav-link" href="../overzicht/">Recepten</a>
          </li>
          <?php if(isset($_SESSION['id'])): ?>
            <li class="nav-item">
              <a class="nav-link" href="../toevoegen/">Recept Toevoegen</a>
            </li>
            <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'Admin'): ?>
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="./">Admin</a>
              </li>
            <?php endif; ?>
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
    <div class="row">
      <div class="col-md-12">
        <div class="content">
          <h1 class="mb-4">Beheer gebruikers en recepten</h1>
          <?php if (!empty($status['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($status['success']) ?></div>
          <?php endif; ?>
          <?php if (!empty($status['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($status['error']) ?></div>
          <?php endif; ?>

          <?php foreach ($users as $user): ?>
            <?php 
              $userRecipes = $recipesPerUser[$user['GebruikerID']] ?? [];
              $isCurrentUser = (int)$user['GebruikerID'] === (int)$_SESSION['id'];
            ?>
            <div class="card mb-4">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($user['Naam']) ?> <small class="text-muted">(ID <?= (int)$user['GebruikerID'] ?>)</small></h5>
                <form method="post" class="row g-3 align-items-end">
                  <input type="hidden" name="action" value="update_user">
                  <input type="hidden" name="user_id" value="<?= (int)$user['GebruikerID'] ?>">
                  <div class="col-md-4">
                    <label for="naam-<?= (int)$user['GebruikerID'] ?>" class="form-label">Gebruikersnaam</label>
                    <input type="text" class="form-control" id="naam-<?= (int)$user['GebruikerID'] ?>" name="naam"
                      value="<?= htmlspecialchars($user['Naam']) ?>">
                  </div>
                  <div class="col-md-3">
                    <label for="rol-<?= (int)$user['GebruikerID'] ?>" class="form-label">Rol</label>
                    <select class="form-select" id="rol-<?= (int)$user['GebruikerID'] ?>" name="rol">
                      <option value="Gebruiker" <?= $user['Rol'] === 'Gebruiker' ? 'selected' : '' ?>>Gebruiker</option>
                      <option value="Admin" <?= $user['Rol'] === 'Admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label for="score-<?= (int)$user['GebruikerID'] ?>" class="form-label">Score</label>
                    <input type="number" class="form-control" id="score-<?= (int)$user['GebruikerID'] ?>" name="score"
                      value="<?= (int)$user['Score'] ?>">
                  </div>
                  <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary">Opslaan</button>
                  </div>
                </form>

                <div class="mt-4">
                  <h6>Recepten</h6>
                  <?php if ($userRecipes): ?>
                    <ul class="list-group">
                      <?php foreach ($userRecipes as $recipe): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap gap-2">
                          <span><?= htmlspecialchars($recipe['ReceptNaam']) ?></span>
                          <div class="d-flex align-items-center gap-2">
                            <a href="../recept/?id=<?= (int)$recipe['ReceptID'] ?>" class="btn btn-outline-primary btn-sm">Bekijken</a>
                            <a href="../bewerken/?id=<?= (int)$recipe['ReceptID'] ?>" class="btn btn-outline-warning btn-sm">Bewerken</a>
                            <form method="post" class="m-0" onsubmit="return confirm('Weet je zeker dat je dit recept wilt verwijderen?');">
                              <input type="hidden" name="action" value="delete_recipe">
                              <input type="hidden" name="recept_id" value="<?= (int)$recipe['ReceptID'] ?>">
                              <button type="submit" class="btn btn-outline-danger btn-sm">Verwijderen</button>
                            </form>
                          </div>
                        </li>
                      <?php endforeach; ?>
                    </ul>
                  <?php else: ?>
                    <p class="text-muted mb-0">Geen recepten gevonden.</p>
                  <?php endif; ?>
                </div>

                <?php if (!$isCurrentUser): ?>
                  <form method="post" class="mt-3" onsubmit="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen? Dit verwijdert ook alle gekoppelde recepten.');">
                    <input type="hidden" name="action" value="delete_user">
                    <input type="hidden" name="user_id" value="<?= (int)$user['GebruikerID'] ?>">
                    <button type="submit" class="btn btn-danger">Verwijder gebruiker</button>
                  </form>
                <?php else: ?>
                  <p class="text-muted mt-3 mb-0">Je kunt je eigen account niet verwijderen.</p>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</body>

</html>