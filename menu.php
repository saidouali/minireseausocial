 

 <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="/">

    <img src="logoOumou.png" width="30" height="30" alt=""></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  

    
         <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav">
       <li class="nav-item ">
        <a class="nav-link" href="index.php">Acceuil</a>
      </li>
    </ul>

     <ul class="navbar-nav">
       <li class="nav-item ">
        <a class="nav-link" href="membres.php">Membres</a>
      </li>
    </ul>
      <ul class="navbar-nav">
       <li>
        <a class="nav-link" href="LesEvenements.php">Événement </a>
      </li>
    </ul>
      <ul class="navbar-nav">
       <li>
        <a class="nav-link" href="contact.php">Contact</a>
      </li>
    </ul>
    <ul class="navbar-nav ml-md-auto">
      <?php
       if (isset($_SESSION['id'])) {
      ?>
       
      <li class="nav-item ">
        <a class="nav-link" href="messagerie.php">Messagerie</a>
      </li>
        <li class="nav-item ">
        <a class="nav-link" href="" data-toggle="modal" data-target="#exampleModal">Mon Profil</a>
      </li>
      <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Paramétres</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <div class="modal-body">
        <li>
        <a href="profil.php">Mon profil</a>
      </li>
       <li>
        <a  href="demande.php">Demande d'amis</a> </li>
       <li>
       <li>
        <a  href="editer-profil.php">Editer mon profil</a> </li>
       <li>
        <a  href="deconnection.php">Déconnexion</a>
      </li>
        
       
      </div>
    </div>
     
  </div>
</div>

      <?php
     }else{

      ?>

    
      <li>
        <a class="nav-link" href="connexion.php">Mon Compte</a>
      </li>
      
      <?php
    }

    ?>
     </ul>
  </div>
</nav>


