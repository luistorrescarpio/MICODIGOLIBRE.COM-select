<?php 
//Script para conexión con base de datos en Mysql
include("db_script/heasy_mysql.php");
// Get Params Client
$obj = (object)$_REQUEST;

//Ejecutar Consultas en MYSQL desde PHP
switch ($obj->action) {
  case 'bookSearch':
    $r = query("SELECT * FROM libro WHERE {$obj->campo} LIKE '%{$obj->word}%' ");
    server_res($r);
    break;  
  case 'getInfoBook':
  	$r = query("SELECT * FROM libro WHERE id_libro='{$obj->id_libro}' ");
  	server_res($r);
  	break;
}
?>
