<?php
session_start();
require_once 'db.php';

// Überprüfen, ob der Benutzer eingeloggt ist
if (!isset($_SESSION['user_id'])) {
    header('Location: login');
    exit;
}

// Überprüfen, ob eine Artikel-ID übergeben wurde
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: dash');
    exit;
}

$article_id = $_GET['id'];

try {
    // Artikel aus der Datenbank löschen
    $stmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
    $stmt->execute([$article_id]);

    // Überprüfen, ob ein Artikel gelöscht wurde
    if ($stmt->rowCount() > 0) {
        header('Location: dash');
    } else {
        header('Location: dash');
    }
}
exit;