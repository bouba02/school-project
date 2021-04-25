<div class="sidebar">
<h4>Derniers Articles </h4>

<?php		

	include("connexionDB.inc.php");
	$select="SELECT * FROM produit ORDER BY Reference DESC LIMIT 3";
		$reponse=$bd->prepare($select);
		$reponse->execute();
		
		
		while($resultat=$reponse->fetch(PDO::FETCH_OBJ)){
			$lengthMax=75;
			$description=$resultat->description;
			$new_description= substr($description,0,$lengthMax).'...';
			$description_finale=wordwrap($new_description,45,'<br>',true);
			
  ?>
        <div style="text-align:center;">
		 <a href="?choix=<?php echo $resultat->titre ; ?>"> <img height="80" width="90" src="Admin/image/<?php echo $resultat->titre;?>.jpg"> </a>
	     <a href="?choix=<?php echo $resultat->titre ; ?>"><h3 style="color:white;"> <?php echo $resultat->titre; ?> </h3></a>
	     <h4 style="color: white;"> <?php echo $resultat->prix; ?> Dhs </h4>	
	     <h5 style="color: white;"> <?php echo $description_finale; ?> </h5>			 

		</div>
      

        <?php
		}
		
       ?>	
	   
</div>