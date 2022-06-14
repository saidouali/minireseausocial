<?php
session_start();

include_once('db/connexiondb.php');

if (!isset($_SESSION['id']))  {
   header('Location: index.php');
    exit;
}

$get_id = (int) $_GET['id'];

if ($get_id <= 0) {
    header('Location: messagerie.php');
    exit;
}

$req = $BDD->prepare("SELECT id
        FROM relation
        WHERE ((id_demandeur, id_receveur) = (:id1, :id2) OR (id_demandeur, id_receveur) = (:id2, :id1)) AND statut = :statut");

        $req->execute(array('id1' => $_SESSION['id'], 'id2' => $get_id, 'statut' => 2));

        $verifier_relation = $req->fetch();

      if (isset($verifier_relation['id'])) {
           header('Location: messagerie.php');
           exit;
}

$req = $BDD->prepare("SELECT * 
   FROM messagerie
   WHERE ((id_from,id_to) = (:id1,:id2) OR (id_from, id_to) = (:id2,:id1))
   ");

$req->execute(array('id1' => $_SESSION['id'], 'id2' => $get_id));

$afficher_message = $req->fetchAll();


if(!empty($_POST)){ 
  extract($_POST);
  $valid =(boolean)true;

  
  if(isset($_POST['envoyer'])){
     $message = (String) trim($message);

     if(empty($message)){
        $valid = false;
        $er_message = "il faut mettre un message";
     
    }
  
   if($valid){

     $date_message = date("Y-m-d h:m:s");

     $req = $BDD->prepare("INSERT INTO messagerie (id_from, id_to, message, date_message, lu) VALUES (?, ?, ?, ?, ?)");

     $req->execute(array($_SESSION['id'], $get_id, $message, $date_message, 1));
     }
      header('Location: message.php?id=' .$get_id);
      exit;
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

    <title>Message</title>
  </head>
  <body>

    <?php
   require_once('menu.php');
   ?>  
  <div class="container">
      <div class="row">
         <div class="col-sm-12"> 
           <div class="corps-des-messages" id="msg">     
          <?php
             foreach($afficher_message as $am){
                if ($am['id_from'] == $_SESSION['id']) {
            ?>
          <div class="message-gauche">

              <?= $am['message'] ?> 

              <div  style=" font-size: 12px; padding-top: 15px; color: #ccc; font-style: italic; text-align: right; font-size: 12px;"><?= date('d-m-Y à M:i:s', strtotime($am['date_message']));
                       ?> </div>
          </div>
            <?php

               }else{ 
          ?>
          <div class="message-droit">
              <?= $am['message'] ?>

              <div  style=" font-size: 12px; padding-top: 15px; color: #ccc; font-style: italic; text-align: right; font-size: 12px;"><?= date('d-m-Y à M:i:s', strtotime($am['date_message']));
                       ?> </div>
          </div>
      <?php
  }
 }
   ?>
      <div id="afficher-message"></div>
  </div>
      </div>
       <div class="col-sm-12" style="margin-top: 20px;">
        <?php
           if (isset($er_message)) {
               echo $er_message;
           }
        ?>
        <form method="Post" id="envoyer">
            <textarea placeholder="Votre Message ..." name="message" id="message"></textarea>
            <input type="submit" name="envoyer" value="Envoyer" style="background: #D2B48C" />
             
        </form>
       </div>
    </div>
   </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function(){

            document.getElementById('msg').scrollTop = document.getElementById('msg').scrollHeight;

            $('#envoyer').on("submit", function(e) { 
                e.preventDefault();

               var id;
               var message;

            id = <?= json_encode($get_id, JSON_UNESCAPED_UNICODE); ?>;
            message = document.getElementById('message').value;

            document.getElementById('message').value = '';

            if(id > 0 && message !=""){
                $.ajax({
                    url : 'envoyer-message.php',
                    method : 'POST',
                    dataType : 'html',
                    data : {id: id, message: message},

                    success : function(data){
                        $(' #afficher-message').append(data);
                         document.getElementById('msg').scrollTop = document.getElementById('msg').scrollHeight;

                    },
                    error : function(e, xhr, s){
                        let error = e.responseJSON;
                        if(e.status == 403 && typeof error !== 'undefined'){
                            alert('Erreur 403');
                        }else if(e.status == 404){
                            alert('Erreur 404');
                        }else if(e.status == 401){
                            alert('Erreur 401');
                        } else if{
                            alert('Erreur Ajax');
                        }
                    }
                });
            }

            });
        });
    </script>
  </body>
</html>