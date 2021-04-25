<?php 
include("include/header.inc.php");
include("include/connexionDB.inc.php");
    include("include/fonction_panier.inc.php");
   include("include/paypal.inc.php");

            $id=$_SESSION['userID'];

			$select=$bd->query("SELECT * FROM client WHERE id=$id");
			$res=$select->fetch(PDO::FETCH_OBJ);
                        $total=montantTotal();
                         $totalTva=montantTotalTVA();
						 $shipping=fraisLivraison();
				?>
			                       <br> 
									  <p> Total : <?php  echo $totalTva.' Dhs'; ?></p>
									  <p> Frais de livraison : <?php echo $shipping.' Dhs';?> </p>
									 <b> <p>Total à payer: <?php echo $_SESSION['totalPAYER']=$totalTva+$shipping.'Dhs';  ?></b>
									  
				<br><br><h4> Adresse de livraison:  <?php echo $res->adresse ; ?> <a href=" ?action=modifierAdresse&amp;id=<?php echo $res->id ?>"> Modifier</a> </h4>
				<h4>Numeros:  <?php echo $res->tel ; ?> </h4>
				<form action="" method="post" >
				
				<input type="submit" name="validerCommande" value="confirmer le paiement "> 

				</form>


			<?php

				 
	if(isset($_POST['validerCommande'])){
				if($res->tel && $res->adresse){
 
   
   $id=$_SESSION['userID'];
	
		$select=$bd->query("SELECT * FROM client WHERE id=$id");
		$res=$select->fetch(PDO::FETCH_OBJ);
			
		$nom=$res->nom;
		$name=$res->prenoms;
		$street=$res->adresse;
		$city="RABAT";
		$state="MAROC";
		$date=date("Y-m-d h:m:s");
		$select=$bd->query("SELECT * FROM transaction ORDER BY id DESC LIMIT 1");
	     $res=$select->fetch(PDO::FETCH_OBJ);
		 $transaction_id=$res->transaction_id;
		 $transaction_id++;
	    $amt=$_SESSION['totalPAYER'];
		$currency_code="DHS";
		$userID=$id;
	
   $req=$bd->exec("INSERT INTO transaction VALUE('','$nom','$name','$state','$city','$street','$date','$amt','$shipping','$currency_code','$transaction_id','$userID')");
		
	
	for($i=0; $i<count($_SESSION['panier']['libelleProd']);$i++){
		
          $produit=$_SESSION['panier']['libelleProd'][$i];
		  $quantite=$_SESSION['panier']['qteProd'][$i];

          $insert=$bd->exec("INSERT INTO commande VALUE('','$produit','$quantite','$transaction_id')"); 
         
		$select=$bd->query("SELECT * FROM produit WHERE  titre='$produit'");
		$res=$select->fetch(PDO::FETCH_OBJ);
         $stock=$res->stock;
         $stock=$stock-$quantite;
         $update=$bd->query("UPDATE produit SET stock='$stock' WHERE titre='$produit'");	
 
	}
	
		header("location:success.php");
	}else{
		
		die();
	}

}


if(isset($_GET['action'])){
if($_GET['action'] == 'modifierAdresse'){ 

			$select=$bd->query("SELECT * FROM client WHERE id=$id");
			$res=$select->fetch(PDO::FETCH_OBJ);

			   if(isset($_POST['valider'])){
				   
				   $id=htmlspecialchars($_GET['id']);
				  $req=$bd->prepare("UPDATE client SET adresse=? , tel=?  WHERE id=? ");
				  $req->execute(array($_POST['adresse'], $_POST['tel'], $id)); 
				   
				   header('location:livraison.php');
				   
				 }
			?>
				<form action="" method="post">

				<h4> Adresse de livraison  <input type="text" name="adresse" value="<?php echo $res->adresse; ?>" required> </h4>
					<h4> Contact <input type="text" name="tel" value="<?php echo $res->tel; ?>" required ></h4>
				<h4> <input type="submit" name="valider" value="Modifier"></h4>
				   
			</form>


<?php
 }
	 
 else{
	 
	 die('une Erreur s\'est produite');
 }
 
}
 
include("include/footer.inc.php");

?>