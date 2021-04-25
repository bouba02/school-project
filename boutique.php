<?php 
   include('include/header.inc.php');
   include("include/connexionDB.inc.php"); 
   include("include/sidebar.inc.php");
   //##########################Afficher l'article apres avoir cliquer#####################################
   if(isset($_GET['choix'])){
	   $titre=htmlspecialchars($_GET['choix']);
	   $select="SELECT * FROM produit where titre='$titre' ";
	   $reponse=$bd->prepare($select);
	   $reponse->execute();
	    while($res=$reponse->fetch(PDO::FETCH_OBJ)){
			
		$description=$res->description;
		$description_finale=wordwrap($description,80,'<br>',true);
	   
	   ?>
	   <br>
	  <div style="text-align:center">
	       <img src="Admin/image/<?php echo $res->titre ;?>.jpg">  
	       <h1><?php echo $res->titre ; ?></h1>
	       <h5><?php echo $description_finale ; ?></h5>
		   <h4><?php echo $res->prix ; ?></h4>
		   <h5>Stock: <?php echo $res->stock ;?> </h5>
		   <?php if($res->stock !=0){ ?>
			
			<a href="panier.php?action=ajout&amp;l=<?php echo $res->titre; ?>&amp;q=1&amp;p=<?php echo $res->prix; ?>">Ajouter au panier </a>  
			
			<?php }else{
				    echo"<h5 style=color:red; >Stock épuisé !</h5> ";
			       }
			?>
	  </div><br>
	   
	   <br><br>
	   <?php
	   
	   }//fin While
	   ?>
          <br><br><br><br>
          <?php
   }
   else
	   
   {
   //########################### Afficher les Produits par categorie#################
   
	if(isset($_GET['categorie'])){
			
		$categorie=htmlspecialchars($_GET['categorie']);
		$select="SELECT * FROM produit WHERE categorie='$categorie'";
		$reponse=$bd->prepare($select);
		$reponse->execute();
		?>
	
	<?php
		while($resultat=$reponse->fetch(PDO::FETCH_OBJ)){
			$lengthMax=75;
			$description=$resultat->description;
			$new_description= substr($description,0,$lengthMax).'...';
			$description_finale=wordwrap($new_description,45,'<br>',true);
			
?>            <a href="?choix=<?php echo $resultat->titre ; ?>"><img src="Admin/image/<?php echo $resultat->titre ;?>.jpg"></a>
			
			<a href="?choix=<?php echo$resultat->titre ; ?>"><h2> <?php echo $resultat->titre; ?> </h2></a>
			 <h5> <?php echo $description_finale; ?> </h5>
			 <h4> <?php echo $resultat->prix; ?> Dhs </h4>
			 <h5>Stock: <?php echo $resultat->stock ;?> </h5>
			<?php if($resultat->stock !=0){ ?>
			
			<a href="panier.php?action=ajout&amp;l=<?php echo $resultat->titre; ?>&amp;q=1&amp;p=<?php echo $resultat->prix; ?>">Ajouter au panier </a>
			
			<?php }else{
				    echo"<h5 style=color:red; >Stock épuisé !</h5> ";
			       }
			?>
		
			 <br><br>
  
<?php

		}
           	
		?>
	
     
          <?php
	}
	      	
	else          //Afficher les Categories
	{         
	?>
	
	    <br> <h1>Catégories : </h1>      
	
	<?php
		
		$select="SELECT * FROM categorie";
		$reponse=$bd->query($select);
		
		
		while($res=$reponse->fetch(PDO::FETCH_OBJ)){
			
			?>
			
			<a href="?categorie=<?php echo $res->nomCat ;?>"> <h3><?php echo $res->nomCat ;?></h3>  </a>
			
			<?php
			
			
		} 
	        
	}
  }	
  echo"<br> <br>  <br> <br> <br> <br> <br> <br> <br> <br> <br> <br>";
    include('include/footer.inc.php');
?>
 