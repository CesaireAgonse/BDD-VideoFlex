<!DOCTYPE html>
<html>
  
  <link rel="stylesheet" href="public/css/header.css">
    <div id="header">
      <div class="container">
        <div class="header_navbar">
          
          <div class="header_navbar_left">
          <div class="header_navbar_logo">
              <img class="header_navbar_logo_img" src="public/img/logo.png" alt ="test" title="VidéoFlex le meilleur site de streaming français">
            </div>
            
            <div class="header_menu">
              <a href="index.html" class="header_navbar_menu_link">accueil</a>
              <a href="film.php" class="header_navbar_menu_link">film</a>
              <a href="serie.php" class="header_navbar_menu_link">serie</a>
            
            </div>
          </div>

          <div class="header_navbar_right">
            
            <?php
              if(isset($_COOKIE['id_utilisateur'])){
                include "connexion.php";

                $results= $cnx->prepare('SELECT * FROM utilisateur WHERE idutilisateur = :idnombre');
                $results->execute(array(":idnombre" => $_COOKIE['id_utilisateur']));
                foreach ($results as $line) {
                  ?>
                  <a href="login.php" class="header_navbar_menu_link"><?php
                                                                        printf("%s",$line['pseudo']);
                                                                      ?></a><?php
                }
              
              }else{
              
                ?>
                  <a href="login.php" class="header_navbar_menu_link">Se connecter</a>
                <?php
              }
              
            ?>

            <a href="logout.php" class="header_navbar_menu_link">deconnexion</a>

          </div>
        
        </div>
      </div>
    </div>

    <script>
      var prevScrollpos = window.pageYOffset;
      window.onscroll = function() {
        var currentScrollPos = window.pageYOffset;
        if (prevScrollpos > currentScrollPos) {
          document.getElementById("header").style.top = "0";
        } else {
          document.getElementById("header").style.top = "-80px";
        }
        prevScrollpos = currentScrollPos;
      }
    </script>
  
</html>
