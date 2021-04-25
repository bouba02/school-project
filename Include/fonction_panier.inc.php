<?php 
     
     function createPanier(){
		 include("connexionDB.inc.php"); 
		 if(!isset($_SESSION['panier'])){
			 
			 $_SESSION['panier']=array();
			 $_SESSION['panier']['libelleProd']=array();
			 $_SESSION['panier']['qteProd']=array();
		     $_SESSION['panier']['prixProd']=array();
             $_SESSION['panier']['verrou']=false;
             $select=$bd->query("SELECT tva FROM produit");
			 $res=$select->fetch(PDO::FETCH_OBJ);
		     $_SESSION['panier']['tva']=$res->tva;
			 
			 
		 }
		 return true;
	 }


     function ajoutProd($libelleProd,$qteProd,$prixProd){
		 
		 if(createPanier() && !isVerouille()){
			
			 $position_prod=array_search($libelleProd,$_SESSION['panier']['libelleProd']);
			 
			 if($position_prod !== false){
			
			 $_SESSION['panier']['qteProd'][$position_prod]+=$qteProd ;
				  
			 }else{
				  
				  array_push($_SESSION['panier']['libelleProd'],$libelleProd);
				  array_push($_SESSION['panier']['qteProd'],$qteProd);
				  array_push($_SESSION['panier']['prixProd'],$prixProd);
			 }
	
		 }else{
			 echo"Erreur!!, veuillez contacter l'Administrateur";
			 
		 }
	 }
	 
	 
	 function modifierQteProd($libelleProd,$qteProd){
		 
		 if(createPanier()	&& !isVerouille()){
			 
		     if($qteProd > 0){
				 
				 $position_prod=array_search($libelleProd,$_SESSION['panier']['libelleProd']);
				 
				 if($position_prod !== false){
					 
					 $_SESSION['panier']['qteProd'][$position_prod]= $qteProd;
					  
				 }
			 }else{
				 
				 supprimerProd($libelleProd);
			 }
			 
		 }else{
			 
			 echo"Erreur!! , Veuillez contacter l\'administrateur du site.";	
		 }
		 
	 }
	 
	 
  function supprimerProd($libelleProd){
		 
	 if(createPanier() && !isVerouille()){
			 
			 $temp=array();
			 $temp['libelleProd']=array();
			 $temp['qteProd']=array();
			 $temp['prixProd']=array();
			 $temp['verrou']=$_SESSION['panier']['verrou'];
	         $temp['tva']=$_SESSION['panier']['tva'];
			 
		 for($i=0; $i<count($_SESSION['panier']['libelleProd']); $i++){
				 
			 if($_SESSION['panier']['libelleProd'][$i] !== $libelleProd ) {
					 
					array_push($temp['libelleProd'],$_SESSION['panier']['libelleProd'][$i]);
				  array_push($temp['qteProd'],$_SESSION['panier']['qteProd'][$i]);
				  array_push($temp['prixProd'],$_SESSION['panier']['prixProd'][$i]);
				  
				  
				 }
			}
		     
              $_SESSION['panier']=$temp;
			  unset($temp);
			 
	  }else{
			 
			 echo"Erreur! , veuillez contacter l\'administrateur";
			 
		 }
  }
  
  
  function isVerouille(){
	  
	  if(isset($_SESSION['panier']) && $_SESSION['panier']['verrou']){
		  
		  return true;
		  
	  }else{
		  
		  return false;
	  }
	  
  }
  
  
  function supprimerPanier(){
	  
	 
		  unset($_SESSION['panier']);
		  
	  
	  
  }
  
  
  
  function montantTotal(){ 
     
	 $total=0;
      for($i=0; $i<count($_SESSION['panier']['libelleProd']); $i++){
		  
	  $total += $_SESSION['panier']['qteProd'][$i] * $_SESSION['panier']['prixProd'][$i];
	  
	  }
	  
	return $total; 
  }
  
  
  
  function montantTotalTVA(){ 
     
	 $total=0;
      for($i=0; $i<count($_SESSION['panier']['libelleProd']); $i++){
		  
	  $total += $_SESSION['panier']['qteProd'][$i]* $_SESSION['panier']['prixProd'][$i];
	  
	  }
	  
	return $total + ($total*$_SESSION['panier']['tva'])/100; 
  }
  
  
  function compterProd(){
	  
	  if(isset($_SESSION['panier'])){
		  
		  return count($_SESSION['panier']['libelleProd']);
	  }else{
		  
		  return 0;
	  }
	  
  }
  
  
 function fraisLivraison(){
	  include("connexionDB.inc.php");
	  $erreur=false;
	  $poidsProd= "";
	  $shipping="";

	  
	  for($i=0;$i< compterProd(); $i++){
		  
	     for($j=0; $j< $_SESSION['panier']['qteProd'][$i]; $j++){
			  
			  $titre=$_SESSION['panier']['libelleProd'][$i];
			  $select=$bd->query("SELECT poids FROM produit WHERE titre='$titre'");
			  $res=$select->fetch(PDO::FETCH_OBJ);
			  $poids=$res->poids;
			  
			  $poidsProd += $poids;
			  
			
			     
		  }
		  
	  }  
	  $select1=$bd->query("SELECT prix FROM poids WHERE nom >=$poidsProd ");
			 $res1= $select1->fetch(PDO::FETCH_OBJ);
				  
				$shipping=$res1->prix;  
			
	
	  return $shipping;
	  
	  
  }
	 
	 
?>