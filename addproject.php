<?php
session_start();
require_once './_db/dbconnect.php';
// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Projet</title>
    <link rel="stylesheet" href="./assets/css/addproject.css">
</head>
<body>
    <header>
        <h1>Ajouter un Projet</h1>
        <img id="darkModeToggle" src="./assets/img/dark.svg" alt="Dark Mode Toggle">
        <?php include 'nav.php'; ?>
        <a href="./_db/logout.php" class="benjamin" id="logout">Logout</a>
    </header>
    <main>
        <form id="projectForm" method="POST" autocomplete="off" enctype="multipart/form-data">
            <label for="titre">Titre du projet</label>
            <input type="text" id="titre" name="titre" required>          
            <label for="description_courte">Description courte</label>
            <textarea id="description_courte" name="description_courte" required></textarea>  
            <label for="image">Image du projet</label>
            <input type="file" id="image" name="image" accept="image/*" required>
            <div id="imagePreview"></div>
            <input type="hidden" id="image_path" name="image_path">
            <button type="submit" name="add_project">Ajouter le projet</button>
        </form>
        <div id="errorMessage" style="color: red;"></div>
        <div id="successMessage" style="color: green;"></div>
    </main>
    <footer>
        <img src="./assets/images/logo.png" alt="" id="">
    </footer>
    <script src="./assets/js/darkmode.js"></script>
    <script src="./assets/js/uploadimg.js"></script>
</body>
</html>