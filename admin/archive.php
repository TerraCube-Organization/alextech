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
    header('Location: dash?error=invalid_id');
    exit;
}

$article_id = (int)$_GET['id'];

try {
    // Artikel in der Datenbank archivieren
    $stmt = $pdo->prepare("UPDATE articles SET archived = 1 WHERE id = :id");
    $stmt->bindParam(':id', $article_id, PDO::PARAM_INT);
    $stmt->execute();

    // Überprüfen, ob ein Artikel archiviert wurde
    if ($stmt->rowCount() > 0) {
        header('Location: dash?message=article_archived');
    } else {
        header('Location: dash?error=article_not_found');
    }
} catch (PDOException $e) {
    // Fehlerbehandlung
    error_log("Fehler beim Archivieren des Artikels: " . $e->getMessage());
    header('Location: dash?error=database_error');
}

exit;