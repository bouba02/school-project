<?php 
 include("connexionDB.inc.php"); 
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
   
  echo"<br> <br>  <br> <br> <br> <br>";
}

?>