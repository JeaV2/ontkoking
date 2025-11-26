<?php
session_start();
header('Content-Type: application/json');

require_once '../config.php';

$gebruikerID = $_SESSION['id'] ?? null;
if (!$gebruikerID) {
    echo json_encode(['success' => false, 'error' => 'Je moet ingelogd zijn.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Ongeldige methode.']);
    exit;
}

$payload = json_decode(file_get_contents('php://input'), true);
$receptId = isset($payload['recept_id']) ? (int)$payload['recept_id'] : 0;

if ($receptId <= 0) {
    echo json_encode(['success' => false, 'error' => 'Ongeldig recept.']);
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT 1 FROM GekooktGebruiker WHERE ReceptID = :recept AND GebruikerID = :gebruiker');
    $stmt->execute([':recept' => $receptId, ':gebruiker' => $gebruikerID]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'error' => 'Je hebt dit recept al gemarkeerd.']);
        exit;
    }

    $pdo->beginTransaction();

    $stmt = $pdo->prepare('INSERT INTO GekooktGebruiker (ReceptID, GebruikerID) VALUES (:recept, :gebruiker)');
    $stmt->execute([':recept' => $receptId, ':gebruiker' => $gebruikerID]);

    $stmt = $pdo->prepare('UPDATE Gebruiker SET Score = Score + 1 WHERE GebruikerID = :gebruiker');
    $stmt->execute([':gebruiker' => $gebruikerID]);

    $pdo->commit();

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'error' => 'Kon score niet bijwerken.']);
}