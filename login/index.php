<?php
$validRefer = isset($_SERVER['HTTP_REFERER']) && str_contains($_SERVER['HTTP_REFERER'], 'https://102710.stu.sd-lab.nl/ontkoking/login/');
if ($validRefer || $_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginUsername = trim(strip_tags($_POST['username']));
    $loginPassword = (trim(strip_tags($_POST['password'])));

    if (empty($loginUsername)){
        $error['username'] = 'Een gebruikersnaam is verplicht';
    }
    if (empty($loginPassword)){
        $error['password'] = 'Een wachtwoord is verplicht';
    }
    if (empty($error)){
        $loginPassword = (sha1($loginPassword));
        try {
            require_once '../config.php';
            $stmt = $pdo->prepare("SELECT * FROM Gebruiker WHERE Naam = :username AND Wachtwoord = :password");
            $stmt->execute(['username' => $loginUsername, 'password' => $loginPassword]);
            $user = $stmt->fetch();
            if ($user) {
                session_start();
                $_SESSION['username'] = $user['Naam'];
                $_SESSION['role'] = $user['Rol'];
                header('Location: ../');
            } else {
                $error['login'] = 'Onjuiste gebruikersnaam of wachtwoord';
            }
        }
        catch (PDOException $e) {
            $error['database'] = ['Oeps, er is iets misgegaan met de database', $e->getMessage()];
        }
    } else {
        include_once 'view.php';
    }
}

include_once 'view.php';