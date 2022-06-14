<?php
session_start();
include_once('db/connexiondb.php');

if (isset($_SESSION['id'])) {
    $afficher_membres = $BDD->prepare("SELECT *
    FROM utilisateur
    WHERE id <> ?");

$afficher_membres->execute(array($_SESSION['id']));

}else{
    $afficher_membres = $BDD->prepare("SELECT *
    FROM utilisateur");
}

$afficher_membres->execute();


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

    <title>Membres</title>
  </head>
  <body>

    <?php
   require_once('menu.php');
   ?>  

      <div class="container">
      <div class="row">

            <?php
               foreach($afficher_membres as $am){ 
            ?>
    <div class="col-sm-3"> 
    <div class="membre--corps">
    <div style="position: relative; display: flex; justify-content: center;">

        <?php

        if(isset($am['avatar'])){

        ?>
  <div style="background: url(<?= 'upload/' . $am['id'] . '/' . $am['avatar'] ?>) no-repeat center; background-size : cover; width: 200px; height: 120px;"></div>
     <?php

          }else{

        ?>
<div style="background: url('upload/default/th.png') no-repeat center; background-size : cover; width: 120px; height: 120px; border-radius: 50px;"></div>
        <?php
          }
    ?>

        </div>
        <div>
       <?= $am['pseudo']?>
        </div>
        <div class="membre-btn">
        <a href="voir-profil.php?id=<?= $am['id'] ?>" class="membre-btn-voir">Voir plus</a> 
        </div>
     </div> 
    </div>
    <?php
   }
?>
    </div>
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