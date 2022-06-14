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
    INNER JOIN departement d ON d.departement_code = u.departement 
    WHERE u.id =?");

    $req->execute(array($utilisateur_id));

    $voir_utilisateur = $req->fetch();

    if (!isset($voir_utilisateur['id'])) {
        header('Location: membres.php');
        exit;
    }


if(!empty($_POST)){ 
  extract($_POST);
  $valid =(boolean)true;
  
    if(isset($_POST['modifier'])){
       $pseudo = (String) trim ($pseudo);
       $mail = (String) strtolower(trim ($mail));  
       $departement = (String) trim($departement);
    

     if(empty($pseudo)){
      $valid = false;
      $err_pseudo = "Veuillez renseigner ce champs !";
    }else{
      $req = $BDD->prepare("SELECT id
        FROM utilisateur
        WHERE pseudo = ? AND id <> ?");

      $req->execute(array($pseudo, $_SESSION['id']));
      $utilisateur = $req->fetch();

      if(isset($utilisateur['id'])){
        $valid = false;
        $err_pseudo = "Ce pseudo existe déjà";
      }
    }

     if(empty($mail)){
      $valid = false;
      $err_mail = "Veuillez renseigner ce champs !";
    }else{
      $req = $BDD->prepare("SELECT id
        FROM utilisateur
        WHERE mail = ? AND id <> ?");

      $req->execute(array($mail, $_SESSION['id']));
      $utilisateur = $req->fetch();

      if(isset($utilisateur['id'])){
        $valid = false;
        $err_mail = "Ce mail existe déjà";
      }
    }

  $req = $BDD->prepare("SELECT departement_id, departement_code, departement_nom
        FROM departement
        WHERE departement_code = ?");
      $req->execute(array($departement));

      $verif_departement = $req->fetch();

     if(!isset($verif_departement['departement_id'])){
      $valid = false;
      $err_departement = "Veuillez renseigner ce champs !";
    }
  
   if($valid){

     $req = $BDD->prepare("UPDATE utilisateur SET pseudo = ?, mail = ?, departement = ? WHERE id = ?");

      $req->execute(array($pseudo, $mail, $verif_departement['departement_code'], $_SESSION['id ']));

      header('Location:profil.php' );
      exit;
      }

   }elseif(isset($_POST['envoyer'])){
     $dossier = 'upload/' . $_SESSION['id'] . "/";

     if (!is_dir($dossier)) {
       mkdir($dossier);
     }

     $fichier = basename($_FILES['avatar']['name']);

     if(move_uploaded_file($_FILES['avatar']['tmp_name'], $dossier . $fichier)){

       if(file_exists("upload" .$_SESSION['id'] . '/' . $_SESSION['avatar']) && isset($_SESSION['avatar'])){
        unlink("upload" .$_SESSION['id'] . '/' . $_SESSION['avatar']);
       }

      $req = $BDD->prepare("UPDATE utilisateur SET avatar = ? WHERE id = ?");

      $req->execute(array($fichier,$_SESSION['id']));

      $_SESSION['avatar'] = $fichier;

        header('Location:editer-profil.php');
        exit;
       
     } else { 
         
         header('Location:editer-profil.php');
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
      

    <title>Editer mon profil</title>
  </head>
  <body>

    <?php
   require_once('menu.php');
   ?>  
  <div class="container">
      <div class="row">
         <div class="col-sm-12"> 
          <div class="membre-corps" style="text-align: left">
            <form method="Post">
             
            <div class="form">
                <?php
                     if(isset($err_pseudo)){
                        echo $err_pseudo;
                  }
                  if (!isset($pseudo)) {
                      $pseudo = $voir_utilisateur['pseudo'];
                  }

                ?>
                <label>Pseudo :</label>
                <br>
        <input type="text" name="pseudo" value="<?= $pseudo ?>">

        <?php
          if(isset($err_departement)){
                echo $err_departement;
             }
          ?>
         <label>Département :</label>
         <br>
         <select name="departement">
            <?php
              if (!isset($departement)) { 
            ?>
        <option value="<?= $voir_utilisateur['departement'] ?>"><?= $voir_utilisateur['departement_nom']?></option>
             <?php 
                   }else{ 
              ?>
<option value="<?= $verif_departement['departement_code'] ?>"><?= $verif_departement['departement_nom']?></option>
              <?php
            }

             $req = $BDD->prepare("SELECT *
                FROM departement");

             $req->execute();

             $voir_departement = $req->fetchAll();

             foreach($voir_departement as $vd){ 
              ?>
               <option value="<?= $vd['departement_code'] ?>"><?= $vd['departement_nom']?></option>
    <?php
          }
      ?>
         </select>
    
    <?php
      if(isset($err_mail)){
            echo $err_mail;
          }
                  if (!isset($mail)) {
                      $mail = $voir_utilisateur['mail'];
                  }

                ?>
                         <label>Mail :</label>
                <br>
        <input type="text" name="mail" value="<?= $mail ?>">
   
        <input type="submit" name="modifier" value="Modifier">
</form>
</div>
   </div>
     <div class="membre-corps" style="text-align: left">
      <?php
       if (isset($_SESSION['avatar'])) {
       
      ?>
         <div style="margin: 10px 0">
         <div style="background: url(<?='upload/' . $_SESSION['id'] .'/' . $_SESSION['avatar'] ?>) no-repeat center; width: 150px; background-size: cover; height: 150px">
           
         </div>
         <?php
       }

       ?>
            <form method="Post" enctype="multipart/form-data">
              <div>
              Fichier : <input type="file" name="avatar">
            </div>
            <br>
            <div>
              <input type="submit" name="envoyer" value="Envoyer le fichier">
</div>    <br>       </form>
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