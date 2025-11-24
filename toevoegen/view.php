<!DOCTYPE html>
<html lang="en">

<head <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/toevoegen.css">
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
              <a class="nav-link active" aria-current="page" href="./">Recept Toevoegen</a>
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
        <div class="row">
            <div class="col-md-12">
                <div class="content">
                    <div class="alert alert-light form-container">
                        <h1>Voeg jouw eigen recept toe!</h1>
                        <form action="./" method="post">
                            <label for="receptNaam">Geef je recept een naam:</label>
                            <br>
                            <?php if (!empty($errors['receptNaam'])): ?>
                                <div class="badge text-bg-danger"><?= htmlspecialchars($errors['receptNaam']) ?></div>
<br>
                            <?php endif; ?>
                            <input type="text" id="receptNaam" name="receptNaam"
                                value="<?= htmlspecialchars($receptNaam ?? ''); ?>">
                            <br>

                            <!-- 
                                Plaatje is voor nu een link.
                                Misschien later eigen upload functionaliteit toevoegen?
                            -->
                            <label for="plaatje">Voeg een link naar een plaatje toe:</label>
                            <br>
                            <?php if (!empty($errors['plaatje'])): ?>
                                <div class="badge text-bg-danger"><?= htmlspecialchars($errors['plaatje']) ?></div>
<br>
                            <?php endif; ?>
                            <input type="text" id="plaatje" name="plaatje"
                                value="<?= htmlspecialchars($plaatje ?? ''); ?>">
                            <br>

                            
                            <label for="receptInfo">Voeg een korte beschrijving toe:</label>
                            <br>
                            <?php if (!empty($errors['receptInfo'])): ?>
                                <div class="badge text-bg-danger"><?= htmlspecialchars($errors['receptInfo']) ?></div>
<br>
                            <?php endif; ?>
                            <input type="text" id="receptInfo" name="receptInfo"
                                value="<?= htmlspecialchars($receptInfo ?? ''); ?>"
                                placeholder="bv. Een makkelijk te maken pasta met tomatensaus.">
                            <br>
                            
                            <span id="ingredienten">
                                <label>Voeg ingredienten toe:</label>
                                <br>
                                <?php if (!empty($errors['ingredientNaam'])): ?>
                                    <div class="badge text-bg-danger"><?= htmlspecialchars($errors['ingredientNaam']) ?></div>
                                <?php endif; ?>
                                <div id="ingredientenContainer">
                                    <?php 
                                    // Use submitted ingredients or default to one empty row
                                    $currentIngredients = !empty($ingredienten) ? $ingredienten : [['hoeveelheid' => '', 'ingredient' => '', 'grootte' => '']];
                                    
                                    foreach ($currentIngredients as $index => $ing): 
                                    ?>
                                        <div class="ingredient-row">
                                            <input type="number" name="ingredienten[<?= $index ?>][hoeveelheid]" 
                                                   value="<?= htmlspecialchars($ing['hoeveelheid'] ?? '') ?>" placeholder="Aantal">
                                            
                                            <input type="text" name="ingredienten[<?= $index ?>][ingredient]" 
                                                   value="<?= htmlspecialchars($ing['ingredient'] ?? '') ?>" placeholder="Ingredient">
                                            
                                            <select name="ingredienten[<?= $index ?>][grootte]">
                                                <option value="">Geen Grootte</option>
                                                <?php foreach (getSizes() as $size): ?>
                                                    <option value="<?= htmlspecialchars($size) ?>" 
                                                        <?= (isset($ing['grootte']) && $ing['grootte'] === $size) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($size) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>

                                            <?php if ($index === 0): ?>
                                                <button type="button" class="btn btn-success" onclick="addIngredientField()">+</button>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">-</button>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <!-- Inline script heb ik helaas nodig, want ik kan geen php doen in een externe JS bestand -->
                                <script>
                                    let ingredientCount = <?= count($currentIngredients) ?>;

                                    function addIngredientField() {
                                        const container = document.getElementById('ingredientenContainer');
                                        const newRow = document.createElement('div');
                                        newRow.className = 'ingredient-row';
                                        newRow.innerHTML = `
                                            <input type="number" name="ingredienten[${ingredientCount}][hoeveelheid]" placeholder="Aantal">
                                            <input type="text" name="ingredienten[${ingredientCount}][ingredient]" placeholder="Ingredient">
                                            <select name="ingredienten[${ingredientCount}][grootte]">
                                                <option value="">Geen Grootte</option>
                                                <?php foreach (getSizes() as $size): ?>
                                                    <option value="<?= htmlspecialchars($size); ?>"><?= htmlspecialchars($size); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">-</button>
                                        `;
                                        container.appendChild(newRow);
                                        ingredientCount++;
                                    }
                                </script>
                            </span>

                            <label for="receptInstructies">Voeg de instructies toe:</label>
                            <br>
                            <?php if (!empty($errors['receptInstructies'])): ?>
                                <div class="badge text-bg-danger"><?= htmlspecialchars($errors['receptInstructies']) ?></div>
<br>
                            <?php endif; ?>
                            <textarea id="receptInstructies" name="receptInstructies" rows="6" cols="50"
                                placeholder="Stap 1: Doe dit. &#10;Stap 2: Doe dat."><?= htmlspecialchars($receptInstructies ?? '') ?></textarea>
                            <br>

                            <label for="categorie">Selecteer een categorie voor het gerecht.</label>
                            <br>
                            <?php if (!empty($errors['categorie'])): ?>
                                <div class="badge text-bg-danger"><?= htmlspecialchars($errors['categorie']) ?></div>
<br>
                            <?php endif; ?>
                            <select name="categorie" id="categorie">
                                <?php foreach (getCategories() as $category): ?>
                                    <option value="<?= htmlspecialchars($category); ?>"
                                        <?= (isset($categorie) && $categorie === $category) ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($category); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <br>
                            <input type="submit" class="btn btn-danger" value="Upload Recept">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>