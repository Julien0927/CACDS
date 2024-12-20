<?php
try {
  
  /*$db = new PDO('mysql:host=mysql-julienvarachas.alwaysdata.net; dbname=julienvarachas_willrunexpert;port=3306; charset=utf8mb4','327887', 'T0mEmm@1114');*/
  $db = new PDO ('mysql:host=localhost; dbname=cacds1; port=3306; charset=utf8mb4', 'root', '');
 


$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} 
catch (PDOException $e){
  echo 'erreur de connexion à la base de données' . $e -> getMessage();
}