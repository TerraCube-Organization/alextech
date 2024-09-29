<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];
    $content = $_POST['content'];
    $seo_tags = $_POST['seo_tags'];
    $read_time = $_POST['read_time'];
    $date = date('Y-m-d');
    $url = strtolower(str_replace(' ', '-', $title));
    $description = $_POST['description'];
    $image = $_POST['image'];
    $imgalt = $_POST['imgalt'];

    $stmt = $pdo->prepare("INSERT INTO articles (title, subtitle, content, seo_tags, read_time, date, url, description, image, imgalt) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $subtitle, $content, $seo_tags, $read_time, $date, $url, $description, $image, $imgalt]);

    header('Location: dash');
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikel erstellen</title>
    <link rel="stylesheet" href="/src/style.css">
	<link rel="stylesheet" href="admin.css">
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
		hidden_input: false,
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
    <h1>Artikel erstellen</h1>
    <form method="post">
        <input type="text" name="title" placeholder="Titel" required>
        <input type="text" name="subtitle" placeholder="Untertitel" required>
        <textarea name="content" id="content" placeholder="Inhalt"></textarea>
        <input type="text" name="seo_tags" placeholder="SEO-Tags">
        <input type="text" name="read_time" placeholder="Lesezeit">
		<input type="text" name="description" placeholder="Beschreibung" required>
        <input type="text" name="image" placeholder="Bild-URL">
        <input type="text" name="imgalt" placeholder="Bild-Alternativtext">
        <button type="submit">📝 Artikel erstellen</button>
    </form>
</body>
</html>