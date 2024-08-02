<?php
session_start();
require_once './_db/dbconnect.php';
header('Content-Type: application/json');
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

try {
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('User not authenticated');
    }

    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        throw new Exception('Invalid request method');
    }

    $titre = htmlspecialchars($_POST['titre'] ?? '', ENT_QUOTES, 'UTF-8');
    $description_courte = htmlspecialchars($_POST['description_courte'] ?? '', ENT_QUOTES, 'UTF-8');
    $user_id = $_SESSION['user_id'];
    $image_path = $_POST['image_path'] ?? '';

    error_log("Received data: " . print_r($_POST, true));

    if (empty($image_path)) {
        throw new Exception("Aucune image n'a été uploadée.");
    }

    $sql = "INSERT INTO projets (titre, description_courte, image, user_id) VALUES (:titre, :description_courte, :image, :user_id)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':titre', $titre);
    $stmt->bindParam(':description_courte', $description_courte);
    $stmt->bindParam(':image', $image_path);
    $stmt->bindParam(':user_id', $user_id);             

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => "Projet ajouté avec succès!"]);
    } else {
        throw new Exception("Erreur lors de l'ajout du projet");
    }

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => "Erreur de base de données"]);
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>