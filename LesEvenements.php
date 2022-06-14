<?php
session_start();
include_once('db/connexiondb.php');

if (isset($_SESSION['id'])) {
    $afficher_evenement = $BDD->prepare("SELECT e.*, pseudo
    FROM evenement e
    LEFT JOIN utilisateur u ON u.id = e.id_user
    ORDER BY e.date_creation Desc");

$afficher_evenement->execute(array($_SESSION['id']));

}else{
    $afficher_evenement = $BDD->prepare("SELECT *
    FROM evenement");
}

$afficher_evenement->execute();

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

    <title>Evenement</title>
  </head>
  <body>

    <?php
   require_once('menu.php');
   ?>  

      <div class="container">
      <div class="row">


  <div class="col-sm-0 col-md-0 col-lg-0"></div>
  <div class="col-sm-12 col-md-12 col-lg-12">

     <?php
 if (isset($_SESSION['id'])) {

  ?>  
  <a href="ajout_evenement.php" class="btn btn-primary btn-lg active" role="button" aria-pressed="true" style="background: #D2B48C">Ajouter un Evenement</a>

    <?php
    }
 ?> 

 
            <?php
               foreach($afficher_evenement as $ae){ 
            ?>
    
    <div style="position: relative; display: flex; justify-content: center;">
        </div>
        <div>
      
        </div>
        <div style="margin-top: 10px; background: white; box-shadow: 0 5px 10px rgba(0,0,0, .09); padding: 5px 18px; border-radius: 10px">
     <a href="unEvenement.php?id=<?= $ae['id'] ?>"  style="color: #666; text-decoration: none; font-size: 28px;"> <?= $ae['titre']?></a> 

 <?php

        if(isset($ae['image'])){

        ?>
  <div style="background: url(<?= 'upload/' . $ae['id'] . '/' . $ae['image'] ?>) no-repeat center; background-size : cover; width: 120px;  border-radius: 50px;"></div>
     <?php

          }

        ?>


  <!--       <div><?= '<img src="' . $ae['image'] . '" />'; ?></div> -->
         <div style="border-top: 2px solid #EEE; padding:15px 0">
        
        <?= nl2br($ae['text']); ?>
      </div>
     <a href="unEvenement.php?id=<?= $ae['id'] ?>" style="color: #D2B48C;"> Lire plus</a>
     <div style=" font-size: 12px; padding-top: 15px; color: #ccc; font-style: italic; text-align: right; font-size: 12px;">
        Créer le <?= date_format(date_create($ae['date_creation']), 'D d M Y à H:i');  ?>
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
</div>

    
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>