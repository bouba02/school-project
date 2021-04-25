<?php 

   include('include/header.inc.php');
   include("include/AfficherSiChoix.inc.php");
   include("include/sidebar.inc.php");
    include("include/fonction_panier.inc.php");
   include("include/paypal.inc.php");

   
	
	$erreur=false;
	
	$action=(isset($_POST['action'])?$_POST['action']:(isset($_GET['action'])?$_GET['action']:null));
	
	if($action != null){
		
		if(!in_array($action,array('ajout','suppression','Refresh')))
			
			$erreur=true;
			
				$l=(isset($_POST['l'])?$_POST['l']:(isset($_GET['l'])?$_GET['l']:null));
				$q=(isset($_POST['q'])?$_POST['q']:(isset($_GET['q'])?$_GET['q']:null));
				$p=(isset($_POST['p'])?$_POST['p']:(isset($_GET['p'])?$_GET['p']:null));
				
				$l=preg_replace('#\v#','',$l);
				
				$p= floatval($p);
				
		         if(is_array($q)){
					 
					 $qteProd=array();
					 $i=0;
					 
					 foreach($q as $contenu){
						 
						 $qteProd[$i++]=intval($contenu);
						 
					 }
					 
				 }else{
					 
					 $q=intval($q);
				 }
			
			
		
	}
	
	if(!$erreur){
		
		switch($action){
			
			 case "ajout": 
					
			         ajoutProd($l,$q,$p);
			 break;
			 
			  case "suppression":  supprimerProd($l);
			 break;
			 
			  case "Refresh":   for($i=0; $i<count($qteProd);$i++){
				  
				                     modifierQteProd($_SESSION['panier']['libelleProd'][$i], round($qteProd[$i]));
				  
			                    }
			   
			 break;
			 
			 default :
			 
			 
		}
		
	 }else{
		 
		 echo "Erreur valeur !!!";
		 }
	 
	 
	
	
	
  ?> 
  <form method="post" action="" >
     <table  width="400"> 
	    <tr>   
		    <td colspan="4">  Votre panier </td><br>

	    </tr>
		<tr>
		
		    <td> Libéllé produit   </td>
			<td> Prix Unitaire   </td>
			<td> Quantité  </td>
			<td>   TVA </td>
			<td> Action  </td>
			
		</tr>
		<?php    //######################### Supprimer PANIER~######################################################
		     if(isset($_GET['deletePanier']) && $_GET['deletePanier']== true){
				 
				 supprimerPanier();
				 
			 }
			 
			 ########### Verifie si Panier Créer ou pas ##############################""
		if(createPanier()){
			 $nbProd = count($_SESSION['panier']['libelleProd']);
			 
			 if($nbProd <= 0 ){
				 
				 echo "<br> <p style=font-size=20px;color:red > Oops , Votre panier est vide ! </p>";
			 
			 }else{
				 
				                //######################Affichage /suppression des produits du panier ##################################################
						
						
						  $total=montantTotal();
                         $totalTva=montantTotalTVA();
						 $shipping=fraisLivraison();
				//integration du moyen de paiement par Paypal
						 $paypal=new Paypal();
						 
						 $params= array(
						    
							'RETURNURL'=>'http://127.0.0.1/Site E-Commerce/process.php',
							'CANCELURL'=>'http://127.0.0.1/Site E-Commerce/cancel.php',
							'PAYMENTREQUEST_0_AMT'=>$totalTva + $shipping,
							'PAYMENTREQUEST_0_CURRENCYCODE'=>'EUR',
							'PAYMENTREQUEST_0_SHIPPINGAMT'=>$shipping,
							'PAYMENTREQUEST_0_ITEMAMT'=>$totalTva
						 
						 ); 
								
						$response=$paypal->request('SetExpressCheckout',$params);

                        if($response){
							
							$paypal='https://sandbox.paypal.com/webscr?cmd=_express-checkout&useraction=commit&token='.$response['TOKEN'].'';
							
							
						}else{
							
							var_dump($paypal->errors);
							die('Erreur');
							
							
						}	

      			
								
				 for($i=0 ; $i<$nbProd; $i++){
					 ?>
					 <tr>
					    
				            <td><br> <?php  echo $_SESSION['panier']['libelleProd'][$i];  ?> </td>
							<td><br> <?php  echo $_SESSION['panier']['prixProd'][$i] ; ?> </td>
							<td><br> <input  name="q[]" value="<?php echo $_SESSION['panier']['qteProd'][$i] ;?>" size="5" ></td>
							<td><br> <?php  echo $_SESSION['panier']['tva'].' %	'; ?> </td>
							<td><br> <a href="panier.php?action=suppression&amp;l=<?php echo rawurlencode($_SESSION['panier']['libelleProd'][$i]); ?>"> X </a>  </td>
					 </tr>
				 <?php   }   ?>
					      <td colspan="2">  <br>
						            
									 <p> Total: <?php  echo $total.' Dhs'; ?></p>
									  <p> Total avec TVA : <?php  echo $totalTva.' Dhs'; ?></p>
									  <p> Frais de livraison : <?php echo fraisLivraison().' Dhs';?> </p>
									<?php if(isset($_SESSION['userID'])) {?>  <p> <a href="<?php echo $paypal ;?>"> Payer par carte bancaire </a></p> <?php }else { ?> <h4 style="color:red;" >Vous devez vous connecter pour payer votre commande. <a href="connexion.php">Se connecter </a></h4> <?php } ?>
									<?php if(isset($_SESSION['userID'])) {?>  <p> <a href="livraison.php"> Payer à la livraison </a></p> <?php } ?>
						  
						  <tr>  
						          
								<td colspan="4"> 

                                  <input type="submit" value="Raffraichir"><br>
								  <input type="hidden" name="action" value="Refresh">
								  <a href="?deletePanier=true"> Supprimer le panier </a>
								  
								</td>  
						  
						  </tr>
					 
					 <tr>
					 
					 
					 </tr>
					 <?php
				 
				 
			 }
		
	}	
		?>
	 </table>	
  </form>
  
  
  <?php  
     echo"<br> <br>  <br> <br> <br> <br> <br> <br> <br> <br> <br> <br>";
    include('include/footer.inc.php');
?>
 