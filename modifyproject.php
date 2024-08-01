<?php
session_start();
require_once './_db/dbconnect.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$project_id = isset($_GET['id']) ? $_GET['id'] : null;
$db = new connexionDB();
$conn = $db->getConnection();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titre = $_POST['titre'];
    $description_courte = $_POST['description_courte'];
    $image = $_POST['image'];
    $sql = "SELECT user_id FROM projets WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $project_id, PDO::PARAM_INT);
    $stmt->execute();
    $project = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($project && $project['user_id'] == $user_id) {
        $sql = "UPDATE projets SET titre = :titre, description_courte = :description_courte, image = :image WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':titre', $titre, PDO::PARAM_STR);
        $stmt->bindParam(':description_courte', $description_courte, PDO::PARAM_STR);
        $stmt->bindParam(':image', $image, PDO::PARAM_STR);
        $stmt->bindParam(':id', $project_id, PDO::PARAM_INT);
        $stmt->execute();
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Vous n'avez pas la permission de modifier ce projet.";
    }
} else {
    if ($project_id) {
        $sql = "SELECT titre, description_courte, image FROM projets WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $project_id, PDO::PARAM_INT);
        $stmt->execute();
        $project = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($project) {
            ?>
            <!DOCTYPE html>
            <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Modifier Projet</title>
                <link rel="stylesheet" href="./assets/css/style.css">
            </head>
            <body>
                <header>
                    <h1>Modifier Projet</h1>
                    <img id="darkModeToggle" src="./assets/img/dark.svg" alt="Dark Mode Toggle">
                    <?php include 'nav.php'; ?>
                    <a href="./_db/logout.php" class="benjamin" id="logout">Déconnexion</a>
                </header>
                <main>
                    <form method="POST" action="modifyproject.php?id=<?php echo $project_id; ?>">
                        <label for="titre">Titre :</label>
                        <input type="text" id="titre" name="titre" value="<?php echo htmlspecialchars($project['titre'], ENT_QUOTES, 'UTF-8'); ?>" required>

                        <label for="description_courte">Description Courte :</label>
                        <textarea id="description_courte" name="description_courte" required><?php echo htmlspecialchars($project['description_courte'], ENT_QUOTES, 'UTF-8'); ?></textarea>

                        <label for="image">Image (URL) :</label>
                        <input type="text" id="image" name="image" value="<?php echo htmlspecialchars($project['image'], ENT_QUOTES, 'UTF-8'); ?>" required>

                        <button type="submit">Modifier</button>
                    </form>
                </main>
                <footer>
                    <img src="./assets/images/logo.png" alt="" id="">
                </footer>
                
                <script src="./assets/js/darkmode.js"></script>
            </body>
            </html>
            <?php
        } else {
            echo "Projet non trouvé.";
        }
    } else {
        echo "ID de projet non spécifié.";
    }
}
?>