<?php 

include("include/header.inc.php");
include('include/sidebar.inc.php');
include('include/connexionDB.inc.php');
if(!isset($_SESSION['userID'])){
  if(isset($_POST['valider'])){
	  
	  $nom=htmlspecialchars($_POST['nom']);
	   $prenoms=htmlspecialchars($_POST['prenoms']);
	    $password=htmlspecialchars($_POST['password']);
		$repeatPass=htmlspecialchars($_POST['repeatPass']);
		 $mail=htmlspecialchars($_POST['mail']);
		 $adresse=htmlspecialchars($_POST['adresse']);
		 $tel=htmlspecialchars($_POST['tel']);
		  
		  
		  if($nom && $prenoms && $mail && $password && $repeatPass  ){
			  
			  if($password == $repeatPass){
				  
				 $prep= $bd->prepare("INSERT INTO client(id,nom,prenoms,email,password,adresse,tel) VALUE(?,?,?,?,?,?,?)");
				 
				  $prep->execute(array('',$nom,$prenoms,$mail,$password,$adresse,$tel));
				  
				    
					 header("location:connexion.php");
				  
				  
			  }else{
				  
				  echo '<br> <h3 style="color:red;"> Les mots de passes ne sont pas identiques';
				  
			  }
			  
		  }else{
			  
			   echo '<br> <h3 style="color:red;"> Veuillez Remplir tous les champs	';
		  }
	  
	  
  }

?>


<br>
<h1> S'enregistrer </h1>

<form action="" method="post">

    <h4> Nom<input type="text" name="nom" placeholder="Entrez votre Nom" required></h4> 
	<h4>Prenoms <input type="text" name="prenoms" placeholder="Entrez votre prenom" required ></h4>
	<h4> Email <input type="email" name="mail" placeholder="Entrez votre E-mail" required></h4>
	<h4>Adresse <input type="text" name="adresse" placeholder="Entrez votre Adresse" required ></h4>
	<h4> Numeros <input type="text" name="tel" placeholder="Entrez votre Numeros" required></h4>
	<h4> Mot de passe  <input type="password" name="password" placeholder="Mot de passe" required ></h4>
	<h4> Mot de passe <input type="password" name="repeatPass" placeholder="Confirmez mot de passe" required></h4>
	<h4> <input type="submit" name="valider" value="S'inscire"></h4>
	<p>
	
      
</form>
<p>Déjà utilisateur ? <a href="connexion.php" > Se connecter </a><p>

<?php
}else{
	
	header("location:monCompte.php");
}
include("include/footer.inc.php");

?>
