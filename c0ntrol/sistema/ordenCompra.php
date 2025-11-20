<?

include 'header.php';
include 'loader1.php';
include '../funcion/reporte.php';
include '../funcion/funciones.php';
include '../include/conexionSistema.php';



if($_REQUEST['eliminar_adjunto']){
	eliminarArchivoAdjunto($_REQUEST['id_archivo_adjunto'],$_REQUEST['carpeta'],$_REQUEST['nombre_archivo']);
}


if($_REQUEST['actualizar_item']){

	//lista el detalle de la OC para luego actualizar los datos
	$sql		=	"select id_articulo from orden_compra_detalle where numero_interno='".$_REQUEST['numero_interno']."'";
	$result 	=	mysqli_query($mysql,$sql);
	while($row  = 	mysqli_fetch_array($result)){
		if($_REQUEST[$_REQUEST['numero_interno']."_".$row['id_articulo']]){
			actualizaItemOC($_REQUEST['numero_interno'],$row['id_articulo'],$_REQUEST[$_REQUEST['numero_interno']."_".$row['id_articulo']]);
		}
	}

	echo "<table width='100%'><tr><td bgcolor='green'><font color='white'>Se han actualizado los datos exitosamente!</font></td></tr></table>";
}

if($_REQUEST['generar_guia']){
	echo generarGuiaPorOC($_REQUEST['numero_interno']);
}


if($_REQUEST['actualizar_factura']){
	echo actualizarFacturaOrdenCompra($_REQUEST['numero_interno'],$_REQUEST['numero_boleta'],$_REQUEST['numero_factura'],$_REQUEST['fecha_factura'],$_REQUEST['factura_pagada'],$_REQUEST['comentario_factura'],$_REQUEST['despachar_a'],$_REQUEST['ot'],$_REQUEST['tipo_gasto'],$_REQUEST['boleta_honorario']);
}



if($_REQUEST['descripcion_articulo'] and $_REQUEST['cantidad'] and $_REQUEST['id_bodega_destino']){
	//envia las variables para insertar el detalle

	//si el numero interno es vacio debe insertar el id del usuario
	if($_REQUEST['numero_interno']==''){
		insertaDetalleOrdenCompra($_SESSION['id_usuario'],$_REQUEST['descripcion_articulo'],$_REQUEST['id_bodega_destino'],$_REQUEST['cantidad'],$_REQUEST['costo_promedio']);
	}else{
		insertaDetalleOrdenCompra($_REQUEST['numero_interno'],$_REQUEST['descripcion_articulo'],$_REQUEST['id_bodega_destino'],$_REQUEST['cantidad'],$_REQUEST['costo_promedio']);
	}

	//vacia las variables
	$_REQUEST['descripcion_articulo']	="";
	$_REQUEST['cantidad']				="";
	$_REQUEST['id_bodega_destino']		="";

}


if($_REQUEST['eliminar_detalle'])
{
	eliminaDetalleOrdenCompra($_REQUEST['numero_interno'],$_REQUEST['id_detalle']);
}

if($_REQUEST['anular_guia'])
{
	$resultado_anular_orden_compra = anularOrdenCompra($_REQUEST['numero_interno'],$_REQUEST['comentario_anulacion']);

	echo "<script>alert('$resultado_anular_orden_compra');</script>";

	$resultado_anular_orden_compra="";
}


//si presiona el boton guardar
if($_REQUEST['guardar'])
{


	//si trae numero interno
	if($_REQUEST['numero_interno'])
	{
			//modifica la OC

			$numero_interno = $_REQUEST['numero_interno'];

			modificarOrdenCompra($numero_interno,$_REQUEST['codigo_movimiento'],$_REQUEST['fecha_ingreso'],$_REQUEST['rut_empresa'],$_REQUEST['nombre_proveedor'],$_REQUEST['contacto_proveedor'],$_REQUEST['razon_emision'],$_REQUEST['id_condicion_pago'],$_REQUEST['solicitado_por'],$_REQUEST['numero_boleta'],$_REQUEST['boleta_honorario'],$_REQUEST['numero_factura'],$_REQUEST['fecha_factura'],$_REQUEST['factura_pagada'],$_REQUEST['comentario_factura'],$_REQUEST['folio_memo'],$_REQUEST['fecha_memo'],$_REQUEST['numero_cotizacion'],$_REQUEST['observaciones'],$_REQUEST['despachar_a'],$_REQUEST['ot'],$_REQUEST['tipo_gasto'],$_REQUEST['tipo_documento'],$_REQUEST['check_supervisor'],$_REQUEST['numero_nota_credito'],$_REQUEST['fecha_nota_credito'],$_REQUEST['monto_nota_credito']);
	}
	else
	{
			//inserta una OC
			$numero_interno = guardarOrdenCompra($_REQUEST['codigo_movimiento'],$_REQUEST['fecha_ingreso'],$_REQUEST['rut_empresa'],$_REQUEST['nombre_proveedor'],$_REQUEST['contacto_proveedor'],$_REQUEST['razon_emision'],$_REQUEST['id_condicion_pago'],$_REQUEST['solicitado_por'],$_REQUEST['numero_boleta'],$_REQUEST['boleta_honorario'],$_REQUEST['numero_factura'],$_REQUEST['fecha_factura'],$_REQUEST['factura_pagada'],$_REQUEST['comentario_factura'],$_REQUEST['folio_memo'],$_REQUEST['fecha_memo'],$_REQUEST['numero_cotizacion'],$_REQUEST['observaciones'],$_REQUEST['despachar_a'],$_REQUEST['ot'],$_REQUEST['tipo_gasto'],$_REQUEST['tipo_documento'],$_REQUEST['check_supervisor'],$_REQUEST['numero_nota_credito'],$_REQUEST['fecha_nota_credito'],$_REQUEST['monto_nota_credito']);
	}

	$sql		=	"select * from orden_compra where numero_interno='$numero_interno'";
	$result 	= 	mysqli_query($mysql,$sql);
	$array		= 	mysqli_fetch_array($result);
	$neto 		= 	$array['estado'];
	$neto 		=	$array['neto'];
	$iva 		= 	$array['iva'];
	$total 		= 	$array['total'];
}






if($_REQUEST['numero_interno']<>'' or $numero_interno>0)
{
	if($_REQUEST['numero_interno']<>'')
	{
		 $numero_interno 	=	$_REQUEST['numero_interno'];
		 $sql				=	"select * from orden_compra where numero_interno='".$_REQUEST['numero_interno']."'";
	}
	else
	{
		 $sql				=	"select * from orden_compra where numero_interno='".$numero_interno."'";
		 $_REQUEST['numero_interno'] = $numero_interno;
	}
	$result 				= mysqli_query($mysql,$sql);
	$array					= mysqli_fetch_array($result);
    $estado					= $array['estado'];
	$guia_despacho			= $array['guia_despacho'];
	$codigo_movimiento 		= $array['codigo_movimiento'];
	$descripcion_movimiento = $array['descripcion_movimiento'];
	$fecha_ingreso 			= $array['fecha_ingreso'];
	$rut_empresa			= $array['rut_empresa'];
	$id_condicion_pago		= $array['id_condicion_pago'];
	$rut_proveedor			= $array['rut_proveedor'];
	$nombre_proveedor		= $array['nombre_proveedor'];
	$contacto_proveedor		= $array['contacto_proveedor'];
	$digitador 				= $array['digitador'];
	$razon_emision			= $array['razon_emision'];
	$neto 					= $array['neto'];
	$iva 					= $array['iva'];
	$total 					= $array['total'];
	$fecha_digitacion		= $array['fecha_digitacion'];
	$solicitado_por			= $array['solicitado_por'];
	$numero_boleta			= $array['numero_boleta'];
	$boleta_honorario		= $array['boleta_honorario'];
	$numero_factura			= $array['numero_factura'];
	$fecha_factura			= $array['fecha_factura'];
	$factura_pagada			= $array['factura_pagada'];
	$comentario_factura		= $array['comentario_factura'];
	$despachar_a			= $array['despachar_a'];
	$folio_memo				= $array['folio_memo'];
	$fecha_memo				= $array['fecha_memo'];
	$ot						= $array['ot'];
	$tipo_gasto				= $array['tipo_gasto'];
	$patente				= $array['patente'];
	$numero_cotizacion		= $array['numero_cotizacion'];
	$observaciones			= $array['observaciones'];
	$tipo_documento			= $array['tipo_documento'];
	$comentario_anulacion	= $array['comentario_anulacion'];
	$fecha_anulacion		= $array['fecha_anulacion'];
	$usuario_anulacion		= $array['usuario_anulacion'];
	$check_supervisor		= $array['check_supervisor'];
	$numero_nota_credito	= $array['numero_nota_credito'];
	$fecha_nota_credito		= $array['fecha_nota_credito'];
	$monto_nota_credito		= $array['monto_nota_credito'];


	if($estado==''){
		$numero_interno="";
	}

	// si la guia esta recepcionada o nula no se puede anular ni tampoco eliminar un item. tampoco se pueden agregar mas items
	if($estado=='APROBADO' or $estado=='NULA' or $estado=='RECHAZADO') { $disabled="disabled"; }


}


//descripcion del tipo de movimiento
if($codigo_movimiento)
{
	$sql="select descripcion,tipo from tipo_movimiento where codigo_movimiento='".$codigo_movimiento."'";
}
else
{
	$sql="select descripcion,tipo from tipo_movimiento where codigo_movimiento='".$_REQUEST['codigo_movimiento']."'";
	$codigo_movimiento = $_REQUEST['codigo_movimiento'];
}

$result = mysqli_query($mysql,$sql);
$array  = mysqli_fetch_array($result);
$descripcion_movimiento		 = $array['descripcion'];
$tipo_movimiento 			 = $array['tipo'];


//trae los datos del proveedor

if($rut_proveedor)
{
	 $sql="select * from proveedor where rut_proveedor ='".$rut_proveedor."'";
}
else
{
	 $sql="select * from proveedor where nombre ='".$_REQUEST['nombre_proveedor']."'";
}
$result 			= mysqli_query($mysql,$sql);
$array  			= mysqli_fetch_array($result);

if($nombre_proveedor==''){$nombre_proveedor 	= $array['nombre']; $rut_proveedor=$array['rut_proveedor'];}
$contacto_proveedor = $array['contacto'];
if($_REQUEST['contacto_proveedor']){$contacto_proveedor=$_REQUEST['contacto_proveedor'];}

if($_REQUEST['numero_cotizacion']){  $numero_cotizacion=$_REQUEST['numero_cotizacion'];}


$sql		=	"select * from articulo where descripcion ='".$_REQUEST['descripcion_articulo']."' or id_articulo in (select id_articulo from articulo_codigo_barra where codigo_barra = '".$_REQUEST['descripcion_articulo']."')";
$result 	= 	mysqli_query($mysql,$sql);
$array  	= 	mysqli_fetch_array($result);

//para mostrar el costo de un producto, debe llegar la variable descripcion
if($_REQUEST['descripcion_articulo']<>'')
{
	if($_REQUEST['costo_promedio'])
	{
		$costo_promedio 	  = $_REQUEST['costo_promedio'];
	}
	else
	{
		$costo_promedio 	  = $array['costo_promedio'];
	}
}

$requiere_aprobacion = requiereAprobacion($codigo_movimiento);

//consulta si el usuario puede modificar la oc cuando se encuentra en estado aprobado
$sql							=	"select
										(select count(*)   from permiso_usuario where id_usuario='".$_SESSION['id_usuario']."' and id_permiso in (select id_permiso from permiso where nombre_permiso='Modifica OC en Estado Aprobado'))modifica_oc_estado_aprobado,
										(select count(*)   from permiso_usuario where id_usuario='".$_SESSION['id_usuario']."' and id_permiso in (select id_permiso from permiso where nombre_permiso='Check Supervisor'))permiso_check_supervisor";
$result 						=	mysqli_query($mysql,$sql);
$array							=	mysqli_fetch_array($result);
$modifica_oc_estado_aprobado	=	$array['modifica_oc_estado_aprobado'];
$permiso_check_supervisor		=	$array['permiso_check_supervisor'];

?>
<script>



function enviado() {

	var cuenta=0;

	if (cuenta == 0){
		cuenta++;
		return true;
	}else {

		alert("El formulario ya está siendo enviado, por favor aguarde un instante.");

		return false;
	}
}


function eliminarItem(numero_interno,id_detalle,id_articulo,nombre_articulo){
	r = confirm('¿Desea eliminar el item '+nombre_articulo+' ?');

	if(r === true){
		document.location.href='ordenCompra.php?numero_interno='+numero_interno+'&id_detalle='+id_detalle+'&eliminar_detalle=s';
	}
}


function anularOrdenCompra(numero_interno){
	comentario_anulacion = prompt('¿Esta seguro que desea anular la orden de compra '+numero_interno+'?','Ingrese comentario');

	if(comentario_anulacion){
		document.location.href='ordenCompra.php?numero_interno='+numero_interno+'&anular_guia=s&comentario_anulacion='+comentario_anulacion;
	}
}


function buscarOrdenCompra(ultima_oc) {
	var numero_interno;

	numero_interno = prompt("Numero de Orden de Compra:",ultima_oc);

	if(numero_interno>0){
		document.location.href='ordenCompra.php?numero_interno='+numero_interno;

		numero_interno = null;
	}

}

function generarGuia(numero_interno){
	r = confirm('¿Desea generar una guia para la OC '+numero_interno+'?');

	if(r === true){
		document.location.href='ordenCompra.php?numero_interno='+numero_interno+'&generar_guia=generar_guia';
	}
}

function imprimirOC(numero_interno){

	ancho = 900; // ancho de tu swf
	alto = 700;  // alto de tu swf
	y = screen.height/2 - alto/2;
	x = screen.width/2 - ancho/2;
	window.open('imprimirOC.php?numero_interno='+numero_interno, 'w', 'width='+ancho+',height='+alto+',left='+x+',top='+y+',status=no,scrollbars=yes');
}

function adjuntarArchivo(numero_interno,carpeta){
	ancho = 550; // ancho de tu swf
	alto = 200;  // alto de tu swf
	y = screen.height/2 - alto/2;
	x = screen.width/2 - ancho/2;
	window.open('popUp_adjuntarArchivo.php?numero_interno='+numero_interno+'&carpeta='+carpeta, 'w', 'width='+ancho+',height='+alto+',left='+x+',top='+y+',status=no,scrollbars=yes');
}

function agregarItem(numero_interno){
	ancho = 700; // ancho de tu swf
	alto = 200;  // alto de tu swf
	y = screen.height/2 - alto/2;
	x = screen.width/2 - ancho/2;
	window.open('popUp_agregarItemOC.php?numero_interno='+numero_interno, 'w', 'width='+ancho+',height='+alto+',left='+x+',top='+y+',status=no,scrollbars=yes');
}

function modificarItem(id_detalle,numero_interno){
	ancho = 700; // ancho de tu swf
	alto = 200;  // alto de tu swf
	y = screen.height/2 - alto/2;
	x = screen.width/2 - ancho/2;
	window.open('popUp_modificarItemOC.php?id_detalle='+id_detalle+'&numero_interno='+numero_interno, 'w', 'width='+ancho+',height='+alto+',left='+x+',top='+y+',status=no,scrollbars=yes');
}

function buscaProveedor(){
	ancho = 850; // ancho de tu swf
	alto = 400;  // alto de tu swf
	y = screen.height/2 - alto/2;
	x = screen.width/2 - ancho/2;
	window.open('popUp_buscaProveedor.php', 'w', 'width='+ancho+',height='+alto+',left='+x+',top='+y+',status=no,scrollbars=yes');
}

function nombreProveedor(rut_proveedor) {
    if (rut_proveedor.length == 0) {
        document.getElementById("nombre_proveedor").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("nombre_proveedor").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "../ajax/ajax_oc_nombre_proveedor.php?rut_proveedor=" + rut_proveedor, true);
        xmlhttp.send();
    }
}


function mostrarArchivoAdjunto(carpeta,nombre_archivo){
	ancho = 400; // ancho de tu swf
	alto = 100;  // alto de tu swf
	y = screen.height/2 - alto/2;
	x = screen.width/2 - ancho/2;
	window.open('../adjuntos/'+carpeta+'/'+nombre_archivo+'', 'w', 'width='+ancho+',height='+alto+',left='+x+',top='+y+',status=no,scrollbars=yes');
}

function eliminarArchivoAdjunto(carpeta,numero_interno,id_archivo_adjunto,nombre_archivo){
	r = confirm('Desea eliminar el archivo adjunto ?');

	if(r === true){
		document.location.href='ordenCompra.php?id_archivo_adjunto='+id_archivo_adjunto+'&numero_interno='+numero_interno+'&carpeta='+carpeta+'&eliminar_adjunto=s&nombre_archivo='+nombre_archivo+'';
	}
}

function checkSubmit() {
    document.getElementById("guardar").value = "Enviando...";
    document.getElementById("guardar").disabled = true;
    return true;
}


function aprobarDocumento(numero_interno,nombre_tabla)
{
	ancho = 600; // ancho de tu swf
	alto = 300;  // alto de tu swf
	y = screen.height/2 - alto/2;
	x = screen.width/2 - ancho/2;
	window.open('aprobarDocumento.php?numero_interno='+numero_interno+'&nombre_tabla='+nombre_tabla+'', 'w', 'width='+ancho+',height='+alto+',left='+x+',top='+y+',status=no,scrollbars=yes');
}
</script>


<div id="tabs">
	<ul>
		<li><a href="#tabs-1">Orden de Compra</a></li>
		<li><a href="#tabs-2">Nota de Crédito</a></li>
		<li><a href="#tabs-3">Adjuntos</a></li>
	</ul>
	<div id="tabs-1">

	<?
	$sql		=		"select max(numero_interno) ultima_oc from orden_compra";
	$result		=		mysqli_query($mysql,$sql);
	$array		=		mysqli_fetch_array($result);
	$ultima_oc	=		$array['ultima_oc'];
	?>

	<table>
	<tr>
		<td><img src='../images/atras.png' 		onclick="document.location.href='principal.php';" 				title='Atras'></td>
		<td><img src='../images/recargar.png' 	onclick="document.location.href='ordenCompra.php';" 			title='Actualizar'></td>
		<td><? if($estado=='APROBADO'){?><img src='../images/imprimir.png' 	onclick="imprimirOC(<? echo $_REQUEST['numero_interno'];?>);"  	title='Imprimir'><?}?></td>
		<td><img src='../images/lupa.png'	 	onClick="buscarOrdenCompra(`<? echo $ultima_oc;?>`)" title='Buscar'></td>
		<td><? echo consultaApruebaOC($_REQUEST['numero_interno']);?></td>
		<td><input type='button' name='' value='<<' <? if(buscaNumeroInternoAnteriorDocumento($_REQUEST['numero_interno'],'orden_compra')<>0){ echo "onClick='document.location.href=`ordenCompra.php?numero_interno=".($_REQUEST['numero_interno']-1)."`;';";}else{ echo "disabled";}?>></td>
		<td><input type='button' name='' value='>>' <? if(buscaNumeroInternoSiguienteDocumento($_REQUEST['numero_interno'],'orden_compra')<>0){ echo "onClick='document.location.href=`ordenCompra.php?numero_interno=".($_REQUEST['numero_interno']+1)."`;';";}else{ echo "disabled";}?>></td>
		<td></td>
	 </tr>
	 </table>
	 <form method='post' action='ordenCompra.php'>
			<table>
				<tr>
					<td valign='top'>
						<fieldset>
							<legend>Orden de Compra</legend>
						<table>
							<tr>
								<td>Tipo Guia *:</td>
								<td>
									<select name='codigo_movimiento' onChange='submit()' style='width:235px' <? echo $disabled;?>>
										<?
											$sql="select * from tipo_movimiento where descripcion in ('ORDEN DE COMPRA')";
											$result = mysqli_query($mysql,$sql);
											while($row = mysqli_fetch_array($result))
											{
												if($row['codigo_movimiento']==$_REQUEST['codigo_movimiento'])
												{
													echo "<option value='".$row['codigo_movimiento']."' selected>".$row['codigo_movimiento']." - ".$row['descripcion']."</option>";
												}
												else
												{
													echo "<option value='".$row['codigo_movimiento']."'>".$row['codigo_movimiento']." - ".$row['descripcion']."</option>";
												}
											}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td>Tipo Documento *:</td>
								<td>
									<select name='tipo_documento' onChange='submit()' style='width:235px' <? echo $disabled;?> required>
										<?
											if($_REQUEST['tipo_documento']){
												$tipo_documento=$_REQUEST['tipo_documento'];
											}

											$sql		=	"select * from tipo_movimiento where referencia in ('ORDEN_COMPRA') and tipo='EGRESO' order by codigo_movimiento desc";
											$result 	= 	mysqli_query($mysql,$sql);
											while($row 	= 	mysqli_fetch_array($result))
											{
												if($row['codigo_movimiento']==$tipo_documento)
												{
													echo "<option value='".$row['codigo_movimiento']."' selected>".$row['codigo_movimiento']." - ".$row['descripcion']."</option>";
												}
												else
												{
													echo "<option value='".$row['codigo_movimiento']."'>".$row['codigo_movimiento']." - ".$row['descripcion']."</option>";
												}
											}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td>OC:</td>
								<td>
									<input type='text' value='<? echo $numero_interno;?>' style='width:230px' disabled>
									<input type='hidden' name='numero_interno' value='<? echo $numero_interno;?>'>
								</td>
							</tr>
							<tr>
								<td>Fecha Ingreso *:</td>
								<td><input type='date' name='fecha_ingreso' value='<? if($fecha_ingreso){ echo $fecha_ingreso;}else{ if($_REQUEST['fecha_ingreso']){echo $_REQUEST['fecha_ingreso'];}else{ echo date('Y-m-d');}} ?>' style='width:230px' required <? echo $disabled;?>></td>
							</tr>
							<tr>
								<td>Empresa:</td>
									<td>
										<select name='rut_empresa' required style='width:235px' <? echo $disabled;?>>
											<option value=''>Seleccione...</option>
											<?
												$sql		=	"select  e.* ,
																(select empresa_predefinida from usuario_empresa where rut_empresa=e.rut_empresa and id_usuario='".$_SESSION['id_usuario']."') empresa_predefinida
																from empresa e where e.estado='ACT' and e.rut_empresa in (select rut_empresa from usuario_empresa where id_usuario='".$_SESSION['id_usuario']."')";
												$result 	= 	mysqli_query($mysql,$sql);
												while($row 	= 	mysqli_fetch_array($result))
												{
													if($_REQUEST['rut_empresa'])
													{
														if($_REQUEST['rut_empresa']==$row['rut_empresa'])
														{
															echo "<option value='".$row['rut_empresa']."' selected>".$row['nombre_empresa']."</option>";
														}
														else
														{
															echo "<option value='".$row['rut_empresa']."'>".$row['nombre_empresa']."</option>";
														}
													}
													else
													{
														if($rut_empresa == $row['rut_empresa'])
														{
															echo "<option value='".$row['rut_empresa']."' selected>".$row['nombre_empresa']."</option>";
														}
														else
														{
															if($row['empresa_predefinida']==1 and $rut_empresa==''){
																echo "<option value='".$row['rut_empresa']."' selected>".$row['nombre_empresa']."</option>";
															}else{
																echo "<option value='".$row['rut_empresa']."'>".$row['nombre_empresa']."</option>";
															}
														}
													}
												}
											?>
										</select>
									</td>
							</tr>
							<tr>
							<td>Rut Proveedor *:</td>
							<td><input type='text'  value='<? echo $rut_proveedor; ?>' style='width:230px' disabled >
							</td>
							</tr>
							<tr>
							<td>Proveedor *:</td>
							<td>
								<input list='nombre_proveedor' name='nombre_proveedor' value='<? if($_REQUEST['nombre_proveedor']){ echo $_REQUEST['nombre_proveedor'];}else{echo $nombre_proveedor;}?>' onBlur='submit();' style='width:230px'>
								<datalist id='nombre_proveedor'>
									<?
										//lista todos los repuestos que se encuentren activos
										$sql	=	"select rut_proveedor,nombre from proveedor where estado='ACT'";
										$result = 	mysqli_query($mysql,$sql);
										while($row = mysqli_fetch_array($result))
										{
											echo "<option value='".$row['nombre']."'>".$row['rut_proveedor']."</option>";
										}
									?>
								</datalist>
							</td>
							</tr>
							<tr>
								<td>Contacto:</td>
								<td><input type='text' name='contacto_proveedor' value='<? if($_REQUEST['contacto_proveedor']){ echo strtoupper($_REQUEST['contacto_proveedor']);}else{ echo strtoupper($contacto_proveedor);} ?>' style='width:230px' <? echo $disabled;?>></td>
							</tr>
							<tr>
								<td>Razon Emision *:</td>
								<td><input type='text' name='razon_emision' onBlur='submit();' value='<? if($_REQUEST['razon_emision']){ echo strtoupper($_REQUEST['razon_emision']);}else{ if($razon_emision){echo $razon_emision;}else{echo "ABASTECIMIENTO DE INVENTARIO";}} ?>' style='width:230px' required <? echo $disabled;?> ></td>
							</tr>
							<tr>
								<td>Condicion de Pago *:</td>
									<td>
										<select name='id_condicion_pago' required style='width:235px' <? echo $disabled;?> onBlur='submit();'>
											<option value=''>Seleccione...</option>
											<?
												$sql		=	"select * from condicion_pago where estado='ACT'";
												$result 	= 	mysqli_query($mysql,$sql);
												while($row 	= 	mysqli_fetch_array($result))
												{
													if($id_condicion_pago)
													{
														if($id_condicion_pago==$row['id_condicion_pago'])
														{
															echo "<option value='".$row['id_condicion_pago']."' selected>".$row['descripcion']."</option>";
														}
														else
														{
															echo "<option value='".$row['id_condicion_pago']."'>".$row['descripcion']."</option>";
														}
													}
													else
													{
														if($_REQUEST['id_condicion_pago'] == $row['id_condicion_pago'])
														{
															echo "<option value='".$row['id_condicion_pago']."' selected>".$row['descripcion']."</option>";
														}
														else
														{
															echo "<option value='".$row['id_condicion_pago']."'>".$row['descripcion']."</option>";
														}
													}
												}
											?>
										</select>
									</td>
							</tr>
							<tr>
								<td>Solicitado Por*:</td>
								<td><input type='text' name='solicitado_por' value='<? if($_REQUEST['solicitado_por']=='' and $solicitado_por==''){ echo strtoupper($_SESSION['id_usuario']);}else{ if($_REQUEST['solicitado_por']){ echo strtoupper($_REQUEST['solicitado_por']);}else{echo strtoupper($solicitado_por);}} ?>' style='width:230px' required <? echo $disabled;?> onBlur='submit();'></td>
							</tr>
							<tr>
								<td>Numero Cotizacion:</td>
								<td><input type='text' name='numero_cotizacion' value='<? if($_REQUEST['numero_cotizacion']){ echo $_REQUEST['numero_cotizacion'];}else{ echo $numero_cotizacion;} ?>' style='width:230px' <? echo $disabled;?> onBlur='submit();'></td>
							</tr>
						</table>
						</fieldset>
					</td>
					<td valign='top'>
						<fieldset>
							<legend>Informacion Principal</legend>
							<table>
								<tr>
									<td>Requiere Aprobacion:</td>
									<td><input type='checkbox'  disabled <? if($requiere_aprobacion=='SI'){ echo "checked";}?>>
									<?
										if($permiso_check_supervisor==1){
											echo "&nbsp;Revisado por Supervisor:
												  &nbsp;<input type='checkbox' name='check_supervisor' value='1' "; if($check_supervisor==1){ echo "checked";} echo " onClick=''>
													";
										}
									?>
									</td>
								</tr>
								<tr>
									<td>Fecha Digitacion:</td>
									<td><input type='text' style='width:230px;' disabled value='<? if($numero_interno<>''){ echo date('d-m-Y H:i',strtotime($fecha_digitacion));}?>'></td>
								</tr>
								<tr>
									<td>Estado:</td>
									<td><input type='text' style='width:230px;color:red;' style='' disabled value='<? echo $estado;?>'></td>
								</tr>
								<tr>
									<td>Digitador:</td>
									<td><input type='text' style='width:230px' disabled value='<? if($digitador){ echo strtoupper($digitador);}else{ echo strtoupper($_SESSION['id_usuario']);} ?>'></td>
								</tr>
								<tr>
									<td>Guia:</td>
									<td><input type='text' style='width:230px' disabled value='<? echo $guia_despacho;?>'></td>
								</tr>
								<tr>
									<td>Neto:</td>
									<td><input type='text' style='width:230px' value='$ <? echo number_format($neto,0,'','.');?>' style='text-align:right' disabled></td>
								</tr>
								<tr>
									<td>Impuesto:</td>
									<td><input type='text' style='width:230px'  value='$ <? echo number_format($iva,0,'','.');?>' style='text-align:right' disabled></td>
								</tr>
								<tr>
									<td>Total:</td>
									<td><input type='text' style='width:230px'  value='$ <? echo number_format($total,0,'','.');?>' style='text-align:right' disabled></td>
								</tr>
								<tr>
									<td valign='top'>Observaciones:</td>
									<td><textarea name='observaciones' rows='2' style='width:230px' <? echo $disabled;?> onBlur='submit();'><? if($observaciones){ echo $observaciones;}else{ echo $_REQUEST['observaciones'];} ?></textarea></td>
								</tr>
								<?
									if($comentario_anulacion){

										echo "<tr>
													<td valign='top'>Comentario Anulacion:</td>
													<td><textarea rows='2' style='width:230px;color:red' disabled>$comentario_anulacion</textarea></td>
												</tr>
												<tr>
													<td valign='top'>Usuario Anulacion:</td>
													<td><input type='text' style='width:100px;color:red;' value='$usuario_anulacion' disabled>&nbsp;<input type='text' style='width:120px;color:red;' value='".date('d-m-Y H:i',strtotime($fecha_anulacion))."' disabled></td>
												</tr>";
									}

								?>

							</table>
						</fieldset>
					</td>
					<td valign='top'>
						<fieldset>
							<legend>Documentacion</legend>
							<table>
								<tr>
									<td>Numero Boleta:</td>
									<td><input type='text' name='numero_boleta' value='<? if($_REQUEST['numero_boleta']){ echo $_REQUEST['numero_boleta'];}else{echo $numero_boleta;}?>' style='width:200px' onBlur='submit();'></td>
								</tr>
								<tr>
									<td>Boleta de Honorario:</td>
									<td><input type='text' name='boleta_honorario' value='<? if($_REQUEST['boleta_honorario']){ echo $_REQUEST['boleta_honorario'];}else{echo $boleta_honorario;}?>' style='width:200px' onBlur='submit();'></td>
								</tr>

								<tr>
									<td>Numero Factura:</td>
									<td><input type='text' name='numero_factura' value='<? if($_REQUEST['numero_factura']){ echo $_REQUEST['numero_factura'];}else{echo $numero_factura;}?>' style='width:200px' onBlur='submit();'></td>
								</tr>
								<tr>
									<td>Fecha Factura:</td>
									<td><input type='date' name='fecha_factura' value='<? if($_REQUEST['fecha_factura']){ echo $_REQUEST['fecha_factura'];}else{ echo $fecha_factura;}?>' style='width:200px' onBlur='submit();'></td>
								</tr>
								<tr>
									<td>Factura Pagada:</td>
									<td align='left'><input type='checkbox' name='factura_pagada' value='SI' <? if($_REQUEST['factura_pagada']){ echo "checked";}else{ if($factura_pagada){ echo "checked";}}?> ></td>
								</tr>
								<tr>
									<td>Comentario Factura:</td>
									<td><input type='text' name='comentario_factura' value='<? if($_REQUEST['comentario_factura']){echo $_REQUEST['comentario_factura'];}else{echo $comentario_factura;}?>' style='width:200px' onBlur='submit();'></td>
								</tr>
								<tr>
									<td>Despachar A:</td>
									<td><input type='text' name='despachar_a' value='<? if($_REQUEST['despachar_a']){echo $_REQUEST['despachar_a'];}else{echo $despachar_a;}?>' style='width:200px' onBlur='submit();'></td>
								</tr>
								<tr>
									<td>OT :</td>
									<td><input type='text' name='ot' value='<? if($_REQUEST['ot']){echo $_REQUEST['ot'];}else{echo $ot;}?>' style='width:172px' onBlur='submit();'>
										<input type='button' name='' value='...' onClick='document.location.href=`generarOT.php?numero_interno=<? echo $ot;?>`;' <? if($_REQUEST['ot']=='' and $ot==''){ echo "disabled";}?>>
									</td>
								</tr>
								<tr>
									<td>Patente:</td>
									<td><input type='text' disabled value='<? if($patente){echo $patente;}else{echo $_REQUEST['patente'];}?>' style='width:200px' ></td>
								</tr>
								<tr>
									<td>Tipo Gasto *:</td>
									<td>
										<select name='tipo_gasto' onBlur='submit();' required>
											<option value=''>Seleccione...</option>
											<?
												$sql		=	"select * from oc_tipo_gasto where id in (select id_tipo_gasto from usuario_tipo_gasto where id_usuario='".$_SESSION['id_usuario']."')order by id desc";
												$result 	=	mysqli_query($mysql,$sql);
												while($row 	=	mysqli_fetch_array($result)){
													if($_REQUEST['tipo_gasto']==$row['tipo_gasto'] or $tipo_gasto==$row['tipo_gasto']){
														echo "<option value='".$row['tipo_gasto']."' selected>".$row['tipo_gasto']."</option>";
													}else{
															if($row['tipo_gasto'] == $tipo_gasto){
																echo "<option value='".$row['tipo_gasto']."' selected>".$row['tipo_gasto']."</option>";
															}else{
																echo "<option value='".$row['tipo_gasto']."'>".$row['tipo_gasto']."</option>";
															}
														}
													}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td></td>
									<td><input type='submit' name='actualizar_factura' value='Actualizar'></td>
								</tr>
							</table>
						</fieldset>
					</td>
				</tr>
			</table>
			<br>
			<table border='1' rules='all' width='100%'>
				<tr>
					<td bgcolor='#F2F2F2' width='30%'>Repuesto *</td>
					<td bgcolor='#F2F2F2' width='2%'>Codigo Repuesto</td>
					<? if($tipo_movimiento=='TRASPASO') { echo "<td bgcolor='#F2F2F2' width='6%'>Bodega Origen *</td>"; } ?>
					<? if($tipo_movimiento=='TRASPASO') { echo "<td bgcolor='#F2F2F2' width='6%'>Stock Disponible Bodega Origen</td>"; } ?>
					<td bgcolor='#F2F2F2' width='10%'>Bodega Destino *</td>
					<td bgcolor='#F2F2F2' width='6%'>Stock Disponible Bodega Destino</td>
					<td bgcolor='#F2F2F2' width='6%' align='center'>Cantidad Solicitada *</td>
					<td bgcolor='#F2F2F2' width='6%' align='center'>Ultimo Costo</td>
					<td bgcolor='#F2F2F2' width='6%' align='center'>Costo Unitario</td>
					<td bgcolor='#F2F2F2' align='center'>Total Neto</td>
					<td bgcolor='#F2F2F2' align='center'>Total IVA</td>
					<td bgcolor='#F2F2F2' align='center'>Total</td>
					<td bgcolor='#F2F2F2' align='center'>Actualizar</td>
					<?
						if($modifica_oc_estado_aprobado==1)
						{
							echo "<td bgcolor='#F2F2F2' align='center'>Editar Precio</td>";
						}
					?>
					<td bgcolor='#F2F2F2' align='center'>Eliminar</td>
				</tr>
				<?

					   if($numero_interno==''){

							$sql="select
										ocd.*,
										(select nombre from bodega where id_bodega=ocd.id_bodega_destino) nombre_bodega_destino,
										(select cantidad from existencia where id_bodega=ocd.id_bodega_destino and id_articulo=ocd.id_articulo)stock_disponible_bodega_destino
										from orden_compra_detalle ocd
										where ocd.numero_interno='".$_SESSION['id_usuario']."'";


					   }else{

							 $sql="select
										ocd.*,
										(select nombre from bodega where id_bodega=ocd.id_bodega_destino) nombre_bodega_destino,
										(select cantidad from existencia where id_bodega=ocd.id_bodega_destino and id_articulo=ocd.id_articulo)stock_disponible_bodega_destino
										from orden_compra_detalle ocd
										where ocd.numero_interno='$numero_interno'";
					   }



					$result = mysqli_query($mysql,$sql);

					while($row = mysqli_fetch_array($result))
					{

						if($row['id_articulo']<>'')
						{
							$sql							=	"select costo ultimo_costo,numero_interno numero_interno_ultimo_costo from orden_compra_detalle where id_articulo='".$row['id_articulo']."' and numero_interno<'$numero_interno' order by numero_interno desc limit 0,1;";
							$result2						=	mysqli_query($mysql,$sql);
							$array2							=	mysqli_fetch_array($result2);
							$ultimo_costo					=	$array2['ultimo_costo'];
							$numero_interno_ultimo_costo	=	$array2['numero_interno_ultimo_costo'];
						}


						echo "<tr>
								<td>".$row['descripcion_articulo']."</td>
								<td>".$row['id_articulo']."</td>
								<td>".$row['nombre_bodega_destino']."</td>
								<td align='center'>".number_format($row['stock_disponible_bodega_destino'])."</td>
								<td align='center'><input type='number' name='".$numero_interno."_".$row['id_articulo']."' value='".number_format($row['cantidad'],0,'','')."' max='".number_format($row['cantidad'],0,'','')."' style='width:50px'></td>
								<td align='right'><a href='ordenCompra.php?numero_interno=".$numero_interno_ultimo_costo."'>$".number_format($ultimo_costo,0,'','.')."</a></td>
								<td align='right'>$".number_format($row['costo'],0,'','.')."</td>
								<td align='right'>$".number_format($row['costo']*$row['cantidad'],0,'','.')."</td>
								<td align='right'>$".number_format(($row['iva']),0,'','.')."</td>
								<td align='right'>$".number_format($row['total'],0,'','.')."</td>
								<td align='center'><input type='submit' name='actualizar_item' value='Actualizar'></td>";

					if($modifica_oc_estado_aprobado==1)
					{
						echo "<td align='center'><input type='button' name='editar_precio' value='Editar' onClick='modificarItem(".$row['id_detalle'].",".$_REQUEST['numero_interno'].");'></td>";
					}

						echo 	"<td align='center'><input type='button' name='eliminar_item' value='Eliminar Item' OnClick='eliminarItem(`".$row['numero_interno']."`,".$row['id_detalle'].",`".$row['id_articulo']."`,`".$row['descripcion_articulo']."`)' $disabled></td>
							  </tr>";
					}
				 ?>
			</table>
			<br>
			<input type='submit' name='guardar' id='guardar' value='Guardar y Enviar' <? echo $disabled;?> >
			&nbsp;
			<input type='button' name='agregar_item' onClick='agregarItem(`<? echo $numero_interno;?>`);' value='Agregar Item' <? echo $disabled;?>>
			&nbsp;
			<input type='button' name='anular_orden_compra' value='Anular Orden de Compra' onClick='anularOrdenCompra(<? echo $numero_interno;?>)' <? if($numero_interno==''){ echo "disabled";}?>>
			&nbsp;
			<?
				$sql		=	"select count(*) existe from guia where oc='$numero_interno' AND estado<>'NULA'";
				$result 	= 	mysqli_query($mysql,$sql);
				$array 		= 	mysqli_fetch_array($result);
				$existe 	= 	$array['existe'];
			?>
			<input type='button' name='generar_guia' value='Generar Guia' onClick='generarGuia(<? echo $numero_interno?>);' <? if($existe>0 OR $estado=='NULA' or $estado=='RECHAZADO' or $estado=='PENDIENTE APROBACION'){   echo "disabled";}?>>
			&nbsp;
			<input type='button' name='procesar_guia' value='Procesar Guia' onClick='document.location.href=`recepcionarGuia.php?numero_interno=<? echo $guia_despacho;?>`;' <? if($estado=='NULA' or $estado=='RECHAZADO' or $estado=='PENDIENTE APROBACION'){ echo "disabled";}?>>

	</div>

	<div id="tabs-2">
		<!--nota de credito-->
		<fieldset>
		<legend>Nota de Credito</legend>
			<table>
				<tr>
					<td>Numero Nota Credito:</td>
					<td><input type='number' name='numero_nota_credito' value='<? echo $numero_nota_credito?>' style='width:200px'></td>
				</tr>
				<tr>
					<td>Fecha Nota Credito:</td>
					<td><input type='date' name='fecha_nota_credito' value='<? echo $fecha_nota_credito;?>' style='width:200px'></td>
				</tr>
				<tr>
					<td>Monto Nota Credito:</td>
					<td><input type='number' name='monto_nota_credito' value='<? echo $monto_nota_credito?>' style='width:200px'></td>
				</tr>
			</table>
		</fieldset>

	</div>
	<div id="tabs-3">
	<fieldset>
		<legend>Adjuntos</legend>
		<table>
			<tr><td><input type='button' name='adjuntar_archivos' value='Adjuntar Archivos' onClick="adjuntarArchivo('<? echo $numero_interno;?>','oc');"></td></tr>
		</table>
		<? echo listarArchivosAdjuntosAplicacion('oc',$numero_interno); ?>
		</fieldset>
		<fieldset>
		<legend>MEMO</legend>
		<table>
			<tr>
				<td>Folio Memo:</td>
				<td><input type='text' name='folio_memo' value='<? echo $folio_memo;?>' style='width:200px'></td>
			</tr>
			<tr>
				<td>Fecha Memo:</td>
				<td><input type='date' name='fecha_memo' value='<? echo $fecha_memo;?>' style='width:200px'></td>
			</tr>
		</table>
		</fieldset>
		 </form>
	</div>
</div>
<?

if($_REQUEST['guardar'])
{
	if($numero_interno==0)
	{
		echo "<table width='100%'><tr><td bgcolor='red'><font color='white'>Se ha producido un error al generar el registro!</font></td></tr></table>";
	}
	else
	{
		echo "<table width='100%'><tr><td bgcolor='green'><font color='white'>Se han guardado los datos exitosamente</font></td></tr></table>";
	}
}


include 'footer.php';

?>