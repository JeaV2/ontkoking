<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header('Location: ../overzicht/?error=' . urlencode('Toegang geweigerd.'));
    exit;
}

$status = ['success' => null, 'error' => null];
$users = [];
$recipesPerUser = [];

function buildPlaceholders(int $count): string {
    return implode(',', array_fill(0, $count, '?'));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;

    if ($action === 'update_user') {
        $userId = (int)($_POST['user_id'] ?? 0);
        $naam = trim(strip_tags($_POST['naam'] ?? ''));
        $rol = trim(strip_tags($_POST['rol'] ?? ''));
        $scoreInput = trim((string)($_POST['score'] ?? '0'));
        $score = filter_var($scoreInput, FILTER_VALIDATE_INT);
        if ($score === false) {
            $score = 0;
        }

        if ($userId <= 0 || $naam === '') {
            $status['error'] = 'Gebruikersnaam mag niet leeg zijn.';
        } elseif (!in_array($rol, ['Admin', 'Gebruiker'], true)) {
            $status['error'] = 'Ongeldige rol geselecteerd.';
        } else {
            try {
                $stmt = $pdo->prepare('UPDATE Gebruiker SET Naam = :naam, Rol = :rol, Score = :score WHERE GebruikerID = :id');
                $stmt->execute([
                    ':naam' => $naam,
                    ':rol' => $rol,
                    ':score' => $score,
                    ':id' => $userId,
                ]);
                $status['success'] = 'Gebruiker succesvol bijgewerkt.';
            } catch (PDOException $e) {
                $status['error'] = 'Bijwerken mislukt: ' . $e->getMessage();
            }
        }
    } elseif ($action === 'delete_user') {
        $userId = (int)($_POST['user_id'] ?? 0);

        if ($userId <= 0) {
            $status['error'] = 'Ongeldige gebruiker geselecteerd.';
        } elseif ($userId === (int)$_SESSION['id']) {
            $status['error'] = 'Je kunt je eigen account niet verwijderen.';
        } else {
            try {
                $pdo->beginTransaction();

                $stmt = $pdo->prepare('SELECT ReceptID FROM Recept WHERE GebruikerID = :id');
                $stmt->execute([':id' => $userId]);
                $recipeIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

                if (!empty($recipeIds)) {
                    $recipePlaceholders = buildPlaceholders(count($recipeIds));

                    $stmt = $pdo->prepare("SELECT IngredientID FROM IngredientReceptKoppel WHERE ReceptID IN ($recipePlaceholders)");
                    $stmt->execute($recipeIds);
                    $ingredientIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

                    $stmt = $pdo->prepare("DELETE FROM IngredientReceptKoppel WHERE ReceptID IN ($recipePlaceholders)");
                    $stmt->execute($recipeIds);

                    if (!empty($ingredientIds)) {
                        $ingredientPlaceholders = buildPlaceholders(count($ingredientIds));
                        $stmt = $pdo->prepare("DELETE FROM Ingredient WHERE IngredientID IN ($ingredientPlaceholders)");
                        $stmt->execute($ingredientIds);
                    }

                    $stmt = $pdo->prepare("DELETE FROM Recept WHERE ReceptID IN ($recipePlaceholders)");
                    $stmt->execute($recipeIds);
                }

                $stmt = $pdo->prepare('DELETE FROM Gebruiker WHERE GebruikerID = :id');
                $stmt->execute([':id' => $userId]);

                $pdo->commit();
                $status['success'] = 'Gebruiker en gekoppelde recepten verwijderd.';
            } catch (PDOException $e) {
                $pdo->rollBack();
                $status['error'] = 'Verwijderen mislukt: ' . $e->getMessage();
            }
        }
    }
}

try {
    $stmtUsers = $pdo->query('SELECT GebruikerID, Naam, Rol, Score FROM Gebruiker ORDER BY Naam');
    $users = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);

    $stmtRecipes = $pdo->query('SELECT ReceptID, ReceptNaam, GebruikerID FROM Recept ORDER BY ReceptNaam');
    foreach ($stmtRecipes as $recipe) {
        $recipesPerUser[$recipe['GebruikerID']][] = $recipe;
    }
} catch (PDOException $e) {
    $status['error'] = 'Gegevens konden niet worden opgehaald: ' . $e->getMessage();
}

include_once 'view.php';