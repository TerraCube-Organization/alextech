<?php
require_once 'admin/db.php';

function getEntries($sql, $params = []) {
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllEntries() {
    $sql = "SELECT id, title AS name, date, url, description, image, imgalt 
            FROM articles 
            WHERE archived = 0";
    return getEntries($sql);
}

function search($query) {
    $sql = "SELECT id, title AS name, date, url, description, image, imgalt 
            FROM articles
            WHERE archived = 0 AND (
                title LIKE :query OR
                url LIKE :query OR
                description LIKE :query OR
                content LIKE :query OR
                seo_tags LIKE :query
            )";
    $param = "%$query%";
    return getEntries($sql, [':query' => $param]);
}

$searchResults = isset($_GET["q"]) ? search($_GET["q"]) : getAllEntries();
?>
<!doctype html>
<html lang=de>
<head>
<meta name=viewport content="width=device-width,initial-scale=1">
<meta content="Start - Tech mit Alex" property=og:title>
<meta content="Entdecke jetzt viele Spannende inhalte auf meinem Blog!" property=og:description>
<meta content=https://alextech.galler-edv.eu/ property=og:url>
<meta content=#fc7600 data-react-helmet=true name=theme-color>
<meta charset=UTF-8>
<meta name=description content="Start - QQT´s Blog">
<meta name=keywords content="Minecraft Tutorials, Tutorial, Start, Home, Blog">
<meta name=author content="Tech mit Alex">
<title>Homepage - Tech mit Alex</title>
<link rel=stylesheet href=/src/style.css>
</head>
<body>
<div class=header>
<h1>Tech mit Alex</h1>
</div>
<?php require 'includes/navbar.php';?>
<div class=row>
<div class=leftcolumn>
<?php if (isset($searchResults)): ?>
<?php foreach ($searchResults as $result): ?>
<div class=article>
<img src="<?php echo $result["image"]; ?>" alt="<?php echo $result["imgalt"]; ?>" loading="lazy">
<div>
<h2><a href="/artikel/<?php echo $result["url"]; ?>"><?php echo $result["name"]; ?></a></h2>
<h5 class=margin-top-low><?php echo $result["date"]; ?></h5>
<p><?php echo $result["description"]; ?></p></div>
</div>
<?php endforeach; ?>
<?php endif; ?>
</div>
<div class=rightcolumn>
<?php require 'includes/search.php';?>
<?php require 'includes/about-me.php';?>
<?php require 'includes/presented.php';?>
<div class=card>
<h3><i class="fa-solid fa-rss" style=color:#13b3e7></i> Follow Me</h3>
<p>Some text..</p>
</div>
</div>
</div>
<script src="/src/search.js"></script>
<?php require 'includes/footer.php';?>
</body>
</html>
