<?php
  include('include/header.inc.php');
  include('include/sidebar.inc.php');

?>
	

	<h1> Mon compte </h1>
	<h2> Mes informations </h2>
	<?php 
	
	$userID=$_SESSION['userID'];
	$select=$bd->query("SELECT * FROM client WHERE id=$userID ");
	
	while($res=$select->fetch(PDO::FETCH_OBJ)){
	
	?>

       <h4>Nom:  <?php echo $res->nom ; ?> </h4>
	     <h4>Prénoms:  <?php echo $res->prenoms ; ?> </h4>
		   <h4>E-mail:  <?php echo $res->email ; ?> </h4>  
		   <h4>Adresse de livraison:  <?php echo $res->adresse ; ?> </h4>
			    <h4>Numeros:  <?php echo $res->tel ; ?> </h4>
				   <h4>Mot de passe:  <?php echo $res->password ; ?> </h4>
			 
  <?php		
	}
	?>
	
	<h2> Mes Transactions </h2>
	
	<?php 
	$select1=$bd->query("SELECT * FROM transaction WHERE userID=$userID ");
	
	
	while($res=$select1->fetch(PDO::FETCH_OBJ)){
		
		$transaction_id=$res->transaction_id;
		
		$select2=$bd->query("SELECT * FROM commande WHERE transaction_id=$transaction_id ");
	?>
        
      <br> <h4>Date:  <?php echo $res->date ; ?> </h4>
	      <h4>NumTransaction:  <?php echo $res->transaction_id ; ?> </h4>
		   <h4>Pays:  <?php echo $res->pays ; ?> </h4>
		     <h4>Ville:  <?php echo $res->ville ; ?> </h4>
			 <h4>Adresse de livraison:  <?php echo $res->adresse ; ?> </h4>
		     <h4>Produits:  <?php while($res2=$select2->fetch(PDO::FETCH_OBJ)){?>
					 <i> <h5>Nom: <?php echo $res2->produit;?></h5></i>
					 <i> <h5>Quantité : <?php echo $res2->quantite; ?></h5></i>
				<?php } ?> </h4>  
			<h4>Prix total:  <?php echo $res->prixTotal ; ?> </h4>
			   <h4>Frais de livraison:  <?php echo $res->shipping ; ?> </h4>
			   <h4>Devise utilisé:  <?php echo $res->currency_code ; ?> </h4>
			 
  <?php		
	}
	?>
	
		<a href="deconnecter.php">Se deconnecter</a>
	
	
<?php

 include('include/footer.inc.php');	  

?>