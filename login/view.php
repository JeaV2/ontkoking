<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Bij Stop De Ontkoking</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/login.css">
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
            <a class="nav-link" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Recepten</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Inloggen</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!--! Change the amount of rows and columns as needed for the page layout! -->
  <div class="container-fluid no-margin">
    <div class="row">
      <div class="col-md-12 ">
        <div class="content flex-fill d-flex justify-content-center align-items-center">
          <div class="alert alert-light m-3 w-75">
            <form action="./" method="post" class="form">
                <h1 class="text-center">Login om zelf recepten te uploaden!</h1>
                <?php if (!empty($error['login'])): ?>
                    <div class="alert alert-danger"><?= $error['login'] ?></div>
                <?php endif; ?>
                <?php if (!empty($error['database'])): ?>
                    <div class="alert alert-danger"><?= $error['database'] ?></div>
                <?php endif; ?>
              <div class="mb-3">
                <label for="username" class="form-label">Gebruikersnaam</label>
                  <?php if (!empty($error['username'])): ?>
                      <div class="alert alert-danger"><?= $error['username'] ?></div>
                  <?php endif; ?>
                <input type="text" class="form-control" id="username" name="username" value="<?= $loginUsername ?? '' ?>">
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Wachtwoord</label>
                  <?php if (!empty($error['username'])): ?>
                      <div class="alert alert-danger"><?= $error['username'] ?></div>
                  <?php endif; ?>
                <input type="password" class="form-control" id="password" name="password">
              </div>
              <button type="submit" class="btn btn-primary">Inloggen</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>