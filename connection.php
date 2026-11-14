<?php 
        require_once('header.php');
?>

<form class="connexion" action="index.php" method="POST">
        <label for="identifiant">Identifiant</label>
        <input type="email" id="identifiant" name="identifiant" required>
        <label for="mdp">Mot de passe</label>
        <input type="password" id="mdp" name="mdp" required>
        <button type="submit">Se Connecter</button>

        <?php 
                if (isset($_GET['error']) && $_GET['error'] == 'invalid'){
                        echo('<p class="erreur">Identifiant ou mot de passe invalide</p>' );
                }
                require_once('footer.php');
        ?>
</form>

