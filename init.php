<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start();
require_once(__DIR__ . '/pdo.php');

if ((isset($_POST['identifiant']) && !empty($_POST['identifiant'])) && (isset($_POST['mdp']) && !empty($_POST['mdp']))) {
    $identifiant = htmlspecialchars($_POST['identifiant']);
    $mdp = $_POST['mdp'];

    $stmt = $connexion->prepare("SELECT * FROM User WHERE email = ? ");
    $stmt->bindParam(1, $identifiant);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($mdp == $user['mdp']) {
            $_SESSION['id'] = $user['email'];
            $_SESSION['pseudo'] = $user['pseudo'];
            $_SESSION['admin'] = $user['admin'];
        } else {
            header('Location: connection.php?error=invalid');
            exit();
        }
    } else {
        $pseudo = substr($identifiant, 0, strpos($identifiant, '@'));
        $stmt = $connexion->prepare("SELECT * FROM User WHERE pseudo = ?");
        $stmt->execute([$pseudo]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user['pseudo'] == $pseudo){
            header('Location: connection.php?error=invalid');
            exit();
        }
        else {
            $sql = $connexion->prepare("INSERT INTO User (email, pseudo, mdp, admin) VALUES (?, ?, ?, FALSE)");
            $sql->bindParam(1, $identifiant);
            $sql->bindParam(2, $pseudo);
            $sql->bindParam(3, $mdp);
            $sql->execute();
        }
    }
} else if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    header('Location: connection.php');
    exit();
}



?>