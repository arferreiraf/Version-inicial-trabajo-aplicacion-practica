<?php
error_reporting(0);
session_start();

	if(@$_SESSION['id_empresa']<>'')
	{
		$id_empresa					= "";
		$id_empresa 				= $_SESSION['id_empresa'];
		$_SESSION['nombre_sistema'] = "SUCONTROL.CL";
	}



$mysql= mysqli_connect("localhost","root","TransportesAlbertoDiaz13+")or die("cannot connect");
mysqli_select_db($mysql,"sucontro_".$id_empresa)or die("cannot select DB");
mysqli_query($mysql,"SET NAMES 'utf8'");



$sql						= "select opcion from parametro where proceso='conexion' and parametro='ip_publica'";
$result 					= mysqli_query($mysql,$sql);
$array						= mysqli_fetch_array($result);
$valor						= $array['opcion'];
$_SESSION['servidor']   	= $valor;

?>