<?php 
session_start();
include_once('db/connexiondb.php');

if (isset($_SESSION['id'])) {
 header('Location:index.php');
  exit;
}


if(!empty($_POST)){ 
  extract($_POST);
  $valid =(boolean)true;

   if(isset($_POST['inscription'])){
    
    $pseudo = (String) trim ($pseudo);
    $mail = (String) strtolower(trim ($mail));
    $password = (String) trim ($password);
    $jour = (int) $jour;
    $mois = (int) $mois;
    $annee = (int) $annee;
    $departement = (String) trim($departement);
    $date_naissance = (String) null;

     if(empty($pseudo)){
      $valid = false;
      $err_pseudo = "Veuillez renseigner ce champs !";
    }else{
      $req = $BDD->prepare("SELECT id
        FROM utilisateur
        WHERE pseudo = ?");

      $req->execute(array($pseudo));
      $utilisateur = $req->fetch();

      if(isset($utilisateur['id'])){
        $valid = false;
        $err_pseudo = "Ce pseudo existe déja";
      }
    }
 if(empty($mail)){
      $valid = false;
      $err_mail = "Veuillez renseigner ce champs !";
    }else{
      $req = $BDD->prepare("SELECT id
        FROM utilisateur
        WHERE mail = ?");

      $req->execute(array($mail));
      $utilisateur = $req->fetch();

      if(isset($utilisateur['id'])){
        $valid = false;
        $err_mail = "Ce mail existe déja";
      }
    }

     if(empty($password)){
      $valid = false;
      $err_password = "Veuillez renseigner ce champs !";
    }

     if($jour <= 0 || $jour > 31){
      $valid = false;
      $err_jour = "Veuillez renseigner ce champs !";
    }

      $verif_mois = array(1, 2, 3);

     if(!in_array($mois, $verif_mois)){
      $valid = false;
      $err_mois = "Veuillez renseigner ce champs !";
    }

      $verif_annee = array(1999, 1990, 3);

     if(!in_array($annee, $verif_annee)){
      $valid = false;
      $err_annee = "Veuillez renseigner ce champs !";
    }

    if (!checkdate($mois, $jour, $annee)) {
      $valid = false;
      $err_date = "Date fausse";
    }else{
      $date_naissance = $annee . '-' . $mois . '-' . $jour;
    }

  $req = $BDD->prepare("SELECT departement_code, departement_nom
        FROM departement
        WHERE departement_code =?");
      $req->execute(array($departement));

      $verif_departement = $req->fetch();

     if(isset( $verif_departement['departement_id'])){
      $valid = false;
      $err_departement = "Veuillez renseigner ce champs !";
    }
  
   if($valid){
     $date_inscription = date("Y-m-d h:m:s");

      $password = crypt($password, '$6$rounds=5000$oumoukoussoumousaidou1$');

     $req = $BDD->prepare("INSERT INTO utilisateur (pseudo, mail, password, date_naissance, departement, date_inscription, date_connexion) VALUES ( ?, ?, ?, ?, ?, ?, ?)");

      $req->execute(array($pseudo, $mail, $password, $date_naissance, $departement, $date_inscription, $date_inscription));

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

    <title>Inscription</title>
  </head>
  <body>

     <?php
   require_once('menu.php');
    ?>

    

    <form method="Post">

      <section>
        
        <div class="login-form"><br/><br/>
          <div class="form">

            <h1>Inscription</h1>
           <?php
          if(isset($err_pseudo)){
            echo $err_pseudo;
          }
          ?>
          <input type="text" name="pseudo" placeholder="Pseudo" value="<?php if(isset($pseudo)) {echo $pseudo;  } ?>">
        
         
          <input type="text" name="mail" placeholder="Mail" value="<?php if(isset($mail)) {echo $mail;  } ?>">
        
         
          <input type="password" name="password" placeholder="Mot de passe" value="<?php if(isset($password)) {echo $password;  } ?>">
 <select name="jour">
        <?php
        for ($i = 1; $i <= 31; $i++){ 
        ?>
        <option value="<?= $i ?>"><?=$i ?></option>
        <?php
}
        ?>
       
      </select>
    
       <select name="mois">

        <option value="1">Janvier</option>
        <option value="2">Février</option> 
        <option value="3">Mars</option>
        <option value="4">Avril</option>
        <option value="5">Mai</option> 
        <option value="6">Juin</option> 
        <option value="7">Juillet</option> 
        <option value="8">Aout</option> 
        <option value="9">Septembre</option> 
        <option value="10">Octobre</option> 
        <option value="11">Novembre</option> 
        <option value="12">Décembre</option>   
      </select>
    
       <select name="annee">
        <option value="1990">1990</option>
        <option value="1991">1991</option> 
        <option value="1992">1992</option>  
        <option value="1993">1993</option> 
        <option value="1994">1994</option> 
        <option value="1995">1995</option> 
        <option value="1996">1996</option> 
        <option value="1997">1997</option> 
        <option value="1998">1998</option> 
        <option value="1999">1999</option> 
        <option value="2000">2000</option> 
        <option value="2001">2001</option> 
        <option value="2002">2002</option> 

      </select>
      <select name="departement">
       <?php
          if(isset($err_departement)){
            echo $err_departement;
          }
          ?>
  <?php
    if(isset($departement)){
      $req = $BDD->prepare("SELECT departement_code, departement_nom
        FROM departement
        WHERE departement_code =?");
      $req->execute(array($departement));
      $voir_departement = $req->fetch();
      ?>
      
      <option value="<?=$voir_departement['departement_code'] ?>"><?= $voir_departement['departement_nom']?></option>
    

        <?php
      }
       $req = $BDD->prepare("SELECT departement_code, departement_nom
        FROM departement");
      $req->execute();
      $voir_departement= $req->fetchAll();

      foreach ($voir_departement as $vd) {
       ?>
    <option value="<?=$vd['departement_code'] ?>"><?= $vd['departement_nom']?></option>
    <?php
          }
      ?>
      </select>
           <input type="submit" name="inscription" value="S'inscrire" style="color: black;">
       
       <a class="nav-link" href="connexion.php" style="color: black;">Se connecter</a>
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