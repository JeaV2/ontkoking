<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home pagina</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>
  <link rel="stylesheet" href="./css/style.css">
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
          <?php if(isset($_SESSION['id']) || isset($_SESSION['username'])|| isset($_SESSION['role'])): ?>
            <li class="nav-item">
              <a class="nav-link" href="./loguit/">Uitloggen</a>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="./login/">Inloggen</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="./aanmelden/">Aanmelden</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

      <div class="row">
      <div class="col-md-2 d-flex">
        <div class="content flex-fill">
            <h1>Leaderboard</h1>
            <?php if (isset($error['database'])): ?>
              <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error['database']); ?>
              </div>
            <?php endif; ?>

            <!-- numbered leaderboard -->
            <ol id="leaderboard" class="list-group list-group-numbered">
              <?php foreach($topUsers as $user): ?>
                <li class="list-group-item d-flex justify-content-between align-items-start">
                  <div class="ms-2 me-auto"><?= $user['Naam']; ?></div>
                  <span class="badge bg-primary rounded-pill"><?= $user['Score']; ?></span>
                </li>
              <?php endforeach; ?>
            </ol>

        </div>
      </div>

        <div class="col-md-10 d-flex">
        <div class="content flex-fill">
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque non a incidunt veniam ex temporibus tempora magni. Ullam veritatis voluptates itaque, magni aut aperiam molestiae debitis error, molestias, corrupti nam!</p>
        </div>
      </div>
</body>
</html>