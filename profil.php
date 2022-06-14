<?php
session_start();

include_once('db/connexiondb.php');

if (!isset($_SESSION['id']))  {
   header('Location: index.php');
    exit;
}

$utilisateur_id = (int) $_SESSION['id'];

if (empty($utilisateur_id)){
    header('Location: membres.php');
    exit;
} 
$req = $BDD->prepare("SELECT u.*, d.departement_nom 
    FROM utilisateur u 
    iNNER JOIN departement d ON d.departement_code = u.departement 
    WHERE u.id =?");

    $req->execute(array($utilisateur_id));

    $voir_utilisateur = $req->fetch();

    if (!isset($voir_utilisateur['id'])) {
        header('Location: membres.php');
        exit;
    }

    function age($date){
      $age = date('Y') - date('Y', strtotime($date));

      if(date('md') < date('md', strtotime($date))){
        return $age -1;
      }
      return $age;
    }

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

    <title>Profil de <?= $voir_utilisateur['pseudo'] ?></title>
  </head>
  <body>

    <?php
   require_once('menu.php');
   ?>  
  <div class="container">
      <div class="row">
         <div class="col-sm-12"> 
          <div class="membre--corps">
              <div style="display: flex; justify-content: center;">
                <?php

        if(isset($voir_utilisateur['avatar'])){

        ?>
  <div style="background: url(<?= 'upload/' . $voir_utilisateur['id'] . '/' . $voir_utilisateur['avatar'] ?>) no-repeat center; background-size : cover; width: 200px; height: 120px;"></div>
     <?php

          }else{

        ?>
                <div style="background: url('upload/default/th.png') no-repeat center; background-size : cover; width: 120px; height: 120px; border-radius: 50px;"></div>
            
             <?php
          }
    ?>
            </div>
           
            <div style="font-size: 15px; font-weight: bold; margin-top: 10px">
            <?= $voir_utilisateur['pseudo'] .' ( ' . age($voir_utilisateur['date_naissance']) .' ans )' ?>
          </div>
    <div>
        Habite à <?= $voir_utilisateur['departement_nom'] ?>
    </div>  
         <div>
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