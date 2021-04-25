<?php 
session_start();
   if(isset($_POST['connecter'])){
	   $pseudo=htmlspecialchars($_POST['pseudo']);
       $password=htmlspecialchars($_POST['password']);
	     $pseudoAdmin="Bouba";
		 $passwordAdmin="Bouba00225";
	   if($pseudo &&  $password){
		   
		   if($pseudo==$pseudoAdmin && $password==$passwordAdmin){
			   
			   $_SESSION["pseudo"]=$pseudo;
			   $_SESSION["password"]=$password;
			   
			   header("location:admin.php");
			   
			   
		   }else{
			   
		        echo" Identifiants Erron&eacute;s";
		   }
		   
	   }else{
		   echo "Veuillez remplir tous les champs";
	   }
	   
   }
              //formulation de connexion Administration pour ajouter produit
?>
<link href="../Style/indexStyle.css" type="text/css" rel="stylesheet"/>  
<h1> Administration - Connexion </h1>
<form method="post" action="">
<h3>Pseudo:</h3> <input type="text" name="pseudo" placeholder="Pseudo" ></br>
<h3>Mot de passe:</h3>  <input type="password" name="password" placeholder=" Mot de Passe" > <br><br>
<input type="submit" name="connecter" value="CONNEXION" >

</form>