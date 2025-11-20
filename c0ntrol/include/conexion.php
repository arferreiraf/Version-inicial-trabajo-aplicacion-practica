<?php
$mysql= mysqli_connect("localhost","root","TransportesAlbertoDiaz13+")or die("cannot connect");
mysqli_select_db($mysql,'sucontro_'.$id_empresa)or die("cannot select DB");

?>