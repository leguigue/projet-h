<?php
session_start();
require_once './_db/dbconnect.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
$user_id = $_SESSION['user_id'];
if (isset($_GET['id'])) {
    $project_id = $_GET['id'];
    $db = new connexionDB();
    $conn = $db->getConnection();
    $sql = "SELECT user_id FROM projets WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $project_id, PDO::PARAM_INT);
    $stmt->execute();
    $project = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($project && $project['user_id'] == $user_id) {
        $sql = "DELETE FROM projets WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $project_id, PDO::PARAM_INT);
        $stmt->execute();
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Vous n'avez pas la permission de supprimer ce projet.";
    }
} else {
    echo "ID de projet non spécifié.";
}
?>