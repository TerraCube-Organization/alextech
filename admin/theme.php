<?php
session_start();
require_once 'db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Überprüfen, ob der Benutzer eingeloggt und ein Admin ist
if (!isset($_SESSION['user_id'])) {
    header('Location: login');
    exit;
}

// Define the path to the CSS file
$css_file = '/var/www/vhosts/galler-edv.eu/tech-mit-alex.Galler-EDV.eu/src/style.css';

// Function to read current color settings from CSS file
function getCurrentColors($css_file) {
    $css_content = file_get_contents($css_file);
    if ($css_content === false) {
        throw new Exception("Unable to read CSS file: $css_file");
    }

    $colors = [];
    $pattern = '/--(\w+)-color:\s*(#[A-Fa-f0-9]+);/';
    preg_match_all($pattern, $css_content, $matches, PREG_SET_ORDER);

    foreach ($matches as $match) {
        $colors[$match[1]] = $match[2];
    }

    return $colors;
}

// Function to update colors in CSS file
function updateColors($css_file, $colors) {
    $css_content = file_get_contents($css_file);
    if ($css_content === false) {
        throw new Exception("Unable to read CSS file: $css_file");
    }

    foreach ($colors as $name => $value) {
        $pattern = '/--' . preg_quote($name, '/') . '-color:\s*#[A-Fa-f0-9]+;/';
        $replacement = "--$name-color: $value;";
        $css_content = preg_replace($pattern, $replacement, $css_content);
    }

    if (file_put_contents($css_file, $css_content) === false) {
        throw new Exception("Unable to write to CSS file: $css_file");
    }
}

$message = '';
$error = '';

try {
    $current_colors = getCurrentColors($css_file);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_colors = [
            'primary' => filter_input(INPUT_POST, 'primary_color', FILTER_SANITIZE_STRING) ?: $current_colors['primary'],
            'secondary' => filter_input(INPUT_POST, 'secondary_color', FILTER_SANITIZE_STRING) ?: $current_colors['secondary'],
            'background' => filter_input(INPUT_POST, 'background_color', FILTER_SANITIZE_STRING) ?: $current_colors['background'],
            'text' => filter_input(INPUT_POST, 'text_color', FILTER_SANITIZE_STRING) ?: $current_colors['text'],
            'card' => filter_input(INPUT_POST, 'card_background', FILTER_SANITIZE_STRING) ?: $current_colors['card']
        ];

        updateColors($css_file, $new_colors);
        $current_colors = $new_colors;
        $message = "Theme successfully updated!";
    }
} catch (Exception $e) {
    $error = "An error occurred: " . $e->getMessage();
    error_log($error);
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theme Settings</title>
    <link rel="stylesheet" href="/src/style.css">
    <link rel="stylesheet" href="/src/admin.css">
</head>
<body>
    <h1>Theme Settings</h1>
    
    <?php if ($message): ?>
        <p class="success"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    
    <form method="post">
        <?php foreach ($current_colors as $name => $value): ?>
            <label for="<?php echo $name; ?>_color"><?php echo ucfirst($name); ?> Color:</label>
            <input type="color" id="<?php echo $name; ?>_color" name="<?php echo $name; ?>_color" value="<?php echo htmlspecialchars($value); ?>"><br>
        <?php endforeach; ?>
        
        <input type="submit" value="Update Theme">
    </form>
</body>
</html>