<?php
    $connexion = null;
    try{
        $cheminBDD = __DIR__ . '/blog.db';
        $connexion = new PDO('sqlite:'. $cheminBDD);
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
    }
    catch(PDOException $e){
        die('Erreur PDO : ' . $e->getMessage());
    }
    catch(Exception $e){
        die('Erreur générale : ' . $e->getMessage());
    }

?>