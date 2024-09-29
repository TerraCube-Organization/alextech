<?php
session_start();
require_once 'db.php';

// Überprüfen, ob der Benutzer eingeloggt ist
if (!isset($_SESSION['user_id'])) {
    header('Location: login');
    exit;
}

// Alle Artikel aus der Datenbank abrufen
$stmt = $pdo->query("SELECT id, title, date, url, archived FROM articles ORDER BY date DESC");
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="/src/style.css">
	<link rel="stylesheet" href="/src/admin.css">
</head>
<body>
    <h1>Admin Dashboard</h1>
    <a href="create" class="button">📝 Neuen Artikel erstellen</a>
    <h2>Artikelübersicht</h2>
    <table>
        <thead>
            <tr>
                <th>Titel</th>
                <th>Datum</th>
                <th>URL</th>
                <th>Aktionen</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($articles as $article): ?>
            <tr>
                <td><?php echo htmlspecialchars($article['title']); ?></td>
                <td><?php echo $article['date']; ?></td>
                <td><?php echo htmlspecialchars($article['url']); ?></td>
                <td>
					<?php if (!$article['archived']): ?>
                    <a href="edit?id=<?php echo $article['id']; ?>" class="button edit-button">✏️ Bearbeiten</a>
<a href="archive?id=<?php echo $article['id']; ?>" class="button archive-button" onclick="return confirm('Sind Sie sicher, dass Sie diesen Artikel archivieren möchten?');">📦 Archivieren</a>
        <?php else: ?>
            <a href="unarchive?id=<?php echo $article['id']; ?>" class="button unarchive-button">🔄️ Wiederherstellen</a>
        <?php endif; ?>
                    <a href="delete?id=<?php echo $article['id']; ?>" class="button delete-button" onclick="return confirm('Sind Sie sicher, dass Sie diesen Artikel löschen möchten?')">🗑️ Löschen</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>