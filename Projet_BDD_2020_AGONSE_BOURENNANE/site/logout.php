<?php
  session_start();
  session_unset();
  session_destroy();
  setcookie("donnees_cli[prenom]","", time() + -5 * 60 * 1000);
              setcookie("donnees_cli[nom]","", time() + -5 * 60 * 1000);
              setcookie("donnees_cli[datefin]","", time() + -5 * 60 * 1000);
              setcookie("donnees_cli[adresse]","", time() + -5 * 60 * 1000);
              setcookie("donnees_cli[courriel]","", time() + -5 * 60 * 1000);
              setcookie("donnees_cli[premium]","", time() + -5 * 60 * 1000);
              setcookie("donnees_cli[fils1]","", time() + -5 * 60 * 1000);
              setcookie("donnees_cli[fils2]","", time() + -5 * 60 * 1000);
              setcookie("donnees_cli[fils3]","", time() + -5 * 60 * 1000);
              setcookie("donnees_cli[fils4]","", time() + -5 * 60 * 1000);
  setcookie("id_utilisateur","", time() + -5 * 60 * 1000);         
  /*
  setcookie("donnees_cli", json_encode(array(
                "prenom_cli" => "",
                "nom_cli" => "",
                "datefin_cli" => "",
                "adresse_cli" => "",
                "courriel_cli" => "",
                "premium_cli" => "",
                "fils1_cli" => "",
                "fils2_cli" => "",
                "fils3_cli" => "",
                "fils4_cli" => ""
              )), time() + -10 * 60 * 1000);
  */
              ?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="public/css/base.css">
    <link rel="stylesheet" href="public/css/header.css">
    <link rel="stylesheet" href="public/css/login.css">

    <title>Connection</title>
    
    <?php 
      include "header.php";
    ?>

  </head>
  <body>
    <div class="container">
      <h2>Vous vous êtes bien deconnecté !</h2>
      <img style="width:100%;" title="tkt t'es bien deco ça marche nickel" src="public/img/OK.jpg">
      <div style="display:flex;">
        <a class="login_form_input_button" href="index.html"> Retour à l'accueil </a>
        <a class="login_form_input_button" href="login.php"> Se connecter </a>
      </div>
    </div>
    
  </body>
</html> 