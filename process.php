<?php

session_start();	
   include("include/fonction_panier.inc.php");
   include("include/paypal.inc.php");
   
   
    $totalTva=montantTotalTVA();
	$paypal = new Paypal();
	$response= $paypal->request('GetExpressCheckoutDetails',array(
	 'TOKEN'=>$_GET['token']

	));
if($response){
	
    if($response['CHECKOUTSTATUS']=='PaymentActionCompleted'){
		
	  	$response2= $paypal->request('GetTransactionDetails',array(
	 'TRANSACTIONID'=>$response['PAYMENTRESQUEST_0_TRANSACTIONID']

	));
	  var_dump($response);
	  die('Ce paiement a été déjà éffectué');
	  
  }else{
	  
	  var_dump($paypal->errors);
	  die('1');
  }
  
  $response=$paypal->request('DoExpressCheckoutPayment',array(
    'TOKEN'=>$_GET['token'],
	'PAYERID'=>$_GET['PayerID'],
	'PAYMENTACTION'=>'Sale',
	'PAYMENTREQUEST_0_AMT'=>$totalTva,
	'PAYMENTREQUEST_O_CURRENCYCODE'=>'EUR'
	
  
  ));
  
	if($response){
		
		$response2= $paypal->request('GetTransactionDetails',array(
	 'TRANSACTIONID'=>$response['PAYMENTRESQUEST_0_TRANSACTIONID']

	));

	
	
	$name=$response2['SHIPTONAME'];
	$street=$response2['SHIPTOSTREET'];
	$city=$response2['SHIPTOCITY'];
	//$country=$response2['SHIPTTOCOUNTRY'];
	$state=$response2['SHIPTOSTATE'];
	$date=$response2['ODERTIME'];
	$transaction_id=$response2['TRANSACTIONID'];
	$amt=$response2['AMT'];
	$shipping=$response2['FEEAMT'];
	$currency_code=$response2['CURRENCYCODE'];
	$userID=$_SESSION['userID'];
	
	$bd->query("INSERT INTO transaction VALUE('','$name','$state','$city','$street','$date','$amt','$shipping','$currency_code','$transaction_id','$userID')");
		
		
	for($i=0; $i<count($_SESSION['panier']['libelleProd']);$i++){
		
          $produit=$_SESSION['panier']['libelleProd'][$i];
		  $quantite=$_SESSION['panier']['qteProd'][$i];
          $insert=$bd->query("INSERT INTO commande VALUES('','$produit','$quantite','$transaction_id')"); 
        
		$select=$bd->query("SELECT * FROM produit WHERE  titre='$produit'");
		$res=$select->fetch(PDO::FETCH_OBJ);
         $stock=$res->stock;
         $stock=$stock-$quantite;
         $update=$bd->query("UPDATE produit SET stock='$stock' WHERE titre='$produit'");		 
	}
	
		header("location:success.php");
	}else{
		
		var_dump($paypal->errors);
		die();
	}
	

}	


?>

