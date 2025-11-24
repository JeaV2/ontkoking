<?php
session_start();
include_once '../helpers.php';
require_once '../config.php';

if (!isset($_SESSION['id'])) {
    header('Location: ../login/');
    exit;
}

$errors = [];
$receptId = $_GET['id'] ?? $_POST['id'] ?? null;

if (!$receptId) {
    header('Location: ../overzicht/?error=' . urlencode('Ongeldig recept ID.'));
    exit;
}

// Fetch recipe to check ownership and get current data
try {
    $stmt = $pdo->prepare('SELECT * FROM Recept WHERE ReceptID = :id');
    $stmt->execute([':id' => $receptId]);
    $recept = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$recept) {
        header('Location: ../overzicht/?error=' . urlencode('Recept niet gevonden.'));
        exit;
    }

    if ($recept['GebruikerID'] != $_SESSION['id']) {
        header('Location: ../overzicht/?error=' . urlencode('Je hebt geen toestemming om dit recept te bewerken.'));
        exit;
    }
} catch (PDOException $e) {
    header('Location: ../overzicht/?error=' . urlencode('Database fout: ' . $e->getMessage()));
    exit;
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $receptNaam = trim(strip_tags($_POST['receptNaam']));
    $plaatje = trim(strip_tags($_POST['plaatje']));
    $receptInfo = trim(strip_tags($_POST['receptInfo']));
    $ingredienten = $_POST['ingredienten'] ?? [];
    
    foreach ($ingredienten as $ingredient) {
        $hoeveelheid = $ingredient['hoeveelheid'];
        $ingredientNaam = trim($ingredient['ingredient']);
        $grootte = trim($ingredient['grootte']);

        if (empty($ingredientNaam)) {
            $errors['ingredientNaam'] = "Ingredient naam is verplicht.";
        }

        if (!empty($grootte) && !in_array($grootte, getSizes(), true)) {
            $errors['grootte'] = "Ongeldige grootte geselecteerd.";
        }
    }
    $receptInstructies = trim(strip_tags($_POST['receptInstructies']));
    $categorie = trim(strip_tags($_POST['categorie']));

    if (empty($receptNaam)){
        $errors['receptNaam'] = "Recept naam is verplicht.";
    }
    if (empty($plaatje)){
        $errors['plaatje'] = "Plaatje is verplicht.";
    } elseif (!filter_var($plaatje, FILTER_VALIDATE_URL)){
        $errors['plaatje'] = "Ongeldige URL voor plaatje.";
    }
    if (empty($receptInfo)){
        $errors['receptInfo'] = "Recept info is verplicht.";
    }
    if (empty($receptInstructies)){
        $errors['receptInstructies'] = "Recept instructies zijn verplicht.";
    }
    if (empty($categorie)){
        $errors['categorie'] = "Categorie is verplicht.";
    } elseif (!in_array($categorie, getCategories(), true)){
        $errors['categorie'] = "Ongeldige categorie.";
    }

    if (empty($errors)) {
        try {
            $pdo->beginTransaction();

            // Update Recept
            $stmt = $pdo->prepare('UPDATE Recept SET ReceptNaam = :ReceptNaam, Plaatje = :Plaatje, ReceptInfo = :ReceptInfo, Beschrijving = :Beschrijving, Categorie = :Categorie WHERE ReceptID = :ReceptID');
            $stmt->execute([
                ':ReceptNaam' => $receptNaam,
                ':Plaatje' => $plaatje,
                ':ReceptInfo' => $receptInfo,
                ':Beschrijving' => $receptInstructies,
                ':Categorie' => $categorie,
                ':ReceptID' => $receptId
            ]);

            // Update Ingredients: Delete old ones and insert new ones
            // 1. Get old IngredientIDs
            $stmt = $pdo->prepare('SELECT IngredientID FROM IngredientReceptKoppel WHERE ReceptID = :ReceptID');
            $stmt->execute([':ReceptID' => $receptId]);
            $oldIngredientIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

            // 2. Delete from Koppel
            $stmt = $pdo->prepare('DELETE FROM IngredientReceptKoppel WHERE ReceptID = :ReceptID');
            $stmt->execute([':ReceptID' => $receptId]);

            // 3. Delete from Ingredient (optional, but keeps DB clean if ingredients are unique per recipe)
            if (!empty($oldIngredientIds)) {
                // Create placeholders for IN clause
                $placeholders = implode(',', array_fill(0, count($oldIngredientIds), '?'));
                $stmt = $pdo->prepare("DELETE FROM Ingredient WHERE IngredientID IN ($placeholders)");
                $stmt->execute($oldIngredientIds);
            }

            // 4. Insert new Ingredients
            $stmtIngredient = $pdo->prepare('INSERT INTO Ingredient (Ingredient, Aantal, Grootte) VALUES (:Ingredient, :Aantal, :Grootte)');
            $stmtKoppel = $pdo->prepare('INSERT INTO IngredientReceptKoppel (IngredientID, ReceptID) VALUES (:ingredient_id, :recept_id)');

            foreach ($ingredienten as $ingredient) {
                $stmtIngredient->execute([
                    ':Ingredient' => trim($ingredient['ingredient']),
                    ':Aantal' => $ingredient['hoeveelheid'],
                    ':Grootte' => trim($ingredient['grootte']),
                ]);
                $ingredientId = $pdo->lastInsertId();
                
                $stmtKoppel->execute([
                    ':ingredient_id' => $ingredientId,
                    ':recept_id' => $receptId,
                ]);
            }

            $pdo->commit();
            header("Location: ../recept/?id=" . $receptId . "&info=" . urlencode("Recept succesvol bijgewerkt."));
            exit;

        } catch (PDOException $e) {
            $pdo->rollBack();
            $errors['database'] = $e->getMessage();
        }
    }
} else {
    // Pre-fill form data from DB
    $receptNaam = $recept['ReceptNaam'];
    $plaatje = $recept['Plaatje'];
    $receptInfo = $recept['ReceptInfo'];
    $receptInstructies = $recept['Beschrijving'];
    $categorie = $recept['Categorie'];

    // Fetch ingredients
    $stmt = $pdo->prepare('SELECT i.Ingredient as ingredient, i.Aantal as hoeveelheid, i.Grootte as grootte FROM Ingredient i JOIN IngredientReceptKoppel k ON i.IngredientID = k.IngredientID WHERE k.ReceptID = :id');
    $stmt->execute([':id' => $receptId]);
    $ingredienten = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

include_once 'view.php';
