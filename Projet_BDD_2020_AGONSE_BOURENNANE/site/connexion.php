<?php 
/*
 * Création d'objet PDO de la connexion qui sera représentée par la variable $cnx.
 */
$user = 'cagonse';
$pass = 'bddczer12';
try {
    $cnx = new PDO('pgsql:host=sqletud.u-pem.fr;dbname=cagonse_db', $user, $pass);
    ?><p class="bdd_info"><?php
    //pour le debug
    //echo "connecté à la bdd";
    ?></p><?php
} catch (PDOException $e) {
    ?><p class="bdd_info"><?php
    //pour le debug
    //echo "ERREUR : La connexion à la bdd a échouée";
    ?></p><?php
}
?>