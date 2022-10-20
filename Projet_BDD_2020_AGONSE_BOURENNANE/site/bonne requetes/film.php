<?php
  session_start();
  
?>
<!DOCTYPE html>
<html lang="fr">
  <header>
    <meta charset="utf-8">
    <link rel="stylesheet" href="public/css/base.css">
    <link rel="stylesheet" href="public/css/header.css">
    <link rel="stylesheet" href="public/css/film.css">

    <title>Film</title>
    
    <?php 
      include "header.php";
    ?>

  </header>
  
  <body>
    <div class="container">
    <?php
      if(isset($_SESSION['id']) && isset($_SESSION['mdp'])){
        printf("Vos logs : %s %s",$_SESSION['id'],$_SESSION['mdp']);

        include "connexion.php";

        $results= $cnx->prepare('SELECT * FROM client WHERE idclient = :idclient AND mdp =:mdp ;');
        
        $results->execute(array(":idclient" => $_SESSION['id'],":mdp" => $_SESSION['mdp']));
        $nb_ligne = $results->rowCount();
        if ($nb_ligne > 0){
          ?>
            <div class="message">
              <h1>Vous voici sur la page des films</h1>
            </div>
              
            <h2>Film du catalogue:</h2>

            <ul class="lst_film">

              <?php 

                include "connexion.php";

                $results = $cnx->query("
                  SELECT idoeuvre,nomo,affiche,designation,prenoma,noma,synopsis
                  FROM oeuvre 
                    JOIN artiste ON oeuvre.realisateur=artiste.idartiste 
                    NATURAL JOIN appartient 
                    JOIN labbel ON labbel.idlabbel=appartient.idlabbel 
                  WHERE estSerie IS FALSE;
                ");
                
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
                $cnx = null;
              ?>

            </ul>

            <h2>Film que vous pourriez aimer:</h2>

            
            <ul class="lst_film">

              <?php 

                include "connexion.php";

                $results = $cnx->prepare("
                  SELECT idoeuvre,nomo,affiche,designation,prenoma,noma,synopsis 
                  FROM oeuvre 
                    JOIN artiste ON oeuvre.realisateur=artiste.idartiste
                    NATURAL JOIN appartient 
                    JOIN labbel ON labbel.idlabbel=appartient.idlabbel
                  WHERE labbel.idlabbel IN (
                    SELECT labbel.idlabbel 
                    FROM (
                      SELECT count(oeuvre.idoeuvre),labbel.idlabbel
                      FROM oeuvre 
                        JOIN regarde ON regarde.idoeuvre = oeuvre.idoeuvre 
                        JOIN appartient ON appartient.idoeuvre = oeuvre.idoeuvre 
                        JOIN labbel ON labbel.idlabbel=appartient.idlabbel 
                      WHERE regarde.idutilisateur = :idutilisateur 
                      GROUP BY labbel.idlabbel limit 5
                    ) as labbel
                  )
                  AND oeuvre.idoeuvre NOT IN (
                    SELECT regarde.idoeuvre 
                    FROM oeuvre 
                    JOIN regarde ON regarde.idoeuvre = oeuvre.idoeuvre 
                    JOIN appartient ON appartient.idoeuvre = oeuvre.idoeuvre 
                    JOIN labbel ON labbel.idlabbel=appartient.idlabbel
                  )
                  AND estSerie=FALSE;
                ");
                
                $results->execute(array(":idutilisateur" => $_COOKIE['id_utilisateur']));
                
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
                $cnx = null;
              ?>

            </ul>

            <h2>Choisissez un genre spécifique:</h2>

            <form action="film.php" method="post">
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
                ?>
                <ul class="lst_film">
                  <?php 

                    include "connexion.php";

                    $results = $cnx->prepare("
                      SELECT idoeuvre,nomo,affiche,labbel.idlabbel,designation,prenoma,noma,synopsis 
                      FROM oeuvre 
                        JOIN artiste ON oeuvre.realisateur=artiste.idartiste 
                        NATURAL JOIN appartient 
                        JOIN labbel ON labbel.idlabbel=appartient.idlabbel 
                      WHERE estSerie IS FALSE AND labbel.idlabbel = :idnombre;
                    ");

                    $results->execute(array(":idnombre" => $_POST['form_idlabbel']));
                    
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
                    $cnx = null;
                  ?>

                  </ul>
              <?php
              }
            ?>


          <?php
        }else{
          ?>
          <h1>Session invalide.</h1>
          <p>Essayez de vous reconnecter avec de bons identifiants <a href="login.php" style="color: red;">ici</a>.</p>
          <?php
        }
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
    </div>

    
  </body>

  <footer>
    <?php 
      include "footer.php";
    ?>
  </footer>
</html>