<?
session_start();
include '../include/conexionSistema.php';
include 'header.php';

if($_REQUEST['guardar'])
{
	include '../funcion/funciones.php';

	$resultado= responderDocumento($_REQUEST['nombre_tabla'],$_REQUEST['numero_interno'],$_REQUEST['estado'],$_REQUEST['comentario']);

	echo "<script>opener.location.reload();window.close();</script>";

}

?>
<div id="tabs">
	<ul>
		<li><a href="#tabs-1">Aprobar Documento</a></li>
	</ul>
	<div id="tabs-1">
	<form>
		<table width='100%'>
			<tr>
				<td ALIGN='right'>NUMERO INTERNO:</td>
				<td><input type='text' name='' value='<? echo $_REQUEST['numero_interno'];?>' disabled></td>
			</tr>
			<tr>
				<td ALIGN='right'>APROBAR</td>
				<td><input type='radio' name='estado' value='APROBADO' required></td>
			</tr>
			<tr>
				<td ALIGN='right'>RECHAZADO</td>
				<td><input type='radio' name='estado' value='RECHAZADO' required></td>
			</tr>
			<tr>
				<td ALIGN='right' valign='top'>COMENTARIO:</td>
				<td><textarea name='comentario' rows='4' cols='40'></textarea></td>
			</tr>
			<tr>
				<td><input type='button' name='cancelar' onClick="window.close();" value='Cancelar'></td>
				<td><input type='submit' name='guardar' value='Guardar'> </td>
			</tr>
		</table>
		<input type='hidden' name='nombre_tabla' value='<? echo $_REQUEST['nombre_tabla'];?>'>
		<input type='hidden' name='numero_interno' value='<? echo $_REQUEST['numero_interno'];?>'>
	</form>
	</div>
</div>

<?include 'footer.php';?>
