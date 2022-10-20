<?php
  if(!isset($_COOKIE['id_utilisateur'])){
    include "connexion.php";
    ?>
    <h2><?php if(isset($_COOKIE['donnees_cli'])){
                printf("Bonjour %s %s",$_COOKIE['donnees_cli']['prenom'],$_COOKIE['donnees_cli']['nom']);
              } else {
                $results= $cnx->prepare('SELECT * FROM client WHERE idclient = :idnombre;');
                $results->execute(array(":idnombre" => $_SESSION['id']));
                foreach ($results as $line) {
                  printf( "Bienvenue %s %s\n",$line['prenomc'],$line['nomc']);
                }
              }
              ?> vous êtes sur votre page perso:</h2>
    
    <h3>Choisissez votre profil utilisateur:</h3>

    <form class="loginfo_selec" action="login.php" method="POST" >
    <?php
    
      $results= $cnx->prepare('
        SELECT idutilisateur,pseudo,ageu,photoprofileu 
        FROM utilisateur 
          JOIN client ON  utilisateur.idutilisateur = client.fils1 
            OR utilisateur.idutilisateur = client.fils2 
            OR utilisateur.idutilisateur = client.fils3 
            OR utilisateur.idutilisateur = client.fils4 
        WHERE idclient = :idnombre;
      ');

      $results->execute(array(":idnombre" => $_SESSION['id']));

      foreach ($results as $line) {
      ?>
        <button class="loginfo_profile" type="submit" name="id_utilisateur" value=<?php
                                                                                    printf("%s",$line['idutilisateur']);
                                                                                  ?>>
          <h3 ><?php printf("%s",$line['pseudo']); ?></h3>
          <img class="loginfo_profile_photo" title="PhotoProfileUtilisateur" src=<?php
                                                                        if ($line['photoprofileu'] == ""){
                                                                          printf("public/img/OK.jpg");
                                                                        }else{
                                                                          printf("%s",$line['photoprofileu']);
                                                                        }
                                                                        ?>><img/>
          <p>age : <?php printf("%s",$line['ageu']); ?> ans</p>
        </button>
      <?php
      }
    ?>
    </form>
  <?php
  } else {
    ?>
    <h2><?php
      include "connexion.php";
      $results= $cnx->prepare('SELECT * FROM utilisateur WHERE idutilisateur = :idnombre;');
      $results->execute(array(":idnombre" => $_COOKIE['id_utilisateur']));
      foreach ($results as $line) {
        printf( "Bienvenue %s\n",$line['pseudo']);
      }?> vous êtes sur votre page perso: </h2>

    <h3>Votre historique:</h3>
    <ul class="lst_film">
    <?php
      $results= $cnx->prepare('
        SELECT * 
        FROM oeuvre 
          JOIN regarde ON oeuvre.idoeuvre=regarde.idoeuvre 
          JOIN artiste ON oeuvre.realisateur=artiste.idartiste 
          JOIN appartient ON appartient.idoeuvre=oeuvre.idoeuvre 
          JOIN labbel ON labbel.idlabbel=appartient.idlabbel 
        WHERE idutilisateur = :idnombre;
      ');
      
      $results->execute(array(":idnombre" => $_COOKIE['id_utilisateur']));
     
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
          <p><?php
                printf("%s",$line['datedervisionnage']);
              ?></p>
          </a>
    <?php  
    }
      
    $cnx = null;
    ?>
    </ul>
    
    
    <div class="login_form">
      <h3>Vous pouvez modifier vos informations utilisateur:</h3>
      <form action="login.php" method="POST" >

        <label for="pseudo_uti"><p class="login_form_p">Pseudo</p></label>
        <input class="login_form_input" type="text" name="pseudo_uti"  placeholder="Rentrer un pseudo"/>
        <label for="age_uti"><p class="login_form_p">Age</p></label>
        <input class="login_form_input" type="text" name="age_uti" placeholder="Rentrer un age"/>

        <input class="login_form_input_button" type="submit" name="submit" value="Valider"/>
        <input class="login_form_input_button" type="reset" name="reset"  value="Effacer"/>

        <button class="login_form_input_button" type="submit" name="id_utilisateur" value="">
          <p>Ou changer de profile<p>
        </button>
      </form>
    </div>
    
<?php
  }
?>
