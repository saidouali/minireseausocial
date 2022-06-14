<?php
session_start();
include_once('db/connexiondb.php');

$id_evenement = (int) trim($_GET['id']);

if(empty($id_evenement)) {
  header('Location: LesEvenements.php ');
  exit;
}
  $req = $DB->query("SELECT e.*, u.pseudo
       FROM evenement e
       LEFT JOIN utilisateur u ON u.id = e.id_user
       WHERE e.id = ?
       ORDER BY e.date_creation",
       array($id_evenement));

    $req = $req->fetch();

  $req_commentaire = $DB->query("SELECT c.*, u.pseudo
    FROM commentaire c
    LEFT JOIN utilisateur u ON u.id = c.id_user
    WHERE c.id_evenement = ?
    ORDER BY c.date_creation Desc",
    array($id_evenement));

  $req_commentaire = $req_commentaire->fetchAll();

   if (!empty($_POST)) {
     extract($_POST);  
     $valid = true;

     if (isset($_POST['ajout-commentaire'])) {
       $text = (String) trim($text);

       if (empty($text)) {
         $valid = false;
         $er_commentaire= "Il faut mettre un commentaire";
       }elseif (iconv_strlen($text, 'UTF-8')<= 3) {
         $valid = false;
         $er_commentaire= "Il faut mettre plus de 3 caracteres";
       }

       $text = htmlentities($text);

       if ($valid) {
         $date_creation = date('Y-m-d H:i:s');

         $DB->insert("INSERT INTO commentaire (id_user, id_evenement, text,  date_creation) VALUES (?,?,?,?)",
          array($_SESSION['id'], $id_evenement, $text, $date_creation));

         header('Location: LesEvenements.php' . $id_evenement);
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

    <title>Evenement : <?= $req['titre']?></title>
  </head>
  <body>

    <?php
   require_once('menu.php');
   ?>  

  <div class="container">
    <div class="row" style="margin-top: 20px;">

       <div class="col-sm-12 col-md-12 col-lg-12">

        <a class="btn btn-primary" href="LesEvenements.php" role="button" style="background: #D2B48C">Retour</a>
 
    <div style="margin-top: 20px; background: white; box-shadow: 0 5px 10px rgba(0,0,0, .09); padding: 5px 10px; border-radius: 10px">
    <h1 style="color: #666; text-decoration: none; font-size: 28px;"><?= $req['titre'] ?></h1>

    <div style="border-top: 2px solid #EEE; padding:15px 0">

      <?= nl2br($req['text']); ?> </div>


     <div style="padding-top: 15px; color: #ccc; font-style: italic; text-align: right; font-size: 12px;">
  Créer par <?= $req['pseudo'];?> le <?= date_format(date_create($req['date_creation']), 'D d M Y à H:i');?>
             </div>
              <?php
                  if(isset($_SESSION['id']) && $_SESSION['id'] == $req['id_user']){
                ?>
            <div style="padding-top: 15px; color: #ccc; font-style: italic; text-align: right; font-size: 12px;">
              <a href="editer-evenement.php?id=<?= $req['id'] ?>" style=" color : #D2B48C;">Editer mon evenement<a>
            </div>
            <?php
          }
          ?>
           </div>
           <?php 
           if (isset($_SESSION['id'])) {
             ?>
             <div style="margin-top: 20px; background: white; box-shadow: 0 5px 10px rgba(0,0,0, .09); padding: 5px 10px; border-radius: 10px">
              <h3>Participer à l'evenement</h3>
               
             <?php  
             // S'il y a une erreur sur le nom alors on affiche
             if (isset($er_commentaire)) {
            ?>
                <div class="er_msg"><?= $er_commentaire ?></div>
             <?php
              }
           ?>
           <form method="Post">
             <div class="form-group">
                <textarea class="form-control" name="text" rows="4" placeholder="Ecrivez votre commentaire..."></textarea>
             </div>
         <div class="form-group">
           <button class="btn btn-primary" type="submit" name="ajout-commentaire" style="background: #D2B48C"> Envoyer</button>
         </div>
          </form>
       </div>
       <?php
         }
     ?>
     <div style="margin-top: 20px; background: white; box-shadow: 0 5px 15px rgba(0,0,0, .15); padding: 5px 10px; border-radius: 10px">
      <h3>Commentaires</h3>

          <?php
          foreach($req_commentaire as $rc){
            ?>
           
              <div style="background: #eee; margin-top: 30px; padding: 5px 10px; border-radius: 20px">
                <div style="font-weight: bold;">
                <?= "De" .$rc['pseudo'] . ":"?></a>

                </div>
                    <?= nl2br($rc['text'] )?>

                <div style="text-align: right;">
                <?= $rc['date_creation'] ?>
              </div>
              </div>
           
            <?php
             }

          ?>
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