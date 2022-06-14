A<?php
session_start();

include_once('db/connexiondb.php');

if (!isset($_SESSION['id'])) {
  header('Location: LesEvenements.php');
  exit;
}
if (!empty($_POST)) {
  extract($_POST);
  $valid = true;

  if (isset($_POST['ajout-evenement'])) {
    
    $titre = (string) htmlentities(trim($titre));
    $contenu = (string) htmlentities(trim($contenu));
    $categorie = (int) htmlentities(trim($categorie));


  if (empty($titre)) {
    $valid = false;
    $er_titre = ("il faut mettre un titre");
  }

  if (empty($contenu)) {
    $valid = false;
    $er_contenu = ("il faut mettre un contenu");
  }

  if (empty($categorie)) {
    $valid = false;
    $er_categorie = "le thème ne peut pas etre vide";
  }else{

    $verif_cat =$DB->query("SELECT id, titre
      FROM categorie
      WHERE id = ?",
      array($categorie));

    $verif_cat = $verif_cat->fetch();

    if (!isset($verif_cat['id'])) {
      $valid = false;
      $er_categorie = "Ce thème n'existe pas";
    }
  }
    if ($valid) {
      $date_creation = date('Y-m-d H:i:s');

      $DB->insert("INSERT INTO evenement (id_user, titre, text,  date_creation, id_categorie) VALUES
        (?,?,?,?,?)",
        array($_SESSION['id'], $titre, $contenu,  $date_creation, $categorie));
      header('Location: LesEvenements.php');
      exit;
    }
  }
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

    <title>Ajout evenement</title>
  </head>
  <body>

    <?php
   require_once('menu.php');
   ?>  
    <div class="container">
    <div class="row">
  
    <div class="col-sm-12 col-md-12 col-lg-12">
     <div class="cdr-ins"></div>
         <h1>Ajouter un Evenement </h1>
         <form method="Post">

          <?php
          // S'il y a une erreur sur le nom alors on affiche
          if (isset($er_categorie)) {
          ?>
             <div class="er_msg"><?= $er_categorie ?></div>
             <?php
          }
          ?>
         <div class="form-group">
           <div class="input-group mb-3">
             <select name="categorie" class="custom-select" id="inputGroupSelect01">

              <?php
              if (empty($categorie)) {
                ?>
               <option selected>Sélectionner votre thème</option>
                <?php
                }else{ 
                ?>
                <option value="<?= $categorie ?>"><?= $verif_cat['titre'] ?></option>
                <?php
                   }
              ?>
                 <?php
                 $req_cat = $DB->query("SELECT * 
                  FROM categorie");

                 $req_cat = $req_cat->fetchAll();

                 foreach($req_cat as $rc){
                  ?>
                
                  <option value="<?=$rc['id'] ?>"><?= $rc['titre'] ?></option>
                  <?php
                    }
                 ?>
             </select>
           </div>
         </div>
        <?php
        if (isset($er_titre)) {
         ?>
         <div class="er_msg"><?= $er_titre ?></div>
         <?php
        }
        ?>
         <div class="form-group">
          <input class="form-control" type="text" name="titre" placeholder="Votre titre" >
         </div>
         <?php
        if (isset($er_contenu)) {
         ?>
         <div class="er_msg"><?= $er_contenu ?></div>
         <?php
        }
        ?>
         <div class="form-group">
          <textarea class="form-control" rows="4" placeholder="Décrivez l'évènement" name="contenu"></textarea>
           
         </div>

         <form method="Post" enctype="multipart/form-data">
              <div>
              Fichier : <input type="file" name="avatar">
            </div>
            <br>
            
         <div class="form-group">
           <button class="btn btn-primary" type="submit" name="ajout-evenement" style="background:#D2B48C">Envoyer</button>
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
