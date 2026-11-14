
<?php 
            if (isset($_POST["deconnexion"]) && $_POST["deconnexion"]=="true") {
                session_destroy();
                unset($_POST['deconnexion']);
                header("Location:index.php");
                exit();
            }
        ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css">
    <title>TP_Blog</title>
</head>
<body>
    <header>
        
        <?php 
            if (basename($_SERVER['PHP_SELF']) != "categorie.php" && basename($_SERVER['PHP_SELF']) != "creationArticle.php" && (isset($_SESSION['id']) && !empty($_SESSION['id']))) {
                echo('<form action="index.php" method="POST" class="deconnexion">');
                echo('<input type="hidden" name="deconnexion" value="true">');
                echo('<button type="submit">Déconnexion</button>');
                echo('</form>');
                
                echo('<p class="redigerArticle"><a href="./creationArticle.php">Rédiger un article</a></p>');
            }
            
            if (basename($_SERVER['PHP_SELF']) != "categorie.php" && basename($_SERVER['PHP_SELF']) != "creationArticle.php" && (isset($_SESSION['id']) && !empty($_SESSION['id']) && isset($_SESSION['admin']) && $_SESSION['admin'] ==1)) {
                echo('<p class="redigerArticle"><a href="./admin/categorie.php">AdminPanel</a></p>');
            }
            
            if (basename($_SERVER['PHP_SELF']) != "index.php" && basename($_SERVER['PHP_SELF']) != "connection.php") {
                if (basename($_SERVER['PHP_SELF']) == "categorie.php") {
                    echo('<p class="retourAIndex"><a href="../index.php">Retour</a></p>');
                } else {
                    echo('<p class="retourAIndex"><a href="./index.php">Retour</a></p>');
                }
            }
            
            if (basename($_SERVER['PHP_SELF']) == "index.php") {
                echo('<form method="GET" action="index.php" class="barreRecherche">');
                $pseudo = '';
                if (isset($_GET['pseudo'])) {
                    $pseudo = htmlspecialchars($_GET['pseudo']);
                }
                echo('<input type="text" name="pseudo" id="pseudo" placeholder="Filtrer par pseudo" value="'. $pseudo .'">');
                echo('<button type="submit">🔍</button>');
                echo('</form>');
            }

        ?>


        
            
            
            

            
            
        


    </header>