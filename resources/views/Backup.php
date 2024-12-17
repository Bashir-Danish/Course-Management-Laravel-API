<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
</head>
<body>

    <!-- Backup Button -->
    <form method="post">
        <button type="submit" name="backup">Backup Page</button>
    </form>

    <?php
    if (isset($_POST['backup'])) {
        // Capture the page content
        ob_start();

        // files which you want to stores
    ?>

    <?php
        $content = ob_get_clean();

        // Set the file path and name
        $backupFile = 'backup_' . date('Y-m-d_H-i-s') . '.txt';

        // Save the content to the file
        file_put_contents($backupFile, $content);

        echo "<p>Backup created successfully: <a href='$backupFile' download>Download Backup</a></p>";
    }
    ?>
</body>
</html>