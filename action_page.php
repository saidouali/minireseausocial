<?php     
    //Connect to database
    try
    {
        $bdd = new PDO('mysql:host=localhost;dbname=site_rencontre;charset=utf8', 'root', '', 
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    catch (Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }

//Prepare query
    $req = $bdd->prepare ('INSERT INTO contact(nom, prenom, mail, message) VALUES (:nom, :prenom, :mail, :message) ');

//Define variables
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $mail = $_POST['mail'];
    $message = $_POST['message'];


//Execuse query, add data to database
    $req ->execute(array(
        'nom' => $nom,
        'prenom' => $prenom,        
        'mail' => $mail,
        'message' => $message
       
    ));


    //Get last ID
    $response = $bdd->query('SELECT id FROM contact ORDER BY id DESC LIMIT 1');
    $array = $response->fetch(PDO::FETCH_ASSOC);
        
    //Convert array to string
    $id = implode($array);
    
//Mail message body
    
    $form="
     <html>
        <form>
        <h3>Bonjour</h3>
        <p>Vous avez un nouveau message de la part d'un membre du site minisocal </P>
        <p>$nom: </p>
        <p>$prenom: </p>
        <p>$mail: </p>
        <p>$message: </p>
        </form>
       

</html>
";

 //Mail header content
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=utf-8';
    $headers[] = "From: $mail";

    //Site message on submit
    echo "Votre message a été envoyé avec succès.";

    //Send mail to $email_res
    mail("oumou-koussoumou.saidou-ali@justice.fr", "Message d'aide site de rencontre",
    $form, implode("\r\n", $headers));
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
    </head>
</html>



