

<?php  

session_start();

?>
<html  lang="fr">
   <head>  
      <meta charset="UTF-8" >
	   
		<link href="style/indexStyle.css" type="text/css" rel="stylesheet"/>  
	
  
    </head>
	  <header>   
	     <h1> Site E-Commerce  </h1></br>
		 
		 
		 <?php 
		   include("connexionDB.inc.php");
		   
		   if(isset($_POST['validerRecherche'])){
			   
			   $recherche=htmlspecialchars($_POST['recherche']);
			   
			   if($recherche){
				   
				   $req=$bd->query("SELECT categorie FROM produit WHERE categorie='$recherche' OR titre='$recherche' OR description='$recherche' ");
				 if( $res=$req->fetchColumn()){
					 
					   //$req=$bd->query("SELECT categorie FROM produit WHERE categorie='$recherche' OR titre='$recherche' OR description='$recherche' ");
					  // $res->$req->fetch(PDO::FETCH_OBJ);
					   
					 $cat=$res;
				    header("location:boutique.php?categorie=$cat");
				   }
					   
				  }
					   
				   }
			   
			   
		   
		 
		 
		 ?>
		  <div style="text-align:center ;"><form action="" method="post"  >
		     <input type="search" name="recherche" placeholder="cherchez un produit,une marque ou une catégorie" size="43px" > 
			 <input type="submit" name="validerRecherche" value="Rechercher">
		 </form><br></div>
	     <ul class="menu">
		
		    <li><a href="index.php">Accueil</a></li>
			<li><a  href="boutique.php">Boutique</a></li>
			<li><a href="panier.php">Panier</a></li>
			<?php if(!isset($_SESSION['userID'])) {?>
			<li><a href="connexion.php">Connexion</a></li>
			<li><a href="inscription.php">Inscription</a></li>
		<?php }else{ ?>
			<li><a href="monCompte.php">Mon Compte</a></li>
			<?php } ?>
			<li><a href="aide.php">Aide</a></li>
			
		 </ul>
		 
	   
	  </header>
  <body>
 
 </body>
 </html>
 