<?php 

$info = $_GET['info'] ?? null;
$error = $_GET['error'] ?? null;

session_start();
require_once '../config.php';

try {
    $stmt = $pdo->prepare('SELECT r.ReceptID, r.Plaatje, r.ReceptInfo, r.ReceptNaam, r.Categorie, g.Naam FROM Recept r JOIN Gebruiker g ON r.GebruikerID = g.GebruikerID WHERE g.GebruikerID = r.GebruikerID'); 
    $stmt->execute();
    $recepten = $stmt->fetchAll();
}
catch (Exception $e) {
    $error['database'] = "Er is een fout opgetreden bij het ophalen van de gegevens uit de database. {$e->getMessage()}";
}

include_once 'view.php';