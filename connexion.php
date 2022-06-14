<?php 
session_start();
include_once ('db/connexionDB.php');

if (isset($_SESSION['id'])) {
  header('Location:index.php');
  exit;
}

  if(!empty($_POST)){
  extract($_POST);
  $valid = (boolean) true;

   if(isset($_POST['connexion'])){
    
    $mail = (String) strtolower(trim ($mail));
    $password = (String) trim ($password);

       if(empty($mail)){
      $valid = false;
      $err_mail = "Veuillez renseigner ce champs !";
    }else{
      $req = $BDD->prepare("SELECT id
      FROM utilisateur
      WHERE mail = ?");

       $req->execute(array($mail));
      $utilisateur = $req->fetch();

      if(!isset($utilisateur['id'])){
        $valid = false;
        $err_mail="Vueillez renseigner ce champs";
      }
    }

      if(empty($password)){
      $valid = false;
      $err_password = "Veuillez renseigner ce champs !";
    }
     $req = $BDD->prepare("SELECT id
       FROM utilisateur
       WHERE mail = ? AND password = ?");

      $req->execute(array($mail, crypt($password, '$6$rounds=5000$oumoukoussoumousaidou1$')));
      $verif_utilisateur = $req->fetch();
       
       if (!isset($verif_utilisateur['id'])) {
         $valid = false;
         $err_mail = "Veuillez renseigner ce champs !";
       }

     if ($valid) {

     $req = $BDD->prepare("INSERT INTO utilisateur (date_connexion) value(?)");
     $req->execute(array(date("Y-m-d h:m:s")));

     $req = $BDD->prepare("SELECT *
      FROM utilisateur
      WHERE id = ?");

      $req->execute(array($verif_utilisateur['id']));
      $verif_utilisateur = $req->fetch();
       
       $_SESSION['id'] = $verif_utilisateur['id'];
       $_SESSION['pseudo'] = $verif_utilisateur['pseudo'];
       $_SESSION['mail'] = $verif_utilisateur['mail'];

          header('Location:index.php');
           exit;
  
}

    }
}
?>

<!doctype html>
<html lang="fr">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

     <link rel="stylesheet" href="css/style.css">

     <link rel="stylesheet" href="css/projet.css">

    <title>Connexion</title>
  </head>
  <body>

     <?php
   require_once('menu.php');
    ?>
    
  
     <form method="Post">

      <section>
        
        <div class="login-form"><br/><br/>
          <div class="form">
              <h1>Connexion</h1>

        <?php
          if(isset($err_mail)){
            echo $err_mail;
          }
          ?>
        <input type="text" name="mail" placeholder="Mail" value="<?php if(isset($mail)) {echo $mail ;} ?>">
          <div>
        </div>
        <?php
          if(isset($err_password)){
            echo $err_password;
          }
          ?>
        <input type="password" name="password" placeholder="Mot de passe" value="<?php if(isset($password)) {echo $password;  } ?>">
        
        <input type="submit" name="connexion" value="Se connecter" style="color: black;">
          
        <a class="nav-link" href="inscription.php" style="color: black;">S'inscrire</a>
      
      </div>
      </div>
      </section>
    </form>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>