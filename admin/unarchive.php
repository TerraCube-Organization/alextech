<?php
session_start();
require_once 'db.php';

// Überprüfen, ob der Benutzer eingeloggt ist
if (!isset($_SESSION['user_id'])) {
    header('Location: login');
    exit;
}

// Überprüfen, ob eine gültige Artikel-ID übergeben wurde
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    header('Location: dash');
    exit;
}

$article_id = (int)$_GET['id'];

try {
    // Artikel in der Datenbank archivieren
    $stmt = $pdo->prepare("UPDATE articles SET archived = 0 WHERE id = :id");
    $stmt->bindParam(':id', $article_id, PDO::PARAM_INT);
    $stmt->execute();

    // Überprüfen, ob ein Artikel archiviert wurde
    if ($stmt->rowCount() > 0) {
        header('Location: dash');
    } else {
        header('Location: dash');
    }
} catch (PDOException $e) {
    // Fehlerbehandlung
    error_log("Fehler beim Archivieren des Artikels: " . $e->getMessage());
    header('Location: dash');
}

exit;