<?php
// https://stackoverflow.com/questions/2350052/how-can-i-get-enum-possible-values-in-a-mysql-database
function getCategories() {
    global $pdo;
    $stmt = $pdo->prepare('SHOW COLUMNS FROM Recept WHERE Field = "Categorie"');
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $type = $row['Type'];
    preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
    $enum = explode("','", $matches[1]);
    return $enum;
}

function getSizes(){
    global $pdo;
    $stmt = $pdo->prepare('SHOW COLUMNS FROM Ingredient WHERE Field = "Grootte"');
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $type = $row['Type'];
    preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
    $enum = explode("','", $matches[1]);
    return $enum;
}