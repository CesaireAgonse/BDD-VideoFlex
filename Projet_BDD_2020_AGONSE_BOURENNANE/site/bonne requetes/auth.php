<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="public/css/base.css">
    <link rel="stylesheet" href="public/css/header.css">
    <link rel="stylesheet" href="public/css/film.css">

    <title>Film</title>
    
    <?php 
      include "header.php";
    ?>

  </head>
  
  <body>

  
    <div class="container">
      
      <?php 
        include "connexion.php";
      ?>

      <h3>Se connecter</h3>
          <form action="login.php" method="POST">
              <p>Identifiant <input type="text" name="name_user" size="40"/>
                  Mot de passe <input type="text" name="mdp_user" size="20"/><br/>
              </p>
              
              <p>
                  <input type="reset" name="reset" value="Effacez"/>
                  <input type="submit" name="submit" value="Validez"/>
              </p>
          </form>
          <h3>Créer un compte</h3>
          <form action="login.php" method="POST">
              <p>Definissez votre identifiant <input type="text" name="name_user2" size="40"/>
                  Definissez votre mot de passe2 <input type="text" name="mdp_user2" size="20"/><br/>
              </p>
              
              <p>
                  <input type="reset" name="reset" value="Effacez"/>
                  <input type="submit" name="submit" value="Validez"/>
              </p>
              
          </form>
          <a href="accueil.php"> Retour à l'accueil </a>

    
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
  </body>

  <footer>
    <?php 
      include "footer.php";
    ?>
  </footer>
</html>