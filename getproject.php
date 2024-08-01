<?php
require_once './_db/dbconnect.php';
if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);  
    try {
        // Connexion à la base de données
        $db = new ConnexionDB();
        $conn = $db->getConnection();
        // Préparer et exécuter la requête pour récupérer les projets de l'utilisateur
        $stmt = $conn->prepare("SELECT * FROM projets WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();      
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($projects) {
            header('Content-Type: application/json');
            echo json_encode($projects);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'No projects found for this user']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Internal Server Error', 'error' => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid request']);
}
?>
