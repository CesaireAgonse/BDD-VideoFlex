<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="public/css/base.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <link rel="stylesheet" href="public/css/login.css">


    <title>Film</title>
    
    <?php 
      include "header.php";
    ?>

    <?php 
    
    if(isset($_POST["timecode"])){
      if($_POST["timecode"] != ""){
        include "connexion.php";
        $results= $cnx->prepare('UPDATE regarde SET timecode = :timecode WHERE regarde.idutilisateur = :idnombre AND regarde.idoeuvre = :idoeuvre;');
        $results->execute(array(":idnombre" => $_COOKIE['id_utilisateur'],
                                ":idoeuvre" => intval($_GET['idoeuvre']),
                                ":timecode" =>  $_POST["timecode"]));
        include "connexion.php";
        $results= $cnx->prepare('UPDATE regarde SET datedervisionnage = :datedervisionnage WHERE regarde.idutilisateur = :idnombre AND regarde.idoeuvre = :idoeuvre;');
        $results->execute(array(":idnombre" => $_COOKIE['id_utilisateur'],
                                ":idoeuvre" => intval($_GET['idoeuvre']),
                                ":datedervisionnage" =>  date('d/m/Y')));
      } 
    }
    if(isset($_POST["timecode_new"])){
      if($_POST["timecode_new"] != ""){
        include "connexion.php";
        $results= $cnx->prepare("INSERT INTO regarde (idutilisateur,idoeuvre,datevisionnage,timeCode) VALUES
                                (:idnombre,:idoeuvre,:datevisionnage,:timecode);");
        $datevision = strtotime('2000-12-12');
        
        /*
        $results->execute(array(":idnombre" => $_COOKIE['id_utilisateur'],
                                ":idoeuvre" => intval($_GET['idoeuvre']),
                                ":datevisionnage" =>  $datevision,
                                ":timecode" =>  $_POST["timecode"]
                              ));
        */
        
      }
    }
    
    ?>

  </head>
  
  <body>
    <?php
      // conversion de la valeur get en int car php c'est bizarre...
      $idoeuvre = intval($_GET['idoeuvre']);
      
    ?>
    <div class="container">
      <?php  

      
      if(isset($_SESSION['id']) && isset($_SESSION['mdp'])){
        $annee = $_COOKIE['donnees_cli']['datefin'];
        if(strtotime(date('d/m/Y',strtotime($annee.'+ 0 years'))) > strtotime(date('d/m/Y'))){
          include "connexion.php";
      
          $results= $cnx->prepare('SELECT nomo,timecode,duree FROM oeuvre JOIN regarde ON regarde.idoeuvre= oeuvre.idoeuvre  
                                    WHERE oeuvre.idoeuvre = :idnombre
                                    AND regarde.idutilisateur = :idutilisateur');
          $results->execute(array(":idnombre" => $idoeuvre,":idutilisateur" => $_COOKIE['id_utilisateur']));
          $nb_ligne = $results->rowCount();
          if ($nb_ligne > 0){
            foreach ($results as $line) {
              ?>
              <h1>Vous avez regardé <?php printf("%s secondes sur %s de %s",$line['timecode'],$line['duree'],$line['nomo'])?><h1>
    
              
              <div class="login_form">
                
                <form action=<?php printf("regarde.php?idoeuvre=%s",$idoeuvre);?> method="POST">
                  <label for="timecode"><p class="login_form_p">Vous avez regardé jusqu'a : </p></label>
                  <input class="login_form_input" type="text" name="timecode"  placeholder="Rentrer un timecode"/>
                  
                        
                  <input class="login_form_input_button" type="submit" name="submit" value="Valider"/>
                  <input class="login_form_input_button" type="reset" name="reset"  value="Effacer"/>
                </form>
              </div>
    
              <?php
              }
          }else {
            include "connexion.php";
      
            $results= $cnx->prepare('SELECT nomo FROM oeuvre  
                                      WHERE oeuvre.idoeuvre = :idnombre;' );
            $results->execute(array(":idnombre" => $idoeuvre));
            foreach ($results as $line) {
              ?>
                <h1>Vous n'avez pas encore regardé <?php printf("%s",$line['nomo'])?><h1>
      
                
                <div class="login_form">
                  
                  <form action=<?php printf("regarde.php?idoeuvre=%s",$idoeuvre);?> method="POST">
                    <label for="timecode_new"><p class="login_form_p">Vous avez regardé jusqu'a : </p></label>
                    <input class="login_form_input" type="text" name="timecode"  placeholder="Rentrer un timecode"/>
                    
                          
                    <input class="login_form_input_button" type="submit" name="submit" value="Valider"/>
                    <input class="login_form_input_button" type="reset" name="reset"  value="Effacer"/>
                  </form>
                </div>
      
              <?php
            }
          }
          
        }else{

          ?>
          <h1>Votre abonnement n'est plus valable.</h1>
          <p>En payer un nouveau <a href="login.php" style="color: red;">ici</a>.</p>
          <?php 
        }
        ?>
  
      </div>
      <?php
        $cnx = null;
      
      } else {
        if(!isset($_SESSION['id'])){
          printf("il manque l'id ");
        }
        if(!isset($_SESSION['mdp'])){
          printf("il manque le mdp ");
        }
        ?>
        <h1>Vous n'êtes pas encore connecté.</h1>
        <p>Se connecter <a href="login.php" style="color: red;">ici</a>.</p>
        <?php
        
      }
      ?>  
    
    
  </body>

  <footer>
    <?php 
      include "footer.php";
    ?>
  </footer>
</html>