<?php
session_start();

include("../include/connexionDB.inc.php");
?>

 <meta charset="utf-8">
 <link href="../Style/indexStyle.css" type="text/css" rel="stylesheet"/>  
<h2> Bienvenue <?php echo $_SESSION['pseudo'] ; ?> </h2>

<a href=" ?action=ajouter"> Ajouter un produit    </a>
<a href=" ?action=modifierAndSupprimer"> Modifier / Supprimer un produit </a> <br/><br>

<a href=" ?action=ajouter_categorie"> Ajouter une Catégorie    </a>
<a href=" ?action=modifierAndSupprimer_Categorie"> Modifier / Supprimer une Catégorie </a> <br/><br>
<a href=" ?action=options"> Options </a> <br/><br>


<?php

if(isset($_SESSION["pseudo"])&& isset($_SESSION["password"])){
  if(isset($_GET['action'])){
	  
	  
	
		
		?>
		    
	         <?php  
			       // #########################Script pour Ajouter un produit#########################
				 
			if($_GET['action']=='ajouter'){	   
				   if(isset($_POST['valider'])){
					   
					  $titre=htmlspecialchars($_POST['titre']);
					   $categorie=htmlspecialchars($_POST['categorie']);
					   $description=htmlspecialchars($_POST['description']);
					   $prix=htmlspecialchars($_POST['prix']);
					   $stock=htmlspecialchars($_POST['stock']);
					   
					   
			 //########################"""Ajout d'une image#########################################################
					   $image=$_FILES['image']['name'];             
					   
					    $image_temp=$_FILES['image']['tmp_name'];
					   
					   if(!empty($image_temp)){
						   
						   $image=explode('.',$image);
						   $image_ext=end($image);
						   
							   if(in_array(strtolower($image_ext),array('png','jpg','jpeg'))==false){
								   
								   echo"Veuillez rentrer une image ayant pour extention: png,jpeg ou jpg";
							   }else
							   
							   {
								   
								   $image_size=getimagesize($image_temp);
								   
								   if($image_size['mime']=='image/jpeg'){
									   
									   $image_src= imagecreatefromjpeg($image_temp);
									   
								   }else 
									   if($image_size['mime']=='image/png'){
									   
									   $image_src= imagecreatefrompng($image_temp);
									   }
									   else {
										   
										   $image_src=false;
										   echo"Veuillez rentrer une image valide";
									   }
									   
									   if($image_src!==false){
										   
										   $image_width=200;
										   
										   if($image_size[0]==$image_width){
												
											   $image_finale=$image_src;
											   
										   }else{
											   
											   $new_width[0]=$image_width;
											   $new_height[1]=200;
											   $image_finale=imagecreatetruecolor($new_width[0],$new_height[1]); 
											   imagecopyresampled($image_finale,$image_src,0,0,0,0,$new_width[0],$new_height[1],$image_size[0],$image_size[1]);
											   
										   }
										  imagejpeg($image_finale,'image/'.$titre.'.jpg');
										  
										  
									   }
									   
							   }
						   
						   
				        }else{
							echo"  Veuillez ajouter une image !!";
							
						}
					   if($titre && $categorie && $description && $prix && $stock ){
						  
                         $poids=htmlspecialchars($_POST['poids']);     //nom : Nom du poids
						 //$categorie=htmlspecialchars($_POST['categorie']);
						 
						 $select=$bd->query("SELECT prix FROM poids WHERE nom='$poids'");
						 $res=$select->fetch(PDO::FETCH_OBJ);
						 $shipping=$res->prix;
						 
						 
						 $select=$bd->query("SELECT tva FROM produit ");
						 $res=$select->fetch(PDO::FETCH_OBJ);
						 $tva=$res->tva;
						 $prix_finale=$prix+$shipping + (($prix+$shipping)*$tva/100);  //prix Total avec TVA + livraison
						 
						  
						  $sql="INSERT INTO produit VALUE('','$titre','$categorie','$description','$prix','$poids','$shipping','$tva','$prix_finale','$stock')";
						  $reponse=$bd->exec($sql);
						 
						 if($reponse){
							 
							 echo" <br>Produit ajouté avec succès";
						 }
						   
					   }else{
						   
						   echo" Veuillez saisir tous les champs ";
					   }
				   }
			 
			         //#####################################Formulaire de saisir de produit#####################
			 ?>
			 
       
		<form name="ajouterProduit" method="post" action="" enctype="multipart/form-data">
		  <h3>Titre:</h3> <input type="text" name="titre" >
		  
		  <h3>Catégorie:</h3> <select name="categorie" > 

                    <?php 
	                        $select=$bd->query("SELECT * FROM categorie");
							while($res=$select->fetch(PDO::FETCH_OBJ)){
								
							?>
							     <option> <?php echo $res->nomCat ;?>  </option>
							<?php
								
							}
	                 ?>



		  </select>
		  <h3>Description:</h3> <textarea name="description" >  </textarea>
		  <h3>Prix:</h3> <input type="text" name="prix" ><br>
		   <h3> Image: </h3> <input type="file" name="image"><br>
		  
		   <h3> Moins  de: </h3><select name="poids">
		   <?php 
	                        $select=$bd->query("SELECT * FROM poids  ");
							while($res=$select->fetch(PDO::FETCH_OBJ)){
								
							?>
							     <option> <?php echo $res->nom ;?>  </option>
							<?php
								
							}
	         ?>
		   
		   </select><br>
		   <h3>Stock : </h3> <input type="text" name="stock"> 
		   
		   <br><br>
		  <input type="submit" name="valider" value="Enregistré">
 		  
		
		</form>
		
		<?php 
	//#####################################"Modifier et supprimer###########################################
	}elseif($_GET['action']=='modifierAndSupprimer'){
		 
		include("../include/connexionDB.inc.php"); 
		echo"<br>";
		
		$select="SELECT * FROM produit";
		$reponse=$bd->prepare($select);
		$reponse->execute();
		
			//###################Afficher les elements de la base de données pour supprimer ou modifier######################
			
		while($resultat=$reponse->fetch(PDO::FETCH_OBJ)){
		
			
			echo $resultat->titre;
			
			?>
			<a href="?action=modifier&amp;id=<?php echo $resultat->Reference ?>"> Modifier</a>
			<a href="?action=supprimer&amp;id=<?php echo $resultat->Reference ;?>">X</a> <br/> <br/>
			<?php 
			
		}
		
	}
	//############################Modifier des données dans la base de données##############################################
	
	elseif($_GET['action']=='modifier'){  	
	    $id=$_GET['id'];
	      include('../include/connexionDB.inc.php');
		  
		  $reponse=$bd->prepare("SELECT * FROM produit WHERE Reference=? ");
		  $reponse->execute(array($id));
		    while( $resultat=$reponse->fetch()){
		  
		  
		?>                  
		  
		<form name="modifierProduit" method="post" action="" >
		  <h3>Titre:</h3> <input type="text" name="titre" value="<?php echo $resultat['titre']; ?>" > 
		  <h3>Catégorie:</h3> <input type="text" name="categorie" value="<?php echo $resultat['categorie']; ?>" >
		  <h3>Description:</h3> <textarea name="description" rows="5" cols="50" > <?php echo $resultat['description']; ?></textarea>
		  <h3>Prix:</h3> <input type="text" name="prix" value="<?php echo $resultat['prix']; ?>" >
			<h3> Stock: </h3> <input type="text" name="stock" value="<?php echo $resultat['stock'] ;?>"><br><br>
		  <input type="submit" name="enregistre" value="Modifier">
		  
 		  </form>
		
		<?php
			
			}
			// #######################Script Modifier le produit######################################################
		    if(isset($_POST['enregistre'])){
				
				 
				
						 
			  $titre=$_POST['titre'];
			  $categorie=htmlspecialchars($_POST['categorie']);
			  $description=htmlspecialchars($_POST['description']);
			  $prix=htmlspecialchars($_POST['prix']);
			$stock=htmlspecialchars($_POST['stock']);
			
			$select=$bd->query("SELECT * FROM produit WHERE Reference='$id' ");
						 $res=$select->fetch(PDO::FETCH_OBJ);
						 $shipping=$res->shipping;
						 
			$prix_finale=$prix+$shipping + (($prix+$shipping)*$tva/100);  //prix Total avec TVA + livraison
						 
		
						 
			  
			  if($titre && $categorie && $description && $prix && stock){        
				  
			      $req=" UPDATE produit set titre='$titre',categorie='$categorie',description='$description',prix='$prix',stock='$stock',prix_final='$prix_finale' WHERE Reference='$id' ";
				  $reponse = $bd->prepare($req);
				  $reponse->execute();
				      header("location:admin.php?action=modifierAndSupprimer");
			  
			  }else{
				  
				  echo"Veuillez remplir tous les champs SVP! ";
			  }
			
		     }
		
		//#################Supprimer des produits dans la base############
		
	}elseif($_GET['action']=='supprimer'){             
		include("../include/connexionDB.inc.php");
		$id=htmlspecialchars($_GET['id']);
		$reponse=$bd->prepare("DELETE FROM produit WHERE Reference=$id ");
		$reponse->execute();
	   
	   
	   //##############"Ajouter Catégorie############################
	   
	}else if($_GET['action']=='ajouter_categorie'){
		  if(isset($_POST['valider'])){
			  $nomCat=$_POST['cat'];
		        if($nomCat){
					
				  $reponse=$bd->exec("INSERT INTO categorie VALUE('','$nomCat')");
			  
			          if($reponse){
				          echo"$nomCat Ajouté avec SUCCES !";
			           }else{
				      echo " Erreur , veuillez réessayer";
			           }
				}else{
					echo"Veuillez saisir une categorie";
				} 
			  
		  }
		
		?> 
		<form method="post" name="ajouterCategorie" >
		<h3> Nom de  la catégorie :</h3> <input type="text" name="cat" ><br><br>
		 <input type="submit" name="valider" value="Enregistré">
		
		</form>
		
		<?php
		
		//##################Modifier et Supprimer les Catégorie##############################
	}elseif($_GET['action']=='modifierAndSupprimer_Categorie'){
		
		$select="SELECT * FROM categorie";
		$reponse=$bd->prepare($select);
		$reponse->execute();
		while($resultat=$reponse->fetch(PDO::FETCH_OBJ)){ //Afficher les elements de la base de données pour supprimer ou modifier
			
			echo $resultat->nomCat;
			
			?>
			<a href="?action=modifier_categorie&amp;id=<?php echo $resultat->id ?>"> Modifier</a>
			<a href="?action=supprimer_categorie&amp;id=<?php echo $resultat->id ;?>">X</a> <br/> <br/>
			<?php 
		}
		
		
		//####################Modifier les Catégories ###############################
		
	}elseif($_GET['action']=='modifier_categorie'){  	
	    $id=htmlspecialchars($_GET['id']);
		  
		  $reponse=$bd->prepare("SELECT * FROM categorie WHERE id=$id ");
		  $reponse->execute();
		    while( $resultat=$reponse->fetch()){
		  
		  
		?>                  
		  
		<form name="modifierProduit" method="post" action="" >
		  <h3>Catégorie:</h3> <input type="text" name="categorie" value="<?php echo $resultat['nomCat']; ?>" >
		   <input type="submit" name="enregistre" value="Modifier">
		  
 		  </form>
		
		<?php
			
			}
		    if(isset($_POST['enregistre'])){

			  $categorie=htmlspecialchars($_POST['categorie']);
			  
			  if($categorie){     
			  
				  $id=htmlspecialchars($_GET['id']);
				  $select=$bd->query("SELECT nomCat FROM categorie WHERE id='$id' ");
				  $res=$select->fetch(PDO::FETCH_OBJ);
				 
				  
			      $req=" UPDATE categorie set nomCat='$categorie' WHERE id='$id' ";
				  $reponse = $bd->prepare($req);
				  $reponse->execute();
				  
				    $update=$bd->query("UPDATE produit  SET categorie='$categorie' WHERE categorie='$res->nomCat'");
				      header("location:admin.php?action=modifierAndSupprimer_Categorie");
					  
			  }else{
				  
				  echo"Veuillez remplir tous les champs SVP! ";
			  }
			
		     }
		
		//##############Supprimer des categorie############################
		
	}elseif($_GET['action']=='supprimer_categorie'){
		$id=htmlspecialchars($_GET['id']);
		$reponse=$bd->prepare("DELETE FROM categorie WHERE id=$id ");
		$reponse->execute();
		 header("location:admin.php?action=modifierAndSupprimer_Categorie");
		
		
		//##########################Options###########################################
	}elseif($_GET['action']=='options'){
		          //$$$$$$$$$$$ Formulaire du poids $$$$$$$$$$$$$$$$$$$$$$$
		?>
		<h2>Frais de ports : <h2>
		<h3> Options de poids </h3>
		<?php
		   $select=$bd->query("SELECT * FROM poids ");
		   while($res=$select->fetch(PDO::FETCH_OBJ)){
			   
			   ?>
			     <form name="" method="post" >
              				<input type="text" name="nomPoids" value="<?php echo $res->nom ;?>">
							<a href="?action=modifierPoids&amp;nom=<?php echo $res->nom ;?>" > Modifier </a><br><br>
				 </form>
 
			   <?php
			     
		   } // ####################Modifier Tva ######################
		   
		         $select=$bd->query("SELECT tva FROM produit ");
				 $res=$select->fetch(PDO::FETCH_OBJ);
				 
				 if(isset($_POST['modifierTva'])){
					 
					 $tva=htmlspecialchars($_POST['tva']);
					 
					 if(!empty($tva)){
						 $req=$bd->exec("UPDATE produit SET tva='$tva' ");
						 header("location:admin.php?action=options");
					 }
				 }
				 
		   ?>           
                 <form method="post" action=""> 		   
		             <h3> Tva: </h3> <input type="text" name="tva" value="<?php echo $res->tva ; ?>" >
					 <input type="submit" name="modifierTva" value="Modifier">
				</form>
		   <?php
				 
		   ##################Modifier Poids #############################
	}elseif ($_GET['action']=='modifierPoids'){
		
		$nom=htmlspecialchars($_GET['nom']);
		$select=$bd->query("SELECT * FROM poids WHERE nom='$nom'");
			$res=$select->fetch(PDO::FETCH_OBJ);
		
		if(isset($_POST['modifier'])){           
			$newNom=htmlspecialchars($_POST['nomPoids']);
			$prix=htmlspecialchars($_POST['prix']);
			
		     if(!empty($_POST['nomPoids'])&& !empty($_POST['prix'])){
		    $req=$bd->exec("UPDATE poids SET nom='$newNom', prix='$prix' WHERE nom='$nom'");
			
			
			
			
			header("location:admin.php?action=options");
			}else{
				echo"Veuillez renseigner un nom ";
			}
			
			 
		}
		
		?>
		<h2>Frais de ports : <h2>
		<h3> Options de poids </h3>
		<form action="" method="post" >
		   <h3> Poids (plus  de):</h3><input type="text" name="nomPoids" value="<?php echo $nom;  ?>">
		  <h3> Prix Correspondant: </h3><input type="text" name="prix" value="<?php echo $res->prix ;?>"> Dhs <br>
		  <input type="submit" name="modifier" value="Modifier">
		  
		</form>
		<?php
		
	}else{
		
		die("Une ERREUR s'est produite");
     }
  }
  
}else{
	
	header("location:indexAdmin.php");
   }

  


?>


