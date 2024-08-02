<?php
session_start();

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'projectmanager';

try {
    // Connexion à MySQL
    $conn = new PDO("mysql:host=$host", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Création de la base de données si elle n'existe pas
    $conn->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    $conn->exec("USE $dbname");

    // Début de la transaction
    $conn->beginTransaction();

    // Définition des tables
    $tables = [
        'user' => "CREATE TABLE IF NOT EXISTS user (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) NOT NULL UNIQUE,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            INDEX idx_email (email)
        )",
        'projets' => "CREATE TABLE IF NOT EXISTS projets (
            id INT AUTO_INCREMENT PRIMARY KEY,
            titre VARCHAR(255) NOT NULL,
            description_courte TEXT NOT NULL,
            image VARCHAR(255) NOT NULL,
            user_id INT NOT NULL,
            FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
        )"
    ];

    $messages = [];

    // Création des tables
    foreach ($tables as $tableName => $createTableSql) {
        $checkTableSql = "SHOW TABLES LIKE '$tableName'";
        $tableExists = $conn->query($checkTableSql)->rowCount() > 0;
        if (!$tableExists) {
            $conn->exec($createTableSql);
            $messages[] = "Table '$tableName' créée avec succès.";
        } else {
            $messages[] = "La table '$tableName' existe déjà.";
        }
    }

    // Validation de la transaction
    $conn->commit();
    $_SESSION['message'] = implode("\n", $messages);
} catch (PDOException $e) {
    // Annulation de la transaction en cas d'erreur
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    $_SESSION['error'] = "Erreur : " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Libre+Barcode+128+Text&family=Special+Elite&display=swap" rel="stylesheet">
    <title>Gestion de Projets</title>
</head>
<body>
    <header>
        <h1>Accueil</h1>
    </header>
    <main>
        <?php 
           if (isset($_SESSION['error'])){
            echo "<div id='message-container'><p class='error'>". $_SESSION['error']."</p></div>";
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['message'])){
            echo "<div id='message-container'><p class='success'>". $_SESSION['message']."</p></div>";
            unset($_SESSION['message']);
        }
        ?>
        <div id="container">
            <div id="inscription-container">
                <form action="_db/register.php" autocomplete="off" id="inscription" method="POST" onsubmit="return validatePassword()">
                    <h2>Inscription</h2>
                    <label for="username">Pseudo :</label>
                    <input type="text" id="username" name="username" required>
                    <label for="email">Email :</label>
                    <input type="email" id="email" name="email" required>
                    <label for="password">Mot de passe :</label>
                    <input type="password" id="password" name="password" required>
                    <label for="confirm-password">Confirmer le mot de passe :</label>
                    <input type="password" id="confirm-password" name="confirm-password" required>
                    <button type="submit">S'inscrire</button>
                </form>
            </div>
            <div id="connexion-container">
                <form action="_db/login.php" autocomplete="off" id="connexion" method="POST">
                    <h2>Connexion</h2>
                    <label for="email">Email :</label>
                    <input type="email" id="email" name="email" required>
                    <label for="login-password">Mot de passe :</label>
                    <input type="password" id="login-password" name="password" required>
                    <button type="submit">Se connecter</button>
                </form>
            </div>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 - Gestion de Projets. Tous droits reserves. Guillaume Gutierrez quintas</p>
    </footer>
    <script src="./assets/js/darkmode.js"></script>
    <script src="./assets/js/sageme.js"></script>
</body>
</html>