<?php 
   //Connect to database
   session_start();

include_once('db/connexiondb.php');
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

    <title>Contact</title>
  </head>
  <body>

     <?php
   require_once('menu.php');
    ?>

<div class="container">
  <h3>Nous Contacter</h3>
  <form action="action_page.php" method="Post">
    <label for="fname">Nom</label>
    <input type="text" id="fname" name="firstname" placeholder="Votre nom..">

    <label for="lname">Prenom</label>
    <input type="text" id="lname" name="lastname" placeholder="Votre prenom..">

    <label for="country">Adresse mail</label>
     <input type="text" id="lname" name="lastname" placeholder="Votre Email">

    <label for="subject">Objet</label>
    <textarea id="subject" name="subject" placeholder="Ecrire votre message ici ..." style="height:200px"></textarea>

    <input type="submit" value="Submit" style="background: #D2B48C">
  </form>
</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>