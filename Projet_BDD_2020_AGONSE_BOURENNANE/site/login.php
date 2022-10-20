<?php
  session_start();
    
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <link rel="stylesheet" href="public/css/base.css">
    <link rel="stylesheet" href="public/css/film.css">
    <link rel="stylesheet" href="public/css/login.css">

    <title>Connection</title>
    
    <?php
      //étrange ça fait buger les cookies si j'affiche le header avant leurs initialisation  
      //include "header.php";
    ?>
    
    <?php

    //MAJ DANS LA BDD via les formulaires

      if(isset($_POST["pseudo_uti"])){
        if ($_POST["pseudo_uti"] != "") {
          include "connexion.php";
          $results= $cnx->prepare('UPDATE utilisateur SET pseudo = :pseudo WHERE idutilisateur = :idnombre;');
          $results->execute(array(":idnombre" => $_COOKIE['id_utilisateur'],
                                  ":pseudo" =>  $_POST["pseudo_uti"]));
        }
      }
      if(isset($_POST["age_uti"])){
        if ($_POST["age_uti"] != "") {
          include "connexion.php";
          $results= $cnx->prepare('UPDATE utilisateur SET ageu = :age WHERE idutilisateur = :idnombre;');
          $results->execute(array(":idnombre" => $_COOKIE['id_utilisateur'],
                                  ":age" =>  $_POST["age_uti"]));
        }
      }
      if(isset($_POST["mdp_cli_update"])){
        if ($_POST["mdp_cli_update"] != ""){
          $_SESSION["mdp"] = $_POST["mdp_cli_update"];
          include "connexion.php";
          $results= $cnx->prepare('UPDATE client SET mdp = :mdp WHERE idclient = :idnombre;');
          $results->execute(array(":idnombre" => $_SESSION['id'],
                                  ":mdp" =>  $_POST["mdp_cli_update"]));
        }
      }
      if(isset($_POST["nom_cli_update"])){
        setcookie("donnees_cli[nom]",$_POST["nom_cli_update"], time() + 5 * 60 * 1000);
        if($_POST["nom_cli_update"] != ""){
          include "connexion.php";
          $results= $cnx->prepare('UPDATE client SET nomc = :nom WHERE idclient = :idnombre;');
          $results->execute(array(":idnombre" => $_SESSION['id'],
                                  ":nom" =>  $_POST["nom_cli_update"]));
        }
        
      }
      if(isset($_POST["prenom_cli_update"])){
        if($_POST["prenom_cli_update"] != ""){
          setcookie("donnees_cli[prenom]",$_POST["prenom_cli_update"], time() + 5 * 60 * 1000);
          include "connexion.php";
          $results= $cnx->prepare('UPDATE client SET prenomc = :prenom WHERE idclient = :idnombre;');
          $results->execute(array(":idnombre" => $_SESSION['id'],
                                  ":prenom" =>  $_POST["prenom_cli_update"]));
        }
        
      }
      if(isset($_POST["adresse_cli_update"])){
        include "connexion.php";
        setcookie("donnees_cli[adresse]",$_POST["prenom_cli_update"], time() + 5 * 60 * 1000);
        $results= $cnx->prepare('UPDATE client SET adresse = :adresse WHERE idclient = :idnombre;');
        $results->execute(array(":idnombre" => $_SESSION['id'],
                                ":adresse" =>  $_POST["adresse_cli_update"]));
      }
      if(isset($_POST["courriel_cli_update"])){
        setcookie("donnees_cli[courriel]",$_POST["courriel_cli_update"], time() + 5 * 60 * 1000);
        include "connexion.php";
        $results= $cnx->prepare('UPDATE client SET courriel = :courriel WHERE idclient = :idnombre;');
        $results->execute(array(":idnombre" => $_SESSION['id'],
                                ":courriel" =>  $_POST["courriel_cli_update"]));
      }
      if(isset($_POST["abo_new"])){
        $annee = $_COOKIE['donnees_cli']['datefin'];
        $annee = date('d/m/Y',strtotime($annee. '+ 1 years'));
        setcookie("donnees_cli[datefin]", $annee, time() + 5 * 60 * 1000);
        
        include "connexion.php";
        $results= $cnx->prepare('UPDATE client SET datefin = :datefin WHERE idclient = :idnombre;');
        $results->execute(array(":idnombre" => $_SESSION['id'],
                                ":datefin" =>  $annee));
        
      }
      if(isset($_POST["abo_premium_new"])){
        $annee = $_COOKIE['donnees_cli']['datefin'];
        $annee = date('d/m/Y',strtotime($annee. '+ 1 years'));
        setcookie("donnees_cli[datefin]", $annee, time() + 5 * 60 * 1000);
        
        include "connexion.php";
        $results= $cnx->prepare('UPDATE client SET datefin = :datefin WHERE idclient = :idnombre;');
        $results->execute(array(":idnombre" => $_SESSION['id'],
                                ":datefin" =>  $annee));
        include "connexion.php";
        $results= $cnx->prepare('UPDATE client SET premium = :premium WHERE idclient = :idnombre;');
        $results->execute(array(":idnombre" => $_SESSION['id'],
                                ":premium" =>  TRUE));
        
      }

      
    ?>

  </head>
  
  <body>

    
    <div class="container">

      <div id="login" class="login_form">
        <h2>Se connecter</h2>
        <form action="login.php" method="POST">
          <label for="id_cli"><p class="login_form_p">Identifiant</p></label>
          <input class="login_form_input" type="text" name="id_cli"  placeholder="Rentrer un identifiant"/>
          <label for="mdp_cli"><p class="login_form_p">Mot de passe</p></label>
          <input class="login_form_input" type="text" name="mdp_cli" placeholder="Rentrer un mot de passe"/>
                
          <input class="login_form_input_button" type="submit" name="submit" value="Valider"/>
          <input class="login_form_input_button" type="reset" name="reset"  value="Effacer"/>
        </form>
        <p style="margin: 0; margin-top: 10px;">Nouveau sur VidéoFlex ? <a style="color: red;" href="#" onclick="afficher_register()">s'enregister</a></p>
      </div>

      <div id="register" class="login_form">
        <h2>Créer un compte</h2>
        <form action="login.php" method="POST">
          <label for="id_cli_new"><p class="login_form_p">Definir un identifiant</p></label>
          <input class="login_form_input" type="text" name="id_cli_new"  placeholder="Rentrer un identifiant"/>
          <label for="mdp_cli_new"><p class="login_form_p">Definir un mot de passe</p></label>
          <input class="login_form_input" type="text" name="mdp_cli_new" placeholder="Rentrer un mot de passe"/>
          <label for="nom_cli_new"><p class="login_form_p">Nom</p></label>
          <input class="login_form_input" type="text" name="nom_cli_new" placeholder="Rentrer un nom"/>
          <label for="prenom_cli_new"><p class="login_form_p">Prenom</p></label>
          <input class="login_form_input" type="text" name="prenom_cli_new" placeholder="Rentrer un prenom"/>
          <label for="adresse_cli_new"><p class="login_form_p">Adresse</p></label>
          <input class="login_form_input" type="text" name="adresse_cli_new" placeholder="Rentrer une adresse"/>
          <label for="courriel_cli_new"><p class="login_form_p">Courriel</p></label>
          <input class="login_form_input" type="text" name="courriel_cli_new" placeholder="Rentrer un courriel"/>
                
          <input class="login_form_input_button" type="submit" name="submit" value="Valider"/>
          <input class="login_form_input_button" type="reset" name="reset"  value="Effacer"/>
        </form>
        <p style="margin: 0; margin-top: 10px;">Déja sur VidéoFlex ? <a style="color: red;" href="#" onclick="afficher_login()">s'identifier</a></p>
      </div>
      
     <!-- Definition des cookies client --> 
      <?php

        //inscription
        //on verifie si l'id est valide
        if(isset($_POST["id_cli_new"]) && $_POST["id_cli_new"] != "" && intval($_POST["id_cli_new"]) != 0){
          ?><p><?php
          
          ?><p><?php
          //on verifie si l'identifiant n'est pas deja pris
          $results= $cnx->prepare('SELECT * FROM client WHERE idclient = :idclient ;');
          $results->execute(array(":idclient" => $_POST["id_cli_new"]));
          $nb_ligne = $results->rowCount();
          if ($nb_ligne = 0){
            ?><p><?php
              printf("L'identifiant est déja prit");
            ?><p><?php
          } else {
            //on verifie tout les autres champs
            if(isset($_POST["mdp_cli_new"]) && $_POST["mdp_cli_new"] != ""
              && isset($_POST["nom_cli_new"]) && $_POST["nom_cli_new"] != ""
              && isset($_POST["prenom_cli_new"]) && $_POST["prenom_cli_new"] != ""
              && isset($_POST["adresse_cli_new"]) && $_POST["adresse_cli_new"] != ""
              && isset($_POST["courriel_cli_new"]) && $_POST["courriel_cli_new"] != ""){
                //puis on insert toute les valeur pour créer un nouveau client
                $newdate = date('d/m/Y');
                include "connexion.php";

                $results= $cnx->prepare("INSERT INTO client(idClient,mdp,nomC,prenomC,dateFin,adresse,courriel,premium,Fils1,Fils2, Fils3, Fils4) VALUES
                  (:idclient,:mdp,:nom,:prenom,:datefin,:adresse,:courriel,FALSE,14,11,12,13);");
                $results->execute(array(":idclient" => $_POST["id_cli_new"],
                                        ":mdp" => $_POST["mdp_cli_new"],
                                        ":nom" => $_POST["nom_cli_new"],
                                        ":prenom" => $_POST["prenom_cli_new"],
                                        ":datefin" => $newdate,
                                        ":adresse" => $_POST["adresse_cli_new"],
                                        "courriel" => $_POST["courriel_cli_new"]
                                      ));
                ?><p><?php
                  printf("Le compte avec l'identifiant %d a bien été crée", $_POST["id_cli_new"]);
                ?><p><?php
            }else {
              ?><p><?php
                printf("Verifiez que tout les champs ne sont pas vide");
              ?><p><?php
            }
          }
        }

        //cookies du client 
        if(isset($_POST['id_cli']) && isset($_POST['mdp_cli'])){
          include "connexion.php";
          $results= $cnx->prepare('SELECT * FROM client WHERE idclient = :idclient AND mdp =:mdp ;');
          $results->execute(array(":idclient" => $_POST['id_cli'],":mdp" => $_POST['mdp_cli']));
          $nb_ligne = $results->rowCount();
          if ($nb_ligne > 0){
            foreach ($results as $line) {
              ?><p><?php
                printf("Vous êtes bien connecté en tant que : %s %s\n",$line['prenomc'],$line['nomc']);
              ?><p><?php
              /*on definie les cookies de la session*/

              $_SESSION['id'] = $_POST['id_cli'];
              $_SESSION['mdp'] = $_POST['mdp_cli'];
            
              setcookie("donnees_cli[prenom]",$line['prenomc'], time() + 5 * 60 * 1000);
              setcookie("donnees_cli[nom]",$line['nomc'], time() + 5 * 60 * 1000);
              setcookie("donnees_cli[datefin]",$line['datefin'], time() + 5 * 60 * 1000);
              setcookie("donnees_cli[adresse]",$line['adresse'], time() + 5 * 60 * 1000);
              setcookie("donnees_cli[courriel]",$line['courriel'], time() + 5 * 60 * 1000);
              setcookie("donnees_cli[premium]",$line['premium'], time() + 5 * 60 * 1000);
              setcookie("donnees_cli[fils1]",$line['fils1'], time() + 5 * 60 * 1000);
              setcookie("donnees_cli[fils2]",$line['fils2'], time() + 5 * 60 * 1000);
              setcookie("donnees_cli[fils3]",$line['fils3'], time() + 5 * 60 * 1000);
              setcookie("donnees_cli[fils4]",$line['fils4'], time() + 5 * 60 * 1000);

              //reset de l'utilisateur si jamais
              setcookie("id_utilisateur","", time() + -5 * 60 * 1000);  

              //ici j'ai essayé de faire des cookies dans un tableau de dim 2 comme dans le cours mais en lecture j'ai l'erreur illegal acces :/ 
              /*
              setcookie("donnees_cli", json_encode(array(
                "prenom_cli" => $line['prenomc'],
                "nom_cli" => $line['nomc'],
                "datefin_cli" => $line['datefin'],
                "adresse_cli" => $line['adresse'],
                "courriel_cli" => $line['courriel'],
                "premium_cli" => $premium_cli,
                "fils1_cli" => $line['fils1'],
                "fils2_cli" => $line['fils2'],
                "fils3_cli" => $line['fils3'],
                "fils4_cli" => $line['fils4']

              )), time() + 5 * 60 * 1000);
              */

              //un peu de deboguage.
              if(isset($_SESSION['id']) && isset($_SESSION['mdp'])){
                ?><p><?php
                  printf("Vos logs : %s %s",$_SESSION['id'],$_SESSION['mdp']);
                ?><p><?php
              } else {
                ?><p><?php
                  printf("session invalide");
                ?><p><?php
                if(!isset($_SESSION['id'])){
                  ?><p><?php
                    printf("il manque l'id ");
                  ?><p><?php
                }
                if(!isset($_SESSION['mdp'])){
                  ?><p><?php
                    printf("il manque le mdp ");
                  ?><p><?php
                }
              }
            }
          } else {
            ?><p><?php
              printf("Il n'existe pas de compte avec cette combinaison identifiant/mot de passe.");
            ?><p><?php
          }
        } else {
          ?><p><?php
          printf("Veuillez rentrer un identifiant et un mot de passe");
          ?><p><?php
        }

        //cookies de l'utilisateur 
        if(isset($_POST['id_utilisateur'])){
          setcookie("id_utilisateur",$_POST['id_utilisateur'], time() + 5 * 60 * 1000);
        }

      ?>

      <div style="display:flex;">
        <a class="login_form_input_button" href="index.html"> Retour à l'accueil </a>
        <a class="login_form_input_button" href="logout.php"> Se deconnecter </a>
      </div>

      
      <!-- après s'être connecté -->
      <?php
        //inclusion ou pas des info utilisateur
        if(isset($_SESSION['id']) && isset($_SESSION['mdp'])){
          include "loginfo.php";
        } else {
          ?><p><?php
            printf("Vous n'êtes pas encore connecté");
          ?></p><?php
        }
    
        //formulaire des info client
        if(isset($_SESSION['id']) && isset($_SESSION['mdp'])){
          ?>
          
          <!--maj date d'abonnement-->
          <div style="login_form">
            <form action="login.php" method="POST">
              <p class="login_form_p">Votre abonnement expire le : <?php
                                              printf("%s",$_COOKIE['donnees_cli']['datefin']);
                                            ?></p>
              <input class="login_form_input_button" type="submit" name="abo_new" value="Je renouvelle mon abonnement pendant un an"/>
              <input class="login_form_input_button" style="color: red" type="submit" name="abo_premium_new" value="Passer premium pendant un an"/>
               
              <p class="login_form_p"> allez hop hop hop par ici la cb</p>
            </form>
          </div>
          
          <!--maj des info client-->
          <div class="login_form">
            <h3>Vous pouvez modifier vos informations client:</h3>
            <form action="login.php" method="POST" >

              <label for="mdp_cli_update"><p class="login_form_p">Mot de passe</p></label>
              <input class="login_form_input" type="text" name="mdp_cli_update"  placeholder="Rentrer un nouveau mot de passe"/>
              <label for="nom_cli_update"><p class="login_form_p">Nom</p></label>
              <input class="login_form_input" type="text" name="nom_cli_update" placeholder="Rentrer un nom"/>
              <label for="prenom_cli_update"><p class="login_form_p">Prenom</p></label>
              <input class="login_form_input" type="text" name="prenom_cli_update" placeholder="Rentrer un prenom"/>
              <label for="adresse_cli_update"><p class="login_form_p">Adresse</p></label>
              <input class="login_form_input" type="text" name="adresse_cli_update" placeholder="Rentrer une adresse"/>
              <label for="courriel_cli_update"><p class="login_form_p">Courriel</p></label>
              <input class="login_form_input" type="text" name="courriel_cli_update" placeholder="Rentrer un courriel"/>

              <input class="login_form_input_button" type="submit" name="submit" value="Valider"/>
              <input class="login_form_input_button" type="reset" name="reset"  value="Effacer"/>

            </form>
          </div>
          <?php
        }
      ?>
      
    
    </div>
    
    
    

    <script>
      
      var register = document.getElementById("register");
      register.style.display = "none";
      var login = document.getElementById("login");


      function afficher_register() {
        register.style.display = "block";
        login.style.display = "none";
      }

      function afficher_login() {
        register.style.display = "none";
        login.style.display = "block";
      }

    </script>
  </body>

  <footer>
    <?php 
      include "footer.php";
    ?>
  </footer>
</html>