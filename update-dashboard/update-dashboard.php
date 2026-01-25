<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Aktualizace Dashboardu</title>
</head>
<body>
    <h1>Správa Dashboardu</h1>
    <form method="post">
        <button type="submit" name="update">Aktualizovat dashboard</button>
    </form>

    <?php
    if (isset($_POST['update'])) {
        $targetDir = '/var/www/dashboard';
        $repoUrl = 'https://github.com/wiblingen/dashboard.git';

        // 1. Změna vlastnictví /var/www (vyžaduje sudo v sudoers)
        exec("sudo chown www-data:www-data /var/www 2>&1", $out1, $res1);
        
        // 2. Odstranění adresáře
        // -rf smaže adresář i s obsahem bez ptaní
        exec("sudo rm -rf " . escapeshellarg($targetDir) . " 2>&1", $outputRm, $returnRm);

        if ($returnRm === 0) {
            // 3. Git clone
            exec("git clone $repoUrl " . escapeshellarg($targetDir) . " 2>&1", $outputGit, $returnGit);

            if ($returnGit === 0) {
                echo "<p style='color: green;'><strong>Výsledek:</strong> Aktualizace proběhla úspěšně.</p>";
            } else {
                echo "<p style='color: red;'><strong>Chyba při klonování:</strong><br>" . implode("<br>", $outputGit) . "</p>";
            }
        } else {
            echo "<p style='color: red;'><strong>Chyba při mazání adresáře:</strong><br>" . implode("<br>", $outputRm) . "</p>";
        }
    }
    ?>
</body>
</html>
