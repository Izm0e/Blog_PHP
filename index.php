<?php
require_once('init.php');

if (isset($_POST['article']) && !empty($_POST['article'])) {

    $sql = $connexion->prepare("INSERT INTO Article (titre, description, categories, pseudo) VALUES (?, ?, ?, ?);");
    $sql->bindParam(1, $titre);
    $sql->bindParam(2, $description);
    $sql->bindParam(3, $categories);
    $sql->bindParam(4, $pseudo);

    $titre = htmlspecialchars($_POST['titre']);
    $description = htmlspecialchars($_POST['article']);
    $categories = $_POST['categorie'];
    $pseudo = $_SESSION['pseudo'];
    $sql->execute();

    $stmt = $connexion->prepare("SELECT MAX(id) FROM Article ; ");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $id_post = $result['MAX(id)'];

    $stmt = $connexion->prepare("SELECT articles FROM Categorie WHERE nom = ? ;");
    $stmt->execute([$categories]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $cat = $result['articles'];
    $cat = $cat . $id_post . ';';


    $sql = $connexion->prepare("UPDATE Categorie SET articles = ? WHERE nom = ? ;");
    $sql->execute([$cat, $categories]);

    unset($_POST['article']);
    header('Location:index.php');
    exit();
}

require_once('header.php');




echo ('<div class="listeArticles">');
try {

    if (isset($_GET['pseudo']) && !empty($_GET['pseudo'])) {
        $sql = $connexion->prepare("SELECT * FROM Article WHERE pseudo = ? ORDER BY id DESC;");
        $sql->execute([$_GET['pseudo']]);
    } else {
        $sql = $connexion->prepare("SELECT * FROM Article ORDER BY id DESC;");
        $sql->execute();
    }

    $articles = $sql->fetchAll(PDO::FETCH_ASSOC);
    if (count($articles) == 0) {
        echo '<p>Aucun article trouvé.</p>';
    } else {
        
        foreach ($articles as $row) {

            echo ('<div class="article">');
            echo ('<h2 class="titre"> ' . $row['titre'] . '</h2>');
            echo ('<p class="description">' . $row['description'] . '</p>');
            echo ('<p class="categorie">' . $row['categories'] . '</p> ');
            echo ('<p class="auteur">Par ' . $row['pseudo'] . '</p>');

            echo ('<form method="GET" action="article.php" class="affichageArticle">');
            echo ('<input type="hidden" name="article_id" value="' . $row['id'] . '">');
            echo ('<button type="submit" name="afficher">Afficher l\'article</button>');
            echo ('</form>');

            if (isset($_SESSION['pseudo']) && ($row['pseudo'] == $_SESSION['pseudo'] || $_SESSION['admin'] == 1)) {
                echo ('<form method="POST" action="" class="supprimer">');
                echo ('<input type="hidden" name="article_id" value="' . $row['id'] . '">');
                echo ('<button type="submit" name="supprimer">supprimer l\'article</button>');
                echo ('</form>');
                if (isset($_POST['supprimer']) && isset($_POST['article_id']) && $_POST['article_id'] == $row['id']) {
                    $stmt = $connexion->prepare("DELETE FROM Article WHERE id = ? ");
                    $stmt->execute([$_POST['article_id']]);

                    $sql = $connexion->prepare("SELECT articles from Categorie WHERE nom = ? ");
                    $sql->execute([$row['categories']]);
                    $result = $sql->fetch(PDO::FETCH_ASSOC);
                    $ids = $result['articles'];
                    $searchID = $row['id'];

                    $position = strpos(";$ids;", ";$searchID;");
                    if ($position != false) {
                        $id_post = substr($ids, 0, $position) . substr($ids, $position + strlen(";$searchID;"));
                        $stmt = $connexion->prepare("UPDATE Categorie SET articles = ? WHERE nom = ?");
                        $stmt->execute([$id_post, $row['categories']]);
                    }


                    header('Location:index.php');
                    exit();
                }


            }
            echo ('</div>');

        }


        echo ('</div>');

    }
    echo ('</div>');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
?>

<?php
require_once('footer.php');
?>