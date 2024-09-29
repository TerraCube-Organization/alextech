<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];
    $content = $_POST['content'];
    $seo_tags = $_POST['seo_tags'];
    $read_time = $_POST['read_time'];
    $url = strtolower(str_replace(' ', '-', $title));

    $stmt = $pdo->prepare("UPDATE articles SET title = ?, subtitle = ?, content = ?, seo_tags = ?, read_time = ?, url = ? WHERE id = ?");
    $stmt->execute([$title, $subtitle, $content, $seo_tags, $read_time, $url, $id]);

    header('Location: dash');
    exit;
}

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->execute([$id]);
    $article = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikel bearbeiten</title>
    <link rel="stylesheet" href="/src/style.css">
	<link rel="stylesheet" href="/src/admin.css">
    <script src="/src/tinymce/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#content',
        height: 500,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount', 'emoticons',
            'codesample', 'quickbars'
        ],
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | ' +
                 'alignleft aligncenter alignright alignjustify | outdent indent | ' +
                 'numlist bullist | forecolor backcolor removeformat | ' +
                 'insertfile image media link codesample | emoticons charmap | ' +
                 'fullscreen code | table | help',
        menubar: 'file edit view insert format tools table help',
        menu: {
            file: { title: 'File', items: 'newdocument restoredraft | preview | export print | deleteallconversations' },
            edit: { title: 'Edit', items: 'undo redo | cut copy paste pastetext | selectall | searchreplace' },
            view: { title: 'View', items: 'code | visualaid visualchars visualblocks | spellchecker | preview fullscreen' },
            insert: { title: 'Insert', items: 'image link media addcomment pageembed template codesample inserttable | charmap emoticons hr | pagebreak nonbreaking anchor tableofcontents | insertdatetime' },
            format: { title: 'Format', items: 'bold italic underline strikethrough superscript subscript codeformat | styles blocks fontfamily fontsize align lineheight | forecolor backcolor | language | removeformat' },
            tools: { title: 'Tools', items: 'spellchecker spellcheckerlanguage | a11ycheck code wordcount' },
            table: { title: 'Table', items: 'inserttable | cell row column | advtablesort | tableprops deletetable' }
        },
        content_style: 'body { font-family:Arial,sans-serif; font-size:16px }',
        quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote',
        quickbars_insert_toolbar: 'quickimage quicktable',
        contextmenu: 'link image table',
        font_family_formats: 'Arial=arial,helvetica,sans-serif; Courier New=courier new,courier,monospace; AkrutiKndPadmini=Akpdmi-n',
        block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6; Preformatted=pre',
        images_upload_url: 'postAcceptor.php',
        images_upload_handler: function (blobInfo, success, failure) {
            setTimeout(function () {
                // Hier würden Sie normalerweise den Bildupload-Code implementieren
                success('http://moxiecode.cachefly.net/tinymce/v9/images/logo.png');
            }, 2000);
        }
    });
</script>
</head>
<body>
    <h1>Artikel bearbeiten</h1>
    <?php if ($article): ?>
        <form method="post">
            <input type="hidden" name="id" value="<?php echo $article['id']; ?>">
            <input type="text" name="title" placeholder="Titel" value="<?php echo $article['title']; ?>" required>
            <input type="text" name="subtitle" placeholder="Untertitel" value="<?php echo $article['subtitle']; ?>" required>
            <textarea name="content" id="content" placeholder="Inhalt" required><?php echo $article['content']; ?></textarea>
            <input type="text" name="seo_tags" placeholder="SEO-Tags" value="<?php echo $article['seo_tags']; ?>">
            <input type="text" name="read_time" placeholder="Lesezeit" value="<?php echo $article['read_time']; ?>">
            <button type="submit">✏️ Artikel aktualisieren</button>
        </form>
    <?php else: ?>
        <p>Artikel nicht gefunden.</p>
    <?php endif; ?>
</body>
</html>