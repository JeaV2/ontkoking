1<?php
session_start();
require_once '../config.php';

$id = $_GET['id'];

if (!isset($id) || empty($id)) {
    $error = 'Recept niet gevonden.';
    header('Location: ../overzicht/?error=' . urlencode($error));
    exit;
}

try {
    $recept_id = $_GET['id'];
    $query = "SELECT r.*, g.Naam FROM Recept r INNER JOIN Gebruiker g ON r.GebruikerID = g.GebruikerID WHERE ReceptID = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $recept_id]);
    $recept = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$recept) {
        header('Location: ../overzicht/');
        exit;
    }
}
catch (Exception $e) {
    $error = "Er is een fout opgetreden bij het ophalen van het recept. {$e->getMessage()}";
    header('Location: ../overzicht/?error=' . urlencode($error));
    exit;
}

try {
    $query = "SELECT i.* FROM Ingredient i INNER JOIN IngredientReceptKoppel ikr ON i.IngredientID = ikr.IngredientID WHERE ikr.ReceptID = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $recept_id]);
    $ingredienten = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch (Exception $e) {
    $error = "Er is een fout opgetreden bij het ophalen van de ingredienten. {$e->getMessage()}";
    header('Location: ../overzicht/?error=' . urlencode($error));
    exit;
}

include 'view.php';