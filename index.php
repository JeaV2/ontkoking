<?php

require_once 'config.php';

try {
    $stmt = $pdo->prepare("SELECT Naam, Score FROM Gebruiker ORDER BY Score DESC LIMIT 10;");
    $stmt->execute();
    $topGebruikers = $stmt->fetchAll();
}
catch (Exception $e) {
    $error['database'] = "Er is een fout opgetreden bij het ophalen van de gegevens uit de database. {$e->getMessage()}";
    exit;
}

include_once 'index_view.php';