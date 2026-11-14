<?php

    require_once('../pdo.php');
    require_once('../init.php');
    require_once('../header.php');


    if (!isset($_SESSION['id']) || empty($_SESSION['id']) || !isset($_SESSION['admin']) || $_SESSION['admin'] !=1){
        header('Location: ../index.php');
        exit();
    }
    else {
        ?>
        <p>Création d'une catégorie</p>
        <form action="" method="POST">
            <input type="text" name="categorie" required>
            <button type="submit" name="creer">Créer</button>
        </form>

        <?php
        try {
            if (isset($_POST['creer'])){
                $stmt = $connexion->prepare("select * from Categorie where nom = ?");
                $stmt->execute([$_POST['categorie']]);
                if ($stmt->rowCount() == 0){
                    $stmt = $connexion->prepare("INSERT INTO Categorie(nom) VALUES(?)");
                    $stmt->execute([$_POST['categorie']]);
                    header('Location: categorie.php');
                    exit();
                }
            }
        } catch (Exception $e) {
            print_r($e);
        }
            
        ?>
        <p>Suppression d'une catégorie</p>
        <form action="" method="POST">
            <select id="categorie" name="categorie" required>
                <?php
                try{
                    $stmt = $connexion->prepare("SELECT nom FROM Categorie");
                    $stmt->execute();
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo('<option value="'. $row['nom'] . '">' . $row['nom'] . '</option>');
                    }
                }
                catch(Exception $e){
                    print_r($e);
                }
                ?>
            </select>
            <button type="submit" name="supprimer">Supprimer</button>
        </form>
        <?php
            try{
                if (isset($_POST['supprimer'])){
                    $stmt = $connexion->prepare("DELETE FROM Categorie WHERE nom = ?");
                    $stmt->execute([$_POST['categorie']]);
                    header('Location: categorie.php');
                    exit();
                }
            }
            catch(Exception $e){
                print_r($e);
            }
        ?>
        <p>Modification d'une categorie</p>
        <form action="" method="POST">
            <label for="avant">Catégorie à modifier :  </label>
            <select id="avant" name="avant" required>
                <?php
                try{
                    $stmt = $connexion->prepare("SELECT nom FROM Categorie");
                    $stmt->execute();
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo('<option value="'. $row['nom'] . '">' . $row['nom'] . '</option>');
                    }
                }catch(Exception $e){
                    print_r($e);
                }
                ?>
            </select>
            <label for="apres">Nouveau nom :</label>
            <input type="text" id="apres" required name="apres">
            <button type="submit" name="modif">Modifier</button>
        </form>
        <?php
        try{
           if (isset($_POST['modif'])){
               $stmt = $connexion->prepare("UPDATE Categorie SET nom = ? WHERE nom = ?");
               $stmt->execute([$_POST['apres'], $_POST['avant']]);
               header('Location: categorie.php');
               exit();
           }
        }
        catch(Exception $e){
            print_r($e);
        }
    }
?>