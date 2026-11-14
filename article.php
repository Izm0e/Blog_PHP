<?php
require_once('init.php');
require_once('header.php');

if (isset($_POST['supprimer']) && isset($_GET['article_id'])) {
    $stmt = $connexion->prepare("DELETE FROM Article WHERE id = ? ");
    $stmt->execute([$_GET['article_id']]);

    $sql = $connexion->prepare("SELECT articles from Categorie WHERE nom = ? ");
    $sql->execute([$row['categories']]);
    $result = $sql->fetch(PDO::FETCH_ASSOC);
    $ids = $result['articles'];
    $searchID = $row['id'];

    $position = strpos(";$ids;", ";$searchID;");
    if ($position != false) {
        $id_post = substr($ids, 0, $position) . substr($ids, $position + strlen(';$searchID;'));
        $stmt = $connexion->prepare("UPDATE Categorie SET articles = ? WHERE nom = ?");
        $stmt->execute([$id_post, $row['categories']]);
    }


    header('Location:index.php');
    exit();
}

if (isset($_POST['supprimerComm']) && isset($_POST['commID'])) {
    $sql = $connexion->prepare("SELECT article FROM Commentaire WHERE id = ? ");
    $sql->execute([$_POST['commID']]);
    $result = $sql->fetch(PDO::FETCH_ASSOC);
    $article_id = $result['article'];

    $stmt = $connexion->prepare("DELETE FROM Commentaire WHERE id = ? ");
    $stmt->execute([$_POST['commID']]);


    $sql = $connexion->prepare("SELECT commentaires from Article WHERE id = ? ");
    $sql->execute([$_POST['commID']]);
    $result = $sql->fetch(PDO::FETCH_ASSOC);
    $comms = $result['commentaires'];
    $searchComm = $_POST['commID'];

    $position = strpos(";$comms;", ";$searchComm;");
    if ($position != false) {
        $id_comms = substr($comms, 0, $position) . substr($comms, $position + strlen(';$searchComm;'));
        $stmt = $connexion->prepare("UPDATE Article SET commentaires = ? WHERE id = ?");
        $stmt->execute([$id_comms, $article_id]);
    }

    header('Location:article.php?article_id=' . $article_id);
    exit();
}

if (isset($_GET['article_id']) && !empty($_GET['article_id'])) {
    $sql = $connexion->prepare("SELECT * FROM Article WHERE id = ? ;");
    $sql->execute([$_GET['article_id']]);

    foreach ($sql as $row) {
        echo ('<div class="article">');
        echo ('<h2 class="titre"> ' . $row['titre'] . '</h2>');
        echo ('<p class="description">' . $row['description'] . '</p>');
        echo ('<p class="categorie">' . $row['categories'] . '</p> ');
        echo ('<p class="auteur">Par ' . $row['pseudo'] . '</p>');

        if (isset($_SESSION['pseudo']) && ($row['pseudo'] == $_SESSION['pseudo'] || $_SESSION['admin'] == 1)) {
            echo ('<form method="POST" action="" class="supprimer">');
            echo ('<input type="hidden" name="article_id" value="' . $row['id'] . '">');
            echo ('<button type="submit" name="supprimer">supprimer l article</button>');
            echo ('</form>');

        }
        ?>

        <?php

        if (isset($_GET['article_id']) && isset($_POST['commentaire']) && !empty($_POST['commentaire'])) {
            $stmt = $connexion->prepare("INSERT INTO Commentaire(description, article, pseudo) VALUES (?, ?, ?)");
            $descriptionComm = htmlspecialchars($_POST['commentaire']);
            $stmt->execute([$descriptionComm, $_GET['article_id'], $_SESSION['pseudo']]);

            $stmt = $connexion->prepare("SELECT MAX(id) FROM Commentaire;");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $id_comm = $result['MAX(id)'];

            $stmt = $connexion->prepare("SELECT commentaires FROM Article WHERE id = ?");
            $stmt->execute([$_GET['article_id']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $comms = $result['commentaires'];
            $comms = $comms . $id_comm . ';';

            $stmt = $connexion->prepare("UPDATE Article SET commentaires = ? WHERE id = ?");
            $stmt->execute([$comms, $_GET['article_id']]);

            header("Location: article.php?article_id=" . $_GET['article_id']);
            exit();

        }

        echo ('</div>');
        ?>

        <form action="" method="POST" class="ajoutCommentaire">
            <input type="hidden" name="article_id" id="article_id" value="<?= $row['id'] ?>">
            <input type="text" name="commentaire" placeholder="Votre commentaire" required>
            <button type="submit">Poster commentaire</button>
        </form>

        <?php
        echo ('<div class="listeCommentaires">');
        $stmt = $connexion->prepare("SELECT * FROM Commentaire WHERE article = ? ORDER BY id DESC");
        $stmt->execute([$_GET['article_id']]);

        foreach ($stmt as $row2) {
            echo ('<div class="commentaires">');
            echo ('<p> Par ' . $row2['pseudo'] . '</p>');
            echo ('<p>' . $row2['description'] . '</p>');

            if (isset($_SESSION['pseudo']) && ($row2['pseudo'] == $_SESSION['pseudo'] || $_SESSION['admin'] == 1)) {
                $_GET['article_id'] = $row2['id'];

                echo ('<form method="POST" action="" class="supprimer">');
                echo ('<input type="hidden" name="commID" value="' . $row2['id'] . '">');
                echo ('<button type="submit" name="supprimerComm">supprimer le commentaire</button>');
                echo ('</form>');

            }
            echo ('</div>');
        }
        echo ('</div>');
    }
}
?>