<?php 
include("include/header.inc.php");
include('include/sidebar.inc.php');
include('include/connexionDB.inc.php');
if(!isset($_SESSION['userID'])){

  if(isset($_POST['valider'])){
	    $password=htmlspecialchars($_POST['password']);
		 $mail=htmlspecialchars($_POST['mail']);
		  
		  
		  if($mail && $password ){
			  
			 $prep=$bd->query("SELECT id FROM client WHERE email='$mail' AND password='$password'");
			 if($prep->fetchColumn()){
				 $prep1=$bd->query("SELECT * FROM client WHERE email='$mail' AND password='$password'"); $prep1->execute();
				  $res=$prep1->fetch(PDO::FETCH_OBJ);
				  $_SESSION['userID']=$res->id;
				   $_SESSION['userNOM']=$res->nom;
				    $_SESSION['userPRENOMS']= $res->prenoms;
				     $_SESSION['userMAIL']=$res->email;
				      $_SESSION['userPASSWORD']=$res->password; 
					  $_SESSION['tel']=$res->tel;
					  $_SESSION['adresse']=$res->adresse;
					 
					header("location:monCompte.php");
				 
			 }else{
				  echo '<br> <h3 style="color:red;"> Mauvais identifiant</h3>';
			 }
			 
			  
		  }else{
			  
			   echo '<br> <h3 style="color:red;"> Veuillez Remplir tous les champs</h3>	';
		  }
	  
	  
  }

?>


<br>
<h1> Se Connecter  </h1>

<form action="" method="post">

	<h4> Email <input type="email" name="mail" placeholder="Entrez votre E-mail" required></h4>
	<h4> Mot de passe  <input type="password" name="password" placeholder="Mot de passe" required ></h4>
	<h4> <input type="submit" name="valider" value="Se connecter"></h4>
	<p>
	
</form>
<a href="inscription.php"> S'inscrire</a>

<?php
}else{
	
	header("location:monCompte.php");
	
}

include("include/footer.inc.php");

?>