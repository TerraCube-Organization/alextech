<?php
require_once 'admin/db.php';

// Einzelner Artikel basierend auf URL
$url = $_GET['url'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM articles WHERE url = ? AND archived = FALSE");
$stmt->execute([$url]);
$article = $stmt->fetch();

if ($url && !$article) {
    header("HTTP/1.0 404 Not Found");
    exit("Artikel nicht gefunden");
}

// Liste aller nicht archivierten Artikel
$stmtAll = $pdo->query("SELECT id, title, date, url FROM articles WHERE archived = FALSE ORDER BY date DESC");
$articles = $stmtAll->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang=de>
<head>
<meta name=viewport content="width=device-width,initial-scale=1">
<meta content="<?php echo $article['title']; ?> - Tech mit Alex" property=og:title>
<meta content="<?php echo $article['subtitle']; ?>" property=og:description>
<meta content="https://alextech.galler-edv.eu/artikel/<?php echo $article['url']; ?>" property=og:url>
<meta content=#fc7600 data-react-helmet=true name=theme-color>
<meta charset=UTF-8>
<meta name=description content="<?php echo $article['title']; ?> - Tech mit Alex">
<meta name=keywords content="<?php echo $article['seo_tags']; ?>">
<meta name=author content="Tech mit Alex">
<title><?php echo $article['title']; ?> - Tech mit Alex</title>
<link rel=stylesheet href=/src/style.css>
<script src=/src/hash.js></script>
</head>
<body>
<div class=header>
<h1><?php echo $article['title']; ?></h1>
<p><?php echo $article['subtitle']; ?></p>
</div>
<?php require 'includes/navbar.php';?>
<div class=row>
<div class=leftcolumn>
<div class=card>
<h5>⌛ <?php echo $article['read_time']; ?> Lesezeit, <?php echo $article['date']; ?></h5>
<?php echo $article['content']; ?>
</div>
</div>
<div class=rightcolumn>
<?php require 'includes/search.php';?>
<?php require 'includes/about-me.php';?>
<?php require 'includes/presented.php';?>
<div class=card>
<h3>Follow Me</h3>
<p>Some text..</p>
</div>
</div>
</div>
<?php require 'includes/footer.php';?>
</body>
</html>