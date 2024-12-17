<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
</head>
<body>
    <form method="post">
        <button type="submit" name="backup">Backup Page</button>
    </form>

    <?php
    if (isset($_POST['backup'])) {
        ob_start();
    ?>

    <?php
        $content = ob_get_clean();
        $backupFile = 'backup_' . date('Y-m-d_H-i-s') . '.txt';
        file_put_contents($backupFile, $content);
        echo "<p>Backup created successfully: <a href='$backupFile' download>Download Backup</a></p>";
    }
    ?>
</body>
</html>