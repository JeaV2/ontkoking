<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Admin") {
    header("Location: ../overzicht.php?error=".urlencode("Toegang geweigerd. U heeft geen toestemming om deze pagina te bekijken."));
    exit();
}