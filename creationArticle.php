<?php
    //require_once('pdo.php');
    require_once('init.php');
    require_once('header.php');

    $sql = "SELECT nom FROM Categorie";
?>

<form action="index.php" method="POST" class="redactionArticle">
    <input type="text" name="titre" placeholder="Titre">
    <textarea name="article" id="article" placeholder="Article"></textarea>

    <select id="categorie" name="categorie" required>

<?php
    foreach ($connexion->query($sql) as $row) {
        echo('<option value="'. $row['nom'] . '">' . $row['nom'] . '</option>');
    }
?>

    </select>

    <button type="submit">Envoyer l'article</button>
</form>

