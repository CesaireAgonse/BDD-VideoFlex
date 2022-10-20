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
    // conversion de la valeur get en int car php c'est bizarre ...
      $idnombre = intval($_GET['idartiste']);
      
    ?>
    <div class="container">
      <?php 

      include "connexion.php";
      
      $results= $cnx->prepare('SELECT * FROM artiste WHERE idartiste = :idnombre');
      $results->execute(array(":idnombre" => $idnombre));
      
      foreach ($results as $line) {

        ?>
        <h1>Fiche de <?php printf("%s %s",$line['prenoma'],$line['noma']);?> :</h1>
        <div>
          <img  src=<?php
                                        printf("%s",$line['photoprofilea']);
                                      ?>><img/>
        </div>
        <div class="details">
          <p>age:<?php
                      printf(" %s ans",$line['agea']);
                    ?></p>
        </div>
      <?php
        }
        
      ?>

      <h2>A realisé :</h2>
      <ul class="lst_film">
        <?php
          $results= $cnx->prepare('SELECT idoeuvre,nomo,affiche,designation,prenoma,noma,synopsis 
                                    FROM oeuvre 
                                    JOIN artiste ON oeuvre.realisateur=artiste.idartiste 
                                    NATURAL JOIN appartient 
                                    JOIN labbel ON labbel.idlabbel=appartient.idlabbel 
                                  WHERE oeuvre.realisateur=:idnombre;');
          $results->execute(array(":idnombre" => $idnombre));
          
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
              <p>genre : <?php
                            printf("%s",$line['designation']);
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
      
      
      <h2>Joue dans :</h2>

      <ul class="lst_film">
        <?php
          $results= $cnx->prepare('SELECT oeuvre.idoeuvre,nomo,affiche,designation,prenoma,noma,synopsis 
                                  FROM oeuvre 
                                    JOIN artiste ON oeuvre.realisateur=artiste.idartiste 
                                    NATURAL JOIN appartient 
                                    JOIN labbel ON labbel.idlabbel=appartient.idlabbel 
                                    JOIN joue ON joue.idoeuvre = oeuvre.idoeuvre 
                                  WHERE joue.idartiste=:idnombre;');
          $results->execute(array(":idnombre" => $idnombre));
          
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
              <p>genre : <?php
                            printf("%s",$line['designation']);
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
    ?>
    
    
  </body>

  <footer>
    <?php 
      include "footer.php";
    ?>
  </footer>
</html>