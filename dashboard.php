<?php
session_start();
require_once './_db/dbconnect.php';
// Vérification de la connexion de l'utilisateur
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
$user_id = $_SESSION['user_id'];
// Utilisez la connexion à la base de données
$db = new connexionDB();
$conn = $db->getConnection();
// Récupération des projets de l'utilisateur
try {
    $sql = "SELECT id, titre, description_courte, image FROM projets WHERE user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $projets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Erreur de base de données : " . $e->getMessage());
    $projets = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/dashboard.css">
    <title>Tableau de Bord</title>
    <link rel="stylesheet" href="./assets/css/dash.css">
</head>

<body>
    <header>
        <h1>Tableau de Bord</h1>
        <img id="darkModeToggle" src="./assets/img/dark.svg" alt="Dark Mode Toggle">
        <?php include 'nav.php'; ?>
        <a href="./_db/logout.php" class="benjamin" id="logout">Déconnexion</a>
    </header>
    <main>
        <h2>Mes Projets</h2>
        <?php if (empty($projets)) : ?>
            <p>Vous n'avez pas encore de projets.</p>
        <?php else : ?>
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Titre</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projets as $projet) : ?>
                        <tr>
                            <td><img src="<?php echo htmlspecialchars($projet['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($projet['titre'], ENT_QUOTES, 'UTF-8'); ?>" style="width: 100px; height: auto;"></td>
                            <td><?php echo htmlspecialchars($projet['titre'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($projet['description_courte'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                <a href="modifyproject.php?id=<?php echo $projet['id']; ?>">Modifier</a>
                                <a href="#" class="delete-project" data-id="<?php echo $projet['id']; ?>">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
    <footer>
        <img src="./assets/images/logo.png" alt="" id="">
    </footer>
    <script src="./assets/js/darkmode.js"></script>
    <script src="./assets/js/suprr.js"></script>
    <div id="confirmModal" class="modal">
    <div class="modal-content">
        <h2>Confirmer la suppression</h2>
        <p>Êtes-vous sûr de vouloir supprimer ce projet ?</p>
        <div class="modal-buttons">
            <button id="confirmDelete">Confirmer</button>
            <button id="cancelDelete">Annuler</button>
        </div>
    </div>
</div>
</body>



</html>