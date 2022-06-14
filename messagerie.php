<?php
session_start();

include_once('db/connexiondb.php');

if (!isset($_SESSION['id']))  {
   header('Location: index.php');
    exit;
}

$req = $BDD->prepare("SELECT count(id) AS nb_amis
    FROM relation
    WHERE (id_demandeur = id_receveur= :id) AND statut = 2");

$req->execute(array('id' => $_SESSION['id']));

$nb_conversation = $req->fetch();

// echo $nb_conversation['nb_amis'];

$req = $BDD->prepare("SELECT u.pseudo, u.id, m.message, m.date_message, m.id_from, m.lu
FROM (
   SELECT IF(r.id_demandeur = :id, r.id_receveur, r.id_demandeur) id_utilisateur , MAX(m.id) max_id
    FROM relation r
    LEFT JOIN messagerie m ON ((m.id_from, m.id_to) = (r.id_demandeur, r.id_receveur) OR (m.id_from, m.id_to) = (r.id_receveur, r.id_demandeur))
    WHERE (r.id_demandeur = :id OR r.id_receveur = :id) AND r.statut = 3
    GROUP BY IF (m.id_from = :id, m.id_to, m.id_from), r.id) AS DM
LEFT JOIN messagerie m ON m.id = DM.max_id
LEFT JOIN utilisateur u ON u.id = DM.id_utilisateur
ORDER BY m.date_message DESC");

    $req->execute(array('id' => $_SESSION['id']));

    $afficher_conversations = $req->fetchAll();
  
?>

<!Doctype html>
<html lang="fr">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

     <link rel="stylesheet" href="css/style.css">

    <title>Messagerie</title>
  </head>
  <body>

    <?php
   require_once('menu.php');
   ?>  
  <div class="container">
      <div class="row">
         <div class="col-sm-12"> 
            <div class="afficher-messages">
                <h5>Mes conversations</h5>
            <table>
          <?php
             foreach($afficher_conversations as $ac){
            ?>
            <tr>

                <td>
                    <a href="message.php?id=<?= $ac['id'] ?>">
                    <?= $ac['pseudo'] ?> <br>
                   </a>
               </td>
                <td>
                    <?php
                    if($ac['id_from'] <> $_SESSION['id'] && $ac['lu'] == 1){ 
                    ?>
                 Nouveau
                 <?php
             }
             ?>
                </td>
                <td>
                 <?php 
                    if (isset($ac['message'])) {
                        echo $ac['message'];
                 }else{
                    echo '<b>Dites lui bonjours ! </b>';
                  }
                ?>      
                </td>
                 
                <td>
                   <?php 
                   if (isset($ac['date_message'])) {
                       echo date('d-m-Y à M:i:s', strtotime($ac['date_message']));
                       ?>
                       <?php  
                   }
              ?>          
             </td>
            </tr>
            <?php

          }
          ?>
      </table>
      </div>
  </div>
    </div>
   </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>