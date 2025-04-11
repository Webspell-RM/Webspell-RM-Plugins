<?php
/**
 * Theme Editor Plugin mit Live-Vorschau und Backup-Funktion
 *
 * @version webspell-rm
 * @license GNU GENERAL PUBLIC LICENSE
 */

$_language->readModule('theme_editor', false, true);

$ergebnis = safe_query("SELECT * FROM " . PREFIX . "navigation_dashboard_links WHERE modulname='ac_database'");
while ($db = mysqli_fetch_array($ergebnis)) {
    $accesslevel = 'is' . $db['accesslevel'] . 'admin';

    if (!$accesslevel($userID) || mb_substr(basename($_SERVER['REQUEST_URI']), 0, 15) != "admincenter.php") {
        die($plugin_language['access_denied']);
    }
}

// Theme-Daten abrufen
$result = safe_query("SELECT * FROM ".PREFIX."theme_settings WHERE id=1");
if(mysqli_num_rows($result) > 0) {
    $settings = mysqli_fetch_assoc($result);
} else {
    $settings = ['primary_color' => '#ffffff', 'secondary_color' => '#000000', 'font_family' => 'Arial, sans-serif', 'font_size' => '16px', 'text_color' => '#000000', 'background_color' => '#ffffff', 'theme_name' => 'Default'];
}

$primary_color = $settings['primary_color'];
$secondary_color = $settings['secondary_color'];
$font_family = $settings['font_family'];
$font_size = $settings['font_size'];
$text_color = $settings['text_color'];
$background_color = $settings['background_color'];
$theme_name = $settings['theme_name'];

// Theme-Einstellungen speichern
if (isset($_POST['save_theme'])) {
    $primary_color = $_POST['primary_color'];
    $secondary_color = $_POST['secondary_color'];
    $font_family = $_POST['font_family'];
    $font_size = intval($_POST['font_size']);
    $text_color = $_POST['text_color'];
    $background_color = $_POST['background_color'];
    $theme_name = $_POST['theme_name'];

    safe_query("UPDATE ".PREFIX."theme_settings SET primary_color='$primary_color', secondary_color='$secondary_color', font_family='$font_family', font_size='$font_size', text_color='$text_color', background_color='$background_color', theme_name='$theme_name' WHERE id=1");
    echo '<div class="alert alert-success">' . $_language->module['settings_saved'] . '</div>';
}

// Backup-Einträge abrufen
$backups = safe_query("SELECT * FROM ".PREFIX."backup_theme ORDER BY created_at DESC");

// Backup-Funktion
if (isset($_POST['backup_theme'])) {
    $backup_data = json_encode($settings);
    safe_query("INSERT INTO ".PREFIX."backup_theme (theme_name, backup_data, created_at) VALUES ('$theme_name', '$backup_data', NOW())");
    echo '<div class="alert alert-info">' . $_language->module['backup_created'] . '</div>';
}

// Wiederherstellen-Funktion
if (isset($_POST['restore_theme']) && isset($_POST['backup_id'])) {
    $backup_id = intval($_POST['backup_id']);
    $backup_result = safe_query("SELECT backup_data FROM ".PREFIX."backup_theme WHERE id='$backup_id'");
    if(mysqli_num_rows($backup_result) > 0) {
        $backup_data = mysqli_fetch_assoc($backup_result)['backup_data'];
        $backup_data = json_decode($backup_data, true);
        safe_query("UPDATE ".PREFIX."theme_settings SET primary_color='{$backup_data['primary_color']}', secondary_color='{$backup_data['secondary_color']}', font_family='{$backup_data['font_family']}', font_size='{$backup_data['font_size']}', text_color='{$backup_data['text_color']}', background_color='{$backup_data['background_color']}', theme_name='{$backup_data['theme_name']}' WHERE id=1");
        echo '<div class="alert alert-warning">' . $_language->module['backup_restored'] . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Theme Editor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<style>
    :root {
        --primary-color: <?php echo htmlspecialchars($primary_color, ENT_QUOTES, 'UTF-8'); ?>;
        --secondary-color: <?php echo htmlspecialchars($secondary_color, ENT_QUOTES, 'UTF-8'); ?>;
        --font-family: "<?php echo htmlspecialchars($font_family, ENT_QUOTES, 'UTF-8'); ?>";
        --font-size: <?php echo htmlspecialchars($font_size, ENT_QUOTES, 'UTF-8'); ?>;
        --text-color: <?php echo htmlspecialchars($text_color, ENT_QUOTES, 'UTF-8'); ?>;
        --background-color: <?php echo htmlspecialchars($background_color, ENT_QUOTES, 'UTF-8'); ?>;
    }

    .preview {
        background-color: var(--background-color);
        color: var(--text-color);
        font-family: var(--font-family);
        font-size: var(--font-size);
        padding: 20px;
        border: 1px solid #ccc;
        margin-top: 20px;
        transition: all 0.3s ease-in-out;
    }
</style>

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <i class="bi bi-palette"></i> <?php echo $_language->module['theme_editor']; ?>
        </div>
        <div class="card-body">
            <form method="post" action="" id="themeForm">
                <label><?php echo $_language->module['theme_name']; ?></label>
                <input class="form-control" type="text" id="theme_name" name="theme_name" value="<?php echo htmlspecialchars($theme_name, ENT_QUOTES, 'UTF-8'); ?>">


                <div class="preview" id="livePreview">
                    <p><?php echo $_language->module['preview_text']; ?></p>
                </div>

                <label><?php echo $_language->module['primary_color']; ?></label>
                <input class="form-control form-control-color" type="color" id="primary_color" name="primary_color" value="<?php echo htmlspecialchars($primary_color, ENT_QUOTES, 'UTF-8'); ?>">
                
                <label><?php echo $_language->module['secondary_color']; ?></label>
                <input class="form-control form-control-color" type="color" id="secondary_color" name="secondary_color" value="<?php echo htmlspecialchars($secondary_color, ENT_QUOTES, 'UTF-8'); ?>">

                <label>Background Color</label>
                <input class="form-control form-control-color" type="color" id="background_color" name="background_color" value="<?php echo htmlspecialchars($background_color, ENT_QUOTES, 'UTF-8'); ?>">
                
                <label><?php echo $_language->module['text_color']; ?></label>
                <input class="form-control form-control-color" type="color" id="text_color" name="text_color" value="<?php echo htmlspecialchars($text_color, ENT_QUOTES, 'UTF-8'); ?>">

                
                <label><?php echo $_language->module['font_family']; ?></label>
                <select class="form-control" id="font_family" name="font_family">
                    <option value="Arial, sans-serif" <?php echo ($font_family == 'Arial, sans-serif') ? 'selected' : ''; ?>>Arial</option>
                    <option value="Verdana, sans-serif" <?php echo ($font_family == 'Verdana, sans-serif') ? 'selected' : ''; ?>>Verdana</option>
                    <option value="Tahoma, sans-serif" <?php echo ($font_family == 'Tahoma, sans-serif') ? 'selected' : ''; ?>>Tahoma</option>
                    <option value="Georgia, serif" <?php echo ($font_family == 'Georgia, serif') ? 'selected' : ''; ?>>Georgia</option>
                </select>

                <label><?php echo $_language->module['font_size']; ?></label>
                <input class="form-control" type="number" id="font_size" name="font_size" value="<?php echo htmlspecialchars($font_size, ENT_QUOTES, 'UTF-8'); ?>" min="10" max="50">
                
                <button class="btn btn-success mt-3" type="submit" name="save_theme"> <?php echo $_language->module['save_settings']; ?> </button>
            </form>
            <hr>
            <h3>Backups</h3>
            <form method="post" action="">
                <select name="backup_id" class="form-control">
                    <?php while($backup = mysqli_fetch_assoc($backups)) { ?>
                        <option value="<?php echo $backup['id']; ?>"><?php echo htmlspecialchars($backup['theme_name']); ?> - <?php echo $backup['created_at']; ?></option>
                    <?php } ?>
                </select>
                <button class="btn btn-info mt-3" type="submit" name="backup_theme"> <?php echo $_language->module['create_backup']; ?> </button>
                <button class="btn btn-warning mt-3" type="submit" name="restore_theme"> <?php echo $_language->module['restore_backup']; ?> </button>
            </form>
            
                        
            
        </div>
    </div>
</div>

<script>
function updatePreview() {
    document.getElementById('livePreview').style.backgroundColor = document.getElementById('primary_color').value;
    document.getElementById('livePreview').style.backgroundColor = document.getElementById('secondary_color').value;
    document.getElementById('livePreview').style.backgroundColor = document.getElementById('background_color').value;
    document.getElementById('livePreview').style.color = document.getElementById('text_color').value;
    document.getElementById('livePreview').style.fontFamily = document.getElementById('font_family').value;
    document.getElementById('livePreview').style.fontSize = document.getElementById('font_size').value + 'px';
}

document.addEventListener("DOMContentLoaded", updatePreview);
document.querySelectorAll('#primary_color, #secondary_color, #background_color, #text_color, #font_family, #font_size').forEach(el => {
    el.addEventListener('input', updatePreview);
});
</script>




</body>
</html>
