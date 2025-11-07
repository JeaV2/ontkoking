<?php
$validRefer = isset($_SERVER['HTTP_REFERER']) && str_contains($_SERVER['HTTP_REFERER'], 'https://102710.stu.sd-lab.nl/ontkoking/aanmelden/');
if ($validRefer || $_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginUsername = trim(strip_tags($_POST['username']));
    $loginPassword = trim(strip_tags($_POST['password']));
    $passwordConfirm = trim(strip_tags($_POST['password_confirm']));
    $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{10,}$/';


    if (empty($loginUsername)) {
        $error['username'] = 'Een gebruikersnaam is verplicht';
    }
    if (empty($loginPassword)) {
        $error['password'] = 'Een wachtwoord is verplicht';
    }
    if ($loginPassword !== $passwordConfirm) {
        $error['password'] = 'Wachtwoorden komen niet overeen';
    }
    if (!preg_match($passwordRegex, $loginPassword)) {
        $error['password'] = 'Wachtwoord moet minimaal 10 tekens bevatten, waaronder een hoofdletter, een kleine letter, een cijfer en een speciaal teken.';
    }
    if (empty($error)) {
        $loginPassword = (sha1($loginPassword));
        try {
            require_once '../config.php';
            $stmt = $pdo->prepare("SELECT * FROM Gebruiker WHERE Naam = :username");
            $stmt->execute(['username' => $loginUsername]);
            $user = $stmt->fetch();
            if ($user) {
                $error['login'] = 'Gebruiker bestaat al, ga naar de <a href="../login">log in</a> pagina, of gebruik een andere gebruikersnaam.';
            } else {
                try {
                    $stmt = $pdo->prepare("INSERT INTO Gebruiker (Naam, Wachtwoord, Rol) VALUES (:username, :password, 'Gebruiker')");
                    $stmt->execute(['username' => $loginUsername, 'password' => $loginPassword]);
                    try {
                        $stmt = $pdo->prepare("SELECT * FROM Gebruiker WHERE Naam = :username");
                        $stmt->execute(['username' => $loginUsername]);
                        $user = $stmt->fetch();
                        if ($user) {
                            session_start();
                            $_SESSION['id'] = $user['GebruikerID'];
                            $_SESSION['username'] = $user['Naam'];
                            $_SESSION['role'] = $user['Rol'];
                            header('Location: ../');
                        }
                    } catch (PDOException $e) {
                        $error['database'] = ['Account wel aangemaakt maar, nog niet ingelogd, login in handmatig op de <a href="../login">log in</a> pagina.', $e->getMessage()];
                    }
                } catch (PDOException $e) {
                    $error['database'] = ['Oeps, er is iets misgegaan met de database', $e->getMessage()];
                }
            }
        } catch (PDOException $e) {
            $error['database'] = ['Oeps, er is iets misgegaan met de database', $e->getMessage()];
        }
    } else {
        include_once 'view.php';
    }
}

include_once 'view.php';