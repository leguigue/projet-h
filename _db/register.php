<?php
session_start();
require_once 'dbconnect.php';
if (!isset($conn) || !($conn instanceof PDO)) {
    die("Erreur : La connexion à la base de données n'est pas établie correctement.");
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST['username']) ? htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8') : '';
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Adresse e-mail invalide";
        header('Location: ../index.php');
        exit;
    }
    // Vérification de la complexité du mot de passe
    if (!preg_match('/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        $_SESSION['error'] =  "Le mot de passe doit contenir au moins 8 caractères, une majuscule et un chiffre.";
        header('Location: ../index.php');
        exit;
    }
    $password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO user (username, email, password) VALUES (:username, :email, :password)";
    try {
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Erreur lors de la préparation de la requête : " . $conn->errorInfo()[2]);
        }
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', $password);
        if ($stmt->execute()) {
            header('Location: ../dashboard.php');
            $_SESSION['user_id'] = $_POST["username"];
            exit;
        } else {
            $_SESSION['error'] = "Les mots de passe ne correspondent pas";
            header('Location: ../index.php');
            exit;

        }
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>