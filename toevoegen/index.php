<?php
session_start();
include_once '../session.php';
include_once '../helpers.php';
require_once '../config.php';

$errors = [];

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
            $queries = [
                $pdo->prepare('INSERT INTO Recept (GebruikerID, ReceptNaam, Plaatje, ReceptInfo, Beschrijving, Categorie) 
                 VALUES (:GebruikerID, :ReceptNaam, :Plaatje, :ReceptInfo, :Beschrijving, :Categorie)'),
                $pdo->prepare('INSERT INTO Ingredient (Ingredient, Aantal, Grootte) 
                 VALUES (:Ingredient, :Aantal, :Grootte)'),
                $pdo->prepare('INSERT INTO IngredientReceptKoppel (IngredientID, ReceptID) 
                 VALUES (:ingredient_id, :recept_id)'),
            ];
            // Insert Recept
            $queries[0]->execute([
                ':GebruikerID' => $_SESSION['id'],
                ':ReceptNaam' => $receptNaam,
                ':Plaatje' => $plaatje,
                ':ReceptInfo' => $receptInfo,
                ':Beschrijving' => $receptInstructies,
                ':Categorie' => $categorie,
            ]);
            $receptId = $pdo->lastInsertId();
            // Insert Ingredients
            foreach ($ingredienten as $ingredient) {
                $queries[1]->execute([
                    ':Ingredient' => trim($ingredient['ingredient']),
                    ':Aantal' => $ingredient['hoeveelheid'],
                    ':Grootte' => trim($ingredient['grootte']),
                ]);
                $ingredientId = $pdo->lastInsertId();
                // Insert into Koppel table
                $queries[2]->execute([
                    ':ingredient_id' => $ingredientId,
                    ':recept_id' => $receptId,
                ]);
            }
        }
        catch (PDOException $e) {
            header("Location: ../?error=". urlencode($e->getMessage()));
            exit;
        }
        finally {
            header("Location: ../?info=". urlencode("Recept succesvol toegevoegd."));
            exit;
        }
    }
}

include_once 'view.php';