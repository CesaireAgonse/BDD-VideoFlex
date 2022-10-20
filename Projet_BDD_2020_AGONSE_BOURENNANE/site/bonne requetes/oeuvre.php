<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="public/css/base.css">
    
    <link rel="stylesheet" href="public/css/oeuvre.css">
    <link rel="stylesheet" href="public/css/film.css">

    <title>Film</title>
    
    <?php 
      include "header.php";
    ?>

  </head>
  
  <body>
    <?php
      // conversion de la valeur get en int car php SEDLAMERD
      $idoeuvre = intval($_GET['idoeuvre']);
      //printf("%s %d",$_GET['idoeuvre'],$idnombre);
    ?>
    <div class="container">
      <?php  
      if(isset($_SESSION['id']) && isset($_SESSION['mdp'])){
        include "connexion.php";
      
        $results= $cnx->prepare('
          SELECT nomo,affiche,noma,prenoma,realisateur,appartient.idlabbel,designation,duree,numopus,estSerie,noteglobale,datesort,synopsis,idsaga
          FROM oeuvre 
            JOIN artiste ON oeuvre.realisateur=artiste.idartiste
            NATURAL JOIN appartient 
            JOIN labbel ON labbel.idlabbel=appartient.idlabbel 
            WHERE oeuvre.idoeuvre = :idnombre
        ');
        
        $results->execute(array(":idnombre" => $idoeuvre));
        
        foreach ($results as $line) {
          $idsaga=$line['idsaga'];
          ?>
          <h1><?php printf("%s",$line['nomo']);?></h1>
          <div class="oeuvre_img_container">
            <img class="oeuvre_img" src=<?php
                                          printf("%s",$line['affiche']);
                                        ?>><img/>
          </div>
          <div class="details">
            <p>durée:<?php
                        printf(" %s secondes",$line['duree']);
                      ?></p>
            <a href=<?php
                      printf("artiste.php?idartiste=%s",$line['realisateur']);
                    ?>>realisteur:<?php
                                    printf(" %s %s",$line['prenoma'],$line['noma']);
                                  ?></a>
            <p>episode:<?php
                            printf( " %s",$line['numopus']);
                          ?></p>
            <p> genre :<?php
                        printf(" %s",$line['designation']);
                        $idlabbel = intval($line['idlabbel']);
                      ?></p>
            <p>date de sortie:<?php
                                printf( " %s",$line['datesort']);
                              ?></p>
          </div>
          <h3> La commu à propos de cette oeuvre :</h3>
          <div class="details">
            <p>note:<?php
                        printf( " %s/100",$line['noteglobale']);
                    ?></p>
  
            <p> les genre les plus données:</p>
            <?php
              $resultsbis= $cnx->prepare('
                SELECT pseudo,designation,nomo 
                FROM suggestion 
                  JOIN utilisateur ON utilisateur.idutilisateur = suggestion.idutilisateur 
                  JOIN labbel ON suggestion.idlabbel=labbel.idlabbel 
                  JOIN oeuvre ON oeuvre.idoeuvre = suggestion.idoeuvre 
                WHERE suggestion.idoeuvre=:idnombre 
                ORDER BY designation LIMIT 5;
              ');

              $resultsbis->execute(array(":idnombre" => $idoeuvre));
              
              foreach ($resultsbis as $ligne) {
                ?>
                <p><?php
                        printf( "%s",$ligne['designation']);
                    ?></p></p>
                <?php
              }
            ?>
          </div> 
          <h3> Synopsis</h3>
          <tex class="synopis"><?php
                  printf( "%s",$line['synopsis']);
                ?></text>
        <?php
          }
          
        ?>
        <h2>Casting :</h2>
        <ul class="casting">
          <?php
            $results= $cnx->prepare('
              SELECT idartiste,noma,prenoma,photoprofilea,roleartiste 
              FROM artiste 
                NATURAL JOIN joue 
              WHERE idoeuvre=:idnombre;
            ');
            
            $results->execute(array(":idnombre" => $idoeuvre));
            
            foreach ($results as $line) {
              ?>
              <a class="casting_acteur" href=<?php
                                                  printf("artiste.php?idartiste=%s",$line['idartiste']);
                                                  ?>>
                <p><?php
                    printf( "%s %s",$line['prenoma'],$line['noma']);
                  ?></p>
                <img class="casting_acteur_img" src=<?php
                                                  printf("%s",$line['photoprofilea']);
                                                ?>><img/>
                <p>rôle: <?php
                          printf( "%s",$line['roleartiste']);
                        ?></p>
              </a>
            <?php
              }
            ?>
        </ul>
        
        
        <h2>Proposer un genre pour cette oeuvre:</h2>
  
        <form action=<?php
                        printf("oeuvre.php?idoeuvre=%s",$idoeuvre);
                      ?> method="post">
          <?php
  
              include "connexion.php";
  
              $results= $cnx->prepare('SELECT idlabbel,designation FROM labbel ORDER BY designation;');
              $results->execute();
              
              foreach ($results as $line) {
                ?>
                <p>
                  <input type="radio" name="form_idlabbel" value=<?php
                                                            printf( "%s",$line['idlabbel']);
                                                          ?> /><?php
                                                                printf( "%s",$line['designation']);
                                                              ?>
                </p>
              <?php
              }
              ?>
          <p>
            <input type="submit" name="submit" value="Valider" />
          </p>
        </form>
        <?php
          if(isset($_POST['form_idlabbel'])){
            include "connexion.php";
            $results= $cnx->prepare("INSERT INTO suggestion(idutilisateur,idoeuvre,idlabbel) VALUES
            (:idutilisateur,:idoeuvre,:idlabbel);");
            $results->execute(array(":idutilisateur" => $_COOKIE["id_utilisateur"],
            ":idoeuvre" => $idoeuvre,
            ":idlabbel" => $_POST['form_idlabbel']
            ));
          }          
        ?>
        <h2>Dans la même serie :</h2>
        
        <ul class="lst_film">
          <?php
          $results= $cnx->prepare('
            SELECT idoeuvre,nomo,affiche,designation,prenoma,noma,synopsis 
            FROM oeuvre 
              JOIN artiste ON oeuvre.realisateur=artiste.idartiste 
              NATURAL JOIN appartient 
              JOIN labbel ON labbel.idlabbel=appartient.idlabbel 
              NATURAL JOIN saga 
            WHERE oeuvre.idsaga=:idnombre;
          ');
          
          $results->execute(array(":idnombre" => $idsaga));
          foreach ($results as $line) {
            ?>
            <a class="lst_film_element" href=<?php
                                                  printf("oeuvre.php?idoeuvre=%s",$line['idoeuvre']);
                                                  ?>>
                <h3 class="lst_film_element_titre"><?php 
                                                        printf("%s",$line['nomo']);
                                                    ?></h3>
                <img class="lst_film_element_affiche" title="affiche" src=<?php
                                                                            printf("%s",$line['affiche']);
                                                                          ?>><img/>
                <p>genre:<?php
                        printf( " %s",$line['designation']);
                        ?></p>
                <p>realisateur : <?php
                              printf("%s %s",$line['prenoma'],$line['noma']);
                            ?></p>
                <p class="lst_film_element_synopsis"><?php
                                                      printf("%s",$line['synopsis']);
                                                    ?></p>
              </a>
          <?php
            }
          ?>
        </ul>
  
        <h2>Du meme genre:</h2>
  
        <ul class="lst_film">
          <?php
            $results= $cnx->prepare('
              SELECT idoeuvre,nomo,affiche,designation,prenoma,noma,synopsis 
              FROM oeuvre 
                JOIN artiste ON oeuvre.realisateur=artiste.idartiste 
                NATURAL JOIN appartient 
                JOIN labbel ON labbel.idlabbel=appartient.idlabbel 
                NATURAL JOIN saga 
              WHERE idoeuvre!=:idnombre 
                AND oeuvre.estSerie IS FALSE 
                AND appartient.idlabbel=:idlabbel
            ');
            
            $results->execute(array(":idnombre" => $idoeuvre,":idlabbel" => $idlabbel));
            foreach ($results as $line) {
              ?>
              <a class="lst_film_element" href=<?php
                                                    printf("oeuvre.php?idoeuvre=%s",$line['idoeuvre']);
                                                    ?>>
                  <h3 class="lst_film_element_titre"><?php 
                                                          printf("%s",$line['nomo']);
                                                      ?></h3>
                  <img class="lst_film_element_affiche" title="affiche" src=<?php
                                                                              printf("%s",$line['affiche']);
                                                                            ?>><img/>
                  <p>genre:<?php
                          printf( " %s",$line['designation']);
                          ?></p>
                  <p>realisateur : <?php
                                printf("%s %s",$line['prenoma'],$line['noma']);
                              ?></p>
                  <p class="lst_film_element_synopsis"><?php
                                                        printf("%s",$line['synopsis']);
                                                      ?></p>
                </a>
            <?php
              }
            ?>
        </ul>
  
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