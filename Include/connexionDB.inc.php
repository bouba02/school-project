<?php

try{
$bd= new PDO('mysql:host=localhost;dbname=site-e-commerce','root','root',array(\PDO::MYSQL_ATTR_INIT_COMMAND =>  'SET NAMES utf8'));
} catch(PDOException $e){
		
		die("La connexion a échoué pour la raison suivante:".$e->getMessage());
	}
	
	//echo"Connexion Etablie";
?>