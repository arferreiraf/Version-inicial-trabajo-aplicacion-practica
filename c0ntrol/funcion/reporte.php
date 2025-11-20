<?

function listarUsuarios($buscar_usuario)
{
	include '../include/conexionSistema.php';

	$resultado="";

	$c="";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorUsuario.php')
	{
		$resultado.="<table width='10%'>
						<tr>
							<td><a href='agregarUsuario.php'><img src='../images/agregar.png' title='Agregar'></a></td>
							<td><a href='descargarListadoUsuarios.php?buscar_usuario=".$_REQUEST['buscar_usuario']."'><img src='../images/descargar.png' title='Descargar'></a></td>
							<td><a href='#' onClick='print();'><img src='../images/imprimir.png' title='Imprimir'></a></td>
							<td><a href='mantenedorUsuario.php'><img src='../images/recargar.png' title='Actualizar'></a></td>
						</tr>
						</table>";
	}

    $sql="select * from usuario where id_usuario like '%$buscar_usuario%' or nombre like '%$buscar_usuario%' or correo like '%$buscar_usuario%' order by id_usuario asc";

	$result = mysqli_query($mysql,$sql);

	$resultado.= "<table width='100%'>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorUsuario.php')
	{
		$resultado.="<tr>
						<td></td>
		                <td><form method='post' action='mantenedorUsuario.php'><input type='text' name='buscar_usuario' value='' placeholder='Buscar...'></form></td>
					 </tr>";
	}

	$resultado.="<tr>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorUsuario.php'){$resultado.="<td bgcolor='#EFF2FB'></td>";}

	$resultado.="<td bgcolor='#EFF2FB'>ID</td>
				 <td bgcolor='#EFF2FB'>NOMBRE</td>
				 <td bgcolor='#EFF2FB'>CORREO</td>
				 <td bgcolor='#EFF2FB'>ESTADO</td>
				 <td bgcolor='#EFF2FB'>ULTIMO INGRESO</td>
				 </tr>";

	while($row = mysqli_fetch_array($result))
	{
		if($row['ultimo_ingreso']<>'0000-00-00 00:00:00')
		{
			$ultimo_ingreso = date('d-m-Y H:i',strtotime($row['ultimo_ingreso']));
		}
		else
		{
			$ultimo_ingreso= "";
		}

		$resultado.= "<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>";
		//strncasecmp
		//strcasecmp
		if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorUsuario.php')
		{$resultado.="<td align='center'><form method='post' action='fichaUsuario.php'><input type='hidden' name='id_usuario' value='".$row['id_usuario']."'><input type='image' src='../images/editar.png' title='Editar' name='editar' value='editar'></form></td>";}
	$resultado.= "<td>".strtoupper($row['id_usuario'])."</td>
					  <td>".strtoupper($row['nombre'])."</td>
					  <td>".strtoupper($row['correo'])."</td>
					  <td>".$row['estado']."</td>
					  <td>".$ultimo_ingreso."</td>
					</tr>";
		$c++;
	}

	$resultado.="</table><br><br>";
	$resultado.="Cantidad de Usuarios:$c";

	return $resultado;
}



function listarArticulo($buscar_articulo)
{
	include '../include/conexionSistema.php';

	$resultado="";

	$c="";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorArticulo.php')
	{
		$resultado.="<table width='10%'>
						<tr>
							<td><a href='agregarArticulo.php'><img src='../images/agregar.png' title='Agregar'></a></td>
							<td><a href='descargarListadoArticulos.php?buscar_articulo=".$_REQUEST['buscar_articulo']."'><img src='../images/descargar.png' title='Descargar'></a></td>
							<td><a href='#' onClick='print();'><img src='../images/imprimir.png' title='Imprimir'></a></td>
							<td><a href='mantenedorArticulo.php'><img src='../images/recargar.png' title='Actualizar'></a></td>
						</tr>
						</table>";
	}

      $sql="select a.*,
			(select count(*) from articulo_codigo_barra where id_articulo = a.id_articulo) cantidad_codigos_barra_configurados
			from articulo a
			where a.id_articulo like '%$buscar_articulo%'
			or a.descripcion like '%$buscar_articulo%'
			or a.estado like '%$buscar_articulo%'
			or a.id_articulo in (select id_articulo from articulo_codigo_barra where codigo_barra like '%".$buscar_articulo."%')
			or a.unidad_medida like '%$buscar_articulo%'
			order by a.descripcion asc";

	$result = mysqli_query($mysql,$sql);

	$resultado.= "<table width='100%'>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorArticulo.php')
	{
		$resultado.="<tr>
						<td></td>
		                <td><form method='post' action='mantenedorArticulo.php'><input type='text' name='buscar_articulo' value='$buscar_articulo' placeholder='Buscar...'></form></td>
					 </tr>";
	}

	$resultado.="<tr>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorArticulo.php'){$resultado.="<td bgcolor='#EFF2FB'></td>";}

	$resultado.="<td bgcolor='#EFF2FB'>CODIGO REPUESTO</td>
				 <td bgcolor='#EFF2FB'>DESCRIPCION</td>
				 <td bgcolor='#EFF2FB'>UNIDAD DE MEDIDA</td>
				 <td bgcolor='#EFF2FB'>ESTADO</td>
				 <td bgcolor='#EFF2FB'>COSTO PROMEDIO</td>
				 <td bgcolor='#EFF2FB'>STOCK CRITICO</td>
				 <td bgcolor='#EFF2FB'>CODIGOS BARRA CONFIGURADOS</td>
				 <td bgcolor='#EFF2FB'>CUENTA CONTABLE</td>
				 <td bgcolor='#EFF2FB'>FAMILIA</td>
				 <td bgcolor='#EFF2FB'>TIPO</td>

				</tr>";

	while($row = mysqli_fetch_array($result))
	{

		$resultado.= "<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>";
		if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorArticulo.php'){$resultado.="<td align='center'><form method='post' action='fichaArticulo.php'><input type='hidden' name='id_articulo' value='".$row['id_articulo']."'><input type='image' src='../images/editar.png' title='Editar' name='editar' value='editar'></form></td>";}
	$resultado.= "<td>".strtoupper($row['id_articulo'])."</td>
					  <td>".strtoupper($row['descripcion'])."</td>
					  <td>".strtoupper($row['unidad_medida'])."</td>
					  <td>".strtoupper($row['estado'])."</td>";
		if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorArticulo.php'){$resultado.="<td align='right'>$".number_format($row['costo_promedio'],2,',','.')."</td>";}else{$resultado.="<td align='right'>".number_format($row['costo_promedio'],2,',','.')."</td>";}
		$resultado.="<td align='center'>".$row['stock_critico']."</td>
					 <td align='center'>".$row['cantidad_codigos_barra_configurados']."</td>
					  <td>".strtoupper($row['cuenta_contable'])."</td>
					  <td>".strtoupper($row['familia'])."</td>
					  <td>".strtoupper($row['tipo'])."</td>

					</tr>";
		$c++;
	}

	$resultado.="</table><br><br>";
	$resultado.="Cantidad de Articulos:$c";

	return $resultado;
}



function listarVehiculo($buscar_vehiculo)
{
	include '../include/conexionSistema.php';

	$resultado	="";
	$c			="";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorVehiculo.php')
	{
		$resultado.="<table width='10%'>
						<tr>
							<td><a href='agregarVehiculo.php'><img src='../images/agregar.png' title='Agregar'></a></td>
							<td><a href='descargarListadoVehiculos.php?buscar_vehiculo=$buscar_vehiculo'><img src='../images/descargar.png' title='Descargar'></a></td>
							<td><a href='#' onClick='print();'><img src='../images/imprimir.png' title='Imprimir'></a></td>
							<td><a href='#' onClick='importar();'><img src='../images/importar.png' title='Importar'></a></td>
							<td><a href='mantenedorVehiculo.php'><img src='../images/recargar.png' title='Actualizar'></a></td>

						</tr>
						</table>";
	}


    $sql		="select v.*,
					(select nombre 			 from bodega 			where id_bodega 	= v.id_bodega) nombre_bodega,
					(select nombre_operacion from operacion 		where id_operacion	= v.id_operacion) nombre_operacion,
					(select marca  			 from vehiculo_marca 	where id_marca  	= v.id_marca ) marca,
					(select modelo 			 from vehiculo_modelo 	where id_modelo 	= v.id_modelo) modelo,
					(select tipo   			 from vehiculo_tipo 	where id_tipo   	= v.id_tipo  ) tipo,
					(select nombre_empresa 	 from empresa 			where rut_empresa	= v.rut_empresa)nombre_empresa
					from vehiculo v
					where v.patente like '%$buscar_vehiculo%'
					or v.estado 	like '%$buscar_vehiculo%'
					or v.vin 		like '%$buscar_vehiculo%'
					or v.ano 		like '%$buscar_vehiculo%'
					or v.rendimiento_teorico like '%$buscar_vehiculo%'
					or v.kilometraje_actual  like '%$buscar_vehiculo%'
					or v.propietario  like '%$buscar_vehiculo%'
					or v.fecha_ultima_mantencion like '%".date('Y-m-d',strtotime($buscar_vehiculo))."%'
					or v.id_marca    in (select id_marca  from vehiculo_marca  where marca  like '%$buscar_vehiculo%')
					or v.id_modelo   in (select id_modelo from vehiculo_modelo where modelo like '%$buscar_vehiculo%')
					or v.id_tipo     in (select id_tipo   from vehiculo_tipo   where tipo   like '%$buscar_vehiculo%')
					or v.id_bodega   in (select id_bodega from bodega where nombre like '%$buscar_vehiculo%')
					order by v.patente asc";

	$result 	= mysqli_query($mysql,$sql);
	$resultado .= "<table width='100%'>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorVehiculo.php')
	{
		$resultado.="<tr>
						<td></td>
		                <td><form method='post' action='mantenedorVehiculo.php'><input type='text' name='buscar_vehiculo' value='$buscar_vehiculo' placeholder='Buscar...'></form></td>
					 </tr>";
	}

	$resultado.="<tr>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorVehiculo.php'){$resultado.="<td bgcolor='#EFF2FB' ALIGN='CENTER'>EDITAR</td>";}

	$resultado.="<td bgcolor='#EFF2FB'>PATENTE</td>
				 <td bgcolor='#EFF2FB'>MARCA</td>
				 <td bgcolor='#EFF2FB'>MODELO</td>
				 <td bgcolor='#EFF2FB'>TIPO</td>
				 <td bgcolor='#EFF2FB'>VIN</td>
				 <td bgcolor='#EFF2FB'>BODEGA</td>
				 <td bgcolor='#EFF2FB'>OPERACION</td>
				 <td bgcolor='#EFF2FB'>AÑO</td>
				 <td bgcolor='#EFF2FB'>RENDIMIENTO TEORICO</td>
				 <td bgcolor='#EFF2FB'>K.M ACTUAL</td>
				 <td bgcolor='#EFF2FB'>K.M PROX. MANTENCION</td>
				 <td bgcolor='#EFF2FB'>K.M RESTANTE PROX. MANTENCION</td>
				 <td bgcolor='#EFF2FB'>ULTIMA MANTENCION</td>
				 <td bgcolor='#EFF2FB'>ESTADO</td>
				 <td bgcolor='#EFF2FB'>NUMERO EJES</td>
				 <td bgcolor='#EFF2FB'>CAPACIDAD CARGA</td>
				 <td bgcolor='#EFF2FB'>COMPARTIMIENTOS</td>
				 <td bgcolor='#EFF2FB'>TIPO CARGA</td>
				 <td bgcolor='#EFF2FB'>PROPIETARIO</td>
				 <td bgcolor='#EFF2FB'>RUT EMPRESA</td>
				 <td bgcolor='#EFF2FB'>EMPRESA</td>

				</tr>";

	while($row = mysqli_fetch_array($result))
	{

		$kilometraje_restante_proxima_mantencion=number_format($row['kilometraje_proxima_mantencion']-$row['kilometraje_actual'],0,'','.');

		if($kilometraje_restante_proxima_mantencion<=0){
			$kilometraje_restante_proxima_mantencion="<font color='red'><b>CITAR</b></font>";
		}

		if($row['fecha_ultima_mantencion']<>'0000-00-00'){$fecha_ultima_mantencion=date('d-m-Y',strtotime($row['fecha_ultima_mantencion']));}else{$fecha_ultima_mantencion="";}
		$resultado.= "<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>";
		if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorVehiculo.php'){$resultado.="<td align='center'><form method='post' action='fichaVehiculo.php'><input type='hidden' name='patente' value='".$row['patente']."'><input type='image' src='../images/editar.png' title='Editar' name='editar' value='editar'></form></td>";}
	$resultado.= "<td>".strtoupper($row['patente'])."</td>
					  <td>".strtoupper($row['marca'])."</td>
					  <td>".strtoupper($row['modelo'])."</td>
					  <td>".strtoupper($row['tipo'])."</td>
					  <td>".strtoupper($row['vin'])."</td>
					  <td>".strtoupper($row['nombre_bodega'])."</td>
					  <td>".strtoupper($row['nombre_operacion'])."</td>
					  <td>".strtoupper($row['ano'])."</td>
					  <td align='center'>".number_format($row['rendimiento_teorico'],2,',','.')."</td>
					  <td align='right'>".number_format($row['kilometraje_actual'],0,'','.')."</td>
					  <td align='right'>".number_format($row['kilometraje_proxima_mantencion'],0,'','.')."</td>
					  <td align='right'>".$kilometraje_restante_proxima_mantencion."</td>
					  <td align='center'>".$fecha_ultima_mantencion."</td>
					  <td>".strtoupper($row['estado'])."</td>
					  <td>".strtoupper($row['numero_ejes'])."</td>
					  <td>".strtoupper($row['capacidad_carga'])."</td>
					  <td>".strtoupper($row['compartimientos'])."</td>
					  <td>".strtoupper($row['tipo_carga'])."</td>
					  <td>".strtoupper($row['propietario'])."</td>
					  <td>".strtoupper($row['rut_empresa'])."</td>
					  <td>".strtoupper($row['nombre_empresa'])."</td>

					</tr>";
		$c++;
	}

	$resultado.="</table><br><br>";
	$resultado.="Cantidad de Vehiculos:$c";

	return $resultado;
}





function listarBodegas($buscar_bodega)
{
	include '../include/conexionSistema.php';

	$resultado="";

	$c="";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorBodega.php')
	{
		$resultado.="<table width='10%'>
							<tr>
								<td><a href='agregarBodega.php'><img src='../images/agregar.png' title='Agregar'></a></td>
								<td><a href='descargarListadoBodega.php?buscar_bodega=".$_REQUEST['buscar_bodega']."'><img src='../images/descargar.png' title='Descargar'></a></td>
								<td><a href='#' onClick='print();'><img src='../images/imprimir.png' title='Imprimir'></a></td>
								<td><a href='mantenedorBodega.php'><img src='../images/recargar.png' title='Actualizar'></a></td>
							</tr>
						</table>";
	}

    $sql="select * from bodega where id_bodega like '%$buscar_bodega%' or nombre like '%$buscar_bodega%' or direccion like '%$buscar_bodega%' or estado like '%$buscar_bodega%' or usuario_a_cargo like '%$buscar_bodega%' order by orden asc";

	$result = mysqli_query($mysql,$sql);

	$resultado.= "<table width='100%'>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorBodega.php')
	{
		$resultado.="<tr>
						<td></td>
		                <td><form method='post' action='mantenedorBodega.php'><input type='text' name='buscar_bodega' value='$buscar_bodega' placeholder='Buscar...'></form></td>
					 </tr>";
	}

	$resultado.="<tr>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorBodega.php'){$resultado.="<td bgcolor='#EFF2FB' align='center'>EDITAR</td>";}

	$resultado.="<td bgcolor='#EFF2FB'>ID BODEGA</td>
				 <td bgcolor='#EFF2FB'>NOMBRE</td>
				 <td bgcolor='#EFF2FB'>DIRECCION</td>
				 <td bgcolor='#EFF2FB'>TELEFONO</td>
				 <td bgcolor='#EFF2FB'>USUARIO A CARGO</td>
				 <td bgcolor='#EFF2FB'>CORREO USUARIO A CARGO</td>
				 <td bgcolor='#EFF2FB'>REPARACION</td>
				 <td bgcolor='#EFF2FB'>ESTADO</td>
				 <td bgcolor='#EFF2FB'>ORDEN</td>
				</tr>";

	while($row = mysqli_fetch_array($result))
	{

		$resultado.= "<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>";
		if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorBodega.php'){$resultado.="<td align='center'><form method='post' action='fichaBodega.php'><input type='hidden' name='id_bodega' value='".strtoupper($row['id_bodega'])."'><input type='image' src='../images/editar.png' title='Editar' name='editar' value='editar'></form></td>";}
		$resultado.= "<td>".strtoupper($row['id_bodega'])."</td>
					  <td>".strtoupper($row['nombre'])."</td>
					  <td>".strtoupper($row['direccion'])."</td>
					  <td>".strtoupper($row['telefono'])."</td>
					  <td>".strtoupper($row['usuario_a_cargo'])."</td>
					  <td>".strtoupper($row['correo_usuario_a_cargo'])."</td>
					  <td align='center'>".strtoupper($row['reparacion'])."</td>
					  <td align='center'>".strtoupper($row['estado'])."</td>
					  <td align='center'>".strtoupper($row['orden'])."</td>
					</tr>";
		//acumulador de bodegas activas e inactivas
		if($row['estado']=='ACT'){$act++;}
		if($row['estado']=='INA'){$ina++;}
	}

	$resultado.="</table><br><br>";

	//entrega el resumen de bodegas activas e inactivas
	$resultado.="<table>
					<tr>
						<td>Bodegas Activas:</td>  <td align='right'>".number_format($act,0,'','')."</td>
					</tr>
					<tr>
						<td>Bodegas Inactivas:</td><td align='right'>".number_format($ina,0,'','')."</td>
					</tr>
					<tr>
						<td>Bodegas Totales:</td>  <td align='right'>".number_format(($act+$ina),0,'','')."</td>
					</tr>
				  </table>";

	return $resultado;
}



function listarEmpresas($buscar_empresa)
{
	include '../include/conexionSistema.php';

	$resultado	="";
	$c			="";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorEmpresa.php')
	{
		$resultado.="<table width='10%'>
							<tr>
								<td><a href='agregarEmpresa.php'><img src='../images/agregar.png' title='Agregar'></a></td>
								<td><a href='descargarListadoEmpresa.php?buscar_empresa=".$_REQUEST['buscar_empresa']."'><img src='../images/descargar.png' title='Descargar'></a></td>
								<td><a href='#' onClick='print();'><img src='../images/imprimir.png' title='Imprimir'></a></td>
								<td><a href='mantenedorEmpresa.php'><img src='../images/recargar.png' title='Actualizar'></a></td>
							</tr>
						</table>";
	}

    $sql		="select * from empresa where rut_empresa like '%$buscar_empresa%' or nombre_empresa like '%$buscar_empresa%' or direccion_empresa like '%$buscar_empresa%' or estado like '%$buscar_empresa%' order by nombre_empresa asc";
	$result 	= mysqli_query($mysql,$sql);

	$resultado .="<table width='100%'>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorEmpresa.php')
	{
		$resultado.="<tr>
						<td></td>
		                <td><form method='post' action='mantenedorEmpresa.php'><input type='text' name='buscar_empresa' value='$buscar_empresa' placeholder='Buscar...'></form></td>
					 </tr>";
	}

	$resultado.="<tr>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorEmpresa.php'){$resultado.="<td bgcolor='#EFF2FB' align='center'>EDITAR</td>";}

	$resultado.="<td bgcolor='#EFF2FB'>RUT EMPRESA</td>
				 <td bgcolor='#EFF2FB'>NOMBRE EMPRESA</td>
				 <td bgcolor='#EFF2FB'>DIRECCION</td>
				 <td bgcolor='#EFF2FB'>CIUDAD</td>
				 <td bgcolor='#EFF2FB'>TELEFONO</td>
				 <td bgcolor='#EFF2FB'>FAX</td>
				 <td bgcolor='#EFF2FB'>ESTADO</td>
				</tr>";

	while($row = mysqli_fetch_array($result))
	{

		$resultado.= "<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>";
		if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorEmpresa.php'){$resultado.="<td align='center'><form method='post' action='fichaEmpresa.php'><input type='hidden' name='rut_empresa' value='".strtoupper($row['rut_empresa'])."'><input type='image' src='../images/editar.png' title='Editar' name='editar' value='editar'></form></td>";}
		$resultado.= "<td>".strtoupper($row['rut_empresa'])."</td>
					  <td>".strtoupper($row['nombre_empresa'])."</td>
					  <td>".strtoupper($row['direccion_empresa'])."</td>
					  <td>".strtoupper($row['ciudad_empresa'])."</td>
					  <td>".strtoupper($row['telefono'])."</td>
					  <td>".strtoupper($row['fax'])."</td>
					  <td>".strtoupper($row['estado'])."</td>
					</tr>";
		//acumulador de bodegas activas e inactivas
		if($row['estado']=='ACT'){$act++;}
		if($row['estado']=='INA'){$ina++;}
	}

	$resultado.="</table><br><br>";

	//entrega el resumen de registros activas e inactivas
	$resultado.="<table>
					<tr>
						<td>Empresas Activas:</td>  <td align='right'>".number_format($act,0,'','')."</td>
					</tr>
					<tr>
						<td>Empresas Inactivas:</td><td align='right'>".number_format($ina,0,'','')."</td>
					</tr>
					<tr>
						<td>Empresas Totales:</td>  <td align='right'>".number_format(($act+$ina),0,'','')."</td>
					</tr>
				  </table>";

	return $resultado;
}



function stockBodega($nombre_bodega,$id_articulo,$descripcion_articulo)
{
	include '../include/conexionSistema.php';

	$resultado	="";
	$c			="";

	if($nombre_bodega<>''){ $filtro_nombre_bodega="and e.id_bodega in (select id_bodega from bodega where nombre like '%$nombre_bodega%')";}

	$resultado.="<table width='100%' >
					<tr>
						<td bgcolor='#EFF2FB'>ID BODEGA</td>
						<td bgcolor='#EFF2FB'>BODEGA</td>
						<td bgcolor='#EFF2FB'>ID ARTICULO</td>
						<td bgcolor='#EFF2FB'>DESCRIPCION</td>
						<td bgcolor='#EFF2FB'>CANTIDAD</td>
						<td bgcolor='#EFF2FB'>STOCK CRITICO</td>
					</tr>";

	 $sql="select e.*,
			(select descripcion from articulo where id_articulo=e.id_articulo) descripcion,
			(select stock_critico from articulo where id_articulo=e.id_articulo) stock_critico,
			(select nombre from bodega where id_bodega=e.id_bodega) nombre_bodega
			from existencia e
			where e.id_articulo like '%$id_articulo%'
			and e.descripcion 	like '%$descripcion_articulo%'
			$filtro_nombre_bodega
			order by e.id_bodega asc";

	$result = mysqli_query($mysql,$sql);

	while($row = mysqli_fetch_array($result))
	{
		if($row['cantidad']<=$row['stock_critico']){
			$bgcolor='#F5A9BC';
		}else{
			$bgcolor='';
		}

			$resultado.="<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>
						<td>".$row['id_bodega']."</td>
						<td>".$row['nombre_bodega']."</td>
						<td>".$row['id_articulo']."</td>
						<td>".strtoupper($row['descripcion'])."</td>
						<td bgcolor='$bgcolor' align='right'><a href='reporteKardex.php?id_articulo=".$row['id_articulo']."&fecha_inicio=2000-01-01&fecha_termino=2050-01-01&buscar=s'>".$row['cantidad']."</td>
						<td align='center'>".$row['stock_critico']."</td>
					</tr>";

	}

	$resultado.="</table>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/stockBodega.php'){
$resultado.="<br><input type='button' name='descargar' value='Descargar' onClick='document.location.href=`descargarStockBodega.php?nombre_bodega=$nombre_bodega&id_articulo=$id_articulo&descripcion_articulo=$descripcion_articulo&bajo_stock=$bajo_stock`;'>";
	}

	return $resultado;
}

function listadoCodigosBarra($id_articulo)
{
	include '../include/conexionSistema.php';

	$resultado="";

	$sql="select codigo_barra,id_articulo from articulo_codigo_barra where id_articulo='$id_articulo'";

	$result = mysqli_query($mysql,$sql);

	$resultado.="<table width='100%'>";

	while($row = mysqli_fetch_array($result))
	{
		$resultado.="<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>
						<td>".$row['codigo_barra']."</td>
						<td><img src='../images/eliminar.png' title='Eliminar Registro' width='15' height='15' onClick='eliminarRegistro(`".$row['id_articulo']."`,`".$row['codigo_barra']."`);'></td>
					</tr>";
	}

	$resultado.="</table>";

	return $resultado;
}

function listarUnidadesMedida($descripcion)
{
	include '../include/conexionSistema.php';

	$resultado="";

	$c="";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorUnidadesMedida.php')
	{
		$resultado.="<table width='10%'>
						<tr>
							<td><a href='agregarUnidadMedida.php'><img src='../images/agregar.png' title='Agregar'></a></td>
							<td><a href='descargarListadoUnidadesMedida.php?descricion=".$_REQUEST['descripcion']."'><img src='../images/descargar.png' title='Descargar'></a></td>
							<td><a href='#' onClick='print();'><img src='../images/imprimir.png' title='Imprimir'></a></td>
							<td><a href='mantenedorUnidadesMedida.php'><img src='../images/recargar.png' title='Actualizar'></a></td>
						</tr>
						</table>";
	}

    $sql="select * from unidad_medida where descripcion like '%$descripcion%' order by descripcion asc";

	$result = mysqli_query($mysql,$sql);

	$resultado.= "<table width='100%'>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorUnidadesMedida.php')
	{
		$resultado.="<tr>
						<td></td>
		                <td><form method='post' action='mantenedorUnidadesMedida.php'><input type='text' name='buscar_unidad_medida' value='$descripcion' placeholder='Buscar...'></form></td>
					 </tr>";
	}

	$resultado.="<tr>";
	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorUnidadesMedida.php'){$resultado.="<td bgcolor='#EFF2FB' ALIGN='CENTER'>EDITAR</td>";}
	$resultado.="<td bgcolor='#EFF2FB'>ID UNIDAD DE MEDIDA</td>
				 <td bgcolor='#EFF2FB'>DESCRIPCION</td>
				 <td bgcolor='#EFF2FB'>USUARIO CREACION</td>
				 <td bgcolor='#EFF2FB'>FECHA CREACION</td>
				 <td bgcolor='#EFF2FB'>USUARIO MODIFICA</td>
				 <td bgcolor='#EFF2FB'>FECHA MODIFICA</td>
				</tr>";

	while($row = mysqli_fetch_array($result))
	{

		if($row['fecha_creacion']<>'0000-00-00 00:00:00'){ $fecha_creacion = date('d-m-Y H:i',strtotime($row['fecha_creacion']));}
		if($row['fecha_modifica']<>'0000-00-00 00:00:00'){ $fecha_modifica = date('d-m-Y H:i',strtotime($row['fecha_modifica']));}


		$resultado.= "<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>";
		if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorUnidadesMedida.php'){$resultado.="<td align='center'><form method='post' action='fichaUnidadesMedida.php'><input type='hidden' name='id_unidad_medida' value='".$row['id_unidad_medida']."'><input type='image' src='../images/editar.png' title='Editar' name='editar' value='editar'></form></td>";}
	$resultado.= "<td>".strtoupper($row['id_unidad_medida'])."</td>
					  <td>".strtoupper($row['descripcion'])."</td>
					  <td>".strtoupper($row['usuario_creacion'])."</td>
					  <td>".$fecha_creacion."</td>
					  <td>".strtoupper($row['usuario_modifica'])."</td>
					  <td>".$fecha_modifica."</td>
					</tr>";
		$c++;
	}

	$resultado.="</table><br><br>";
	$resultado.="Cantidad de Unidades Medida:$c";

	return $resultado;

}


function listarCondicionesPago($descripcion)
{
	include '../include/conexionSistema.php';

	$resultado	="";
	$c			="";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorCondicionesPago.php')
	{
		$resultado.="<table width='10%'>
						<tr>
							<td><a href='agregarCondicionPago.php'><img src='../images/agregar.png' title='Agregar'></a></td>
							<td><a href='descargarCondicionesPago.php?descricion=".$_REQUEST['descripcion']."'><img src='../images/descargar.png' title='Descargar'></a></td>
							<td><a href='#' onClick='print();'><img src='../images/imprimir.png' title='Imprimir'></a></td>
							<td><a href='mantenedorCondicionesPago.php'><img src='../images/recargar.png' title='Actualizar'></a></td>
						</tr>
						</table>";
	}

    $sql			 =	"select * from condicion_pago where descripcion like '%$descripcion%' order by descripcion asc";
	$result 		 =	mysqli_query($mysql,$sql);
	$resultado	 	.=  "<table width='70%'>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorCondicionesPago.php')
	{
		$resultado.="<tr>
						<td></td>
		                <td>
							<form method='post' action='mantenedorCondicionesPago.php'>
							<input type='text' name='descripcion' value='$descripcion' placeholder='Buscar...'>
							</form>
						</td>
					 </tr>";
	}

	$resultado.="<tr>";
	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorCondicionesPago.php'){$resultado.="<td bgcolor='#EFF2FB' ALIGN='CENTER'>EDITAR</td>";}
	$resultado.="<td bgcolor='#EFF2FB'>ID CONDICION PAGO</td>
				 <td bgcolor='#EFF2FB'>DESCRIPCION</td>
				 <td bgcolor='#EFF2FB'>ESTADO</td>
				</tr>";

	while($row = mysqli_fetch_array($result))
	{
		$resultado.= "<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>";
		if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorCondicionesPago.php'){$resultado.="<td align='center'><form method='post' action='fichaCondicionPago.php'><input type='hidden' name='id_condicion_pago' value='".$row['id_condicion_pago']."'><input type='image' src='../images/editar.png' title='Editar' name='editar' value='editar'></form></td>";}
	$resultado.= "<td>".strtoupper($row['id_condicion_pago'])."</td>
					  <td>".strtoupper($row['descripcion'])."</td>
					  <td>".strtoupper($row['estado'])."</td>
					</tr>";
		$c++;
	}

	$resultado.="</table><br><br>";
	$resultado.="Cantidad de Unidades Medida:$c";

	return $resultado;

}



function listarEmpleados($buscar_empleado)
{

	include '../include/conexionSistema.php';

	$resultado="";

	$c="";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorEmpleados.php')
	{
		$resultado.="<table width='10%'>
						<tr>
							<td><a href='agregarEmpleado.php'><img src='../images/agregar.png' title='Agregar'></a></td>
							<td><a href='descargarListadoEmpleados.php?buscar_usuario=".$_REQUEST['buscar_usuario']."'><img src='../images/descargar.png' title='Descargar'></a></td>
							<td><a href='#' onClick='print();'><img src='../images/imprimir.png' title='Imprimir'></a></td>
							<td><a href='mantenedorEmpleados.php'><img src='../images/recargar.png' title='Actualizar'></a></td>
						</tr>
						</table>";
	}

    $sql="select * from empleado where rut like '%$buscar_empleado%' or nombre like '%$buscar_empleado%' or estado like '%$buscar_empleado%' or cargo like '%$buscar_empleado%' order by nombre asc";

	$result = mysqli_query($mysql,$sql);

	$resultado.= "<table width='100%'>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorEmpleados.php')
	{
		$resultado.="<tr>
						<td></td>
		                <td><form method='post' action='mantenedorEmpleados.php'><input type='text' name='buscar_empleado' value='$buscar_empleado' placeholder='Buscar...'></form></td>
					 </tr>";
	}

	$resultado.="<tr>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorEmpleados.php'){$resultado.="<td bgcolor='#EFF2FB' ALIGN='CENTER'>EDITAR</td>";}

	$resultado.="<td bgcolor='#EFF2FB'>RUT</td>
				 <td bgcolor='#EFF2FB'>NOMBRE</td>
				 <td bgcolor='#EFF2FB'>CARGO</td>
				 <td bgcolor='#EFF2FB'>ESTADO</td>
				 </tr>";

	while($row = mysqli_fetch_array($result))
	{
		$resultado.= "<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>";
		if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorEmpleados.php'){$resultado.="<td align='center'><form method='post' action='fichaEmpleado.php'><input type='hidden' name='rut' value='".$row['rut']."'><input type='image' src='../images/editar.png' title='Editar' name='editar' value='editar'></form></td>";}
		$resultado.="<td>".strtoupper($row['rut'])."</td>";
		$resultado.="<td>".strtoupper($row['nombre'])."</td>";
		$resultado.="<td>".strtoupper($row['cargo'])."</td>";
		$resultado.="<td>".$row['estado']."</td>";
		$resultado.="</tr>";
		$c++;
	}

	$resultado.="</table><br><br>";
	$resultado.="Cantidad de Empleados:$c";

	return $resultado;


}




function listarTiposVehiculo($buscar_tipo_vehiculo)
{
	include '../include/conexionSistema.php';

	$resultado="";

	$c="";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorTipoVehiculo.php')
	{
		$resultado.="<table width='10%'>
						<tr>
							<td><a href='agregarTipoVehiculo.php'><img src='../images/agregar.png' title='Agregar'></a></td>
							<td><a href='descargarListadoTipoVehiculo.php?buscar_tipo_vehiculo=".$_REQUEST['buscar_tipo_vehiculo']."'><img src='../images/descargar.png' title='Descargar'></a></td>
							<td><a href='#' onClick='print();'><img src='../images/imprimir.png' title='Imprimir'></a></td>
							<td><a href='mantenedorTipoVehiculo.php'><img src='../images/recargar.png' title='Actualizar'></a></td>
						</tr>
						</table>";
	}

    $sql="select * from vehiculo_tipo where tipo like '%$buscar_tipo_vehiculo%' order by tipo asc";

	$result = mysqli_query($mysql,$sql);

	$resultado.= "<table width='100%'>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorTipoVehiculo.php')
	{
		$resultado.="<tr>
						<td></td>
		                <td><form method='post' action='mantenedorTipoVehiculo.php'><input type='text' name='buscar_tipo_vehiculo' value='$buscar_tipo_vehiculo' placeholder='Buscar...'></form></td>
					 </tr>";
	}

	$resultado.="<tr>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorTipoVehiculo.php'){$resultado.="<td bgcolor='#EFF2FB' ALIGN='CENTER'>EDITAR</td>";}

	$resultado.="<td bgcolor='#EFF2FB'>CODIGO</td>
				 <td bgcolor='#EFF2FB'>DESCRIPCION</td>
				 <td bgcolor='#EFF2FB' align='center'>ESTADO</td>
				</tr>";

	while($row = mysqli_fetch_array($result))
	{

		$resultado.= "<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>";
		if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorTipoVehiculo.php'){$resultado.="<td align='center'><form method='post' action='fichaTipoVehiculo.php'><input type='hidden' name='id_tipo' value='".$row['id_tipo']."'><input type='image' src='../images/editar.png' title='Editar' name='editar' value='editar'></form></td>";}
	$resultado.= "<td>".strtoupper($row['id_tipo'])."</td>
					  <td>".strtoupper($row['tipo'])."</td>
					  <td align='center'>".$row['estado']."</td>
					</tr>";
		$c++;
	}

	$resultado.="</table><br><br>";
	$resultado.="Cantidad de Tipos de Vehiculos:$c";

	return $resultado;
}




function listarTipoGasto($buscar_tipo_gasto)
{
	include '../include/conexionSistema.php';

	$resultado="";

	$c="";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorTipoGasto.php')
	{
		$resultado.="<table width='10%'>
						<tr>
							<td><a href='agregarTipoGasto.php'><img src='../images/agregar.png' title='Agregar'></a></td>
							<td><a href='descargarListadoTipoGasto.php?buscar_tipo_gasto=".$_REQUEST['buscar_tipo_gasto']."'><img src='../images/descargar.png' title='Descargar'></a></td>
							<td><a href='#' onClick='print();'><img src='../images/imprimir.png' title='Imprimir'></a></td>
							<td><a href='mantenedorTipoGasto.php'><img src='../images/recargar.png' title='Actualizar'></a></td>
						</tr>
						</table>";
	}

    $sql="select * from oc_tipo_gasto where tipo_gasto like '%$buscar_tipo_gasto%' order by tipo_gasto asc";

	$result = mysqli_query($mysql,$sql);

	$resultado.= "<table width='100%'>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorTipoGasto.php')
	{
		$resultado.="<tr>
						<td></td>
		                <td><form method='post' action='mantenedorTipoGasto.php'><input type='text' name='buscar_tipo_gasto' value='$mantenedorTipoGasto' placeholder='Buscar...'></form></td>
					 </tr>";
	}

	$resultado.="<tr>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorTipoGasto.php'){$resultado.="<td bgcolor='#EFF2FB' ALIGN='CENTER'>EDITAR</td>";}

	$resultado.="<td bgcolor='#EFF2FB'>CODIGO</td>
				 <td bgcolor='#EFF2FB'>DESCRIPCION</td>
				</tr>";

	while($row = mysqli_fetch_array($result))
	{

		$resultado.= "<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>";
		if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorTipoGasto.php'){$resultado.="<td align='center'><form method='post' action='fichaTipoGasto.php'><input type='hidden' name='id' value='".$row['id']."'><input type='image' src='../images/editar.png' title='Editar' name='editar' value='editar'></form></td>";}
	$resultado.= "<td>".strtoupper($row['id'])."</td>
					  <td>".strtoupper($row['tipo_gasto'])."</td>
					</tr>";
		$c++;
	}

	$resultado.="</table><br><br>";
	$resultado.="Cantidad de Registros:$c";

	return $resultado;
}



function listarTiposMovimientos($buscar_tipo_movimiento)
{
	include '../include/conexionSistema.php';

	$resultado="";

	$c="";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorTipoMovimiento.php')
	{
		$resultado.="<table width='10%'>
						<tr>
							<td><a href='agregarTipoMovimiento.php'><img src='../images/agregar.png' title='Agregar'></a></td>
							<td><a href='descargarListadoTipoMovimiento.php?buscar_tipo_movimiento=".$_REQUEST['buscar_tipo_movimiento']."'><img src='../images/descargar.png' title='Descargar'></a></td>
							<td><a href='#' onClick='print();'><img src='../images/imprimir.png' title='Imprimir'></a></td>
							<td><a href='mantenedorTipoMovimiento.php'><img src='../images/recargar.png' title='Actualizar'></a></td>
						</tr>
						</table>";
	}

      $sql="select *
			from tipo_movimiento
			where codigo_movimiento like '%$buscar_tipo_movimiento%'
			or descripcion like '%$buscar_tipo_movimiento%'
			or estado like '%$buscar_tipo_movimiento%'
			or tipo like '%$buscar_tipo_movimiento%'
			or referencia like '%$buscar_tipo_movimiento%'
			order by codigo_movimiento asc";

	$result = mysqli_query($mysql,$sql);

	$resultado.= "<table width='100%'>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorTipoMovimiento.php')
	{
		$resultado.="<tr>
						<td></td>
		                <td><form method='post' action='mantenedorTipoMovimiento.php'><input type='text' name='buscar_tipo_movimiento' value='$buscar_tipo_movimiento' placeholder='Buscar...'></form></td>
					 </tr>";
	}

	$resultado.="<tr>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorTipoMovimiento.php'){$resultado.="<td bgcolor='#EFF2FB' ALIGN='CENTER'>EDITAR</td>";}

	$resultado.="<td bgcolor='#EFF2FB'>CODIGO</td>
				 <td bgcolor='#EFF2FB'>DESCRIPCION</td>
				 <td bgcolor='#EFF2FB'>TIPO</td>
				 <td bgcolor='#EFF2FB'>REFERENCIA</td>
				 <td bgcolor='#EFF2FB'>ESTADO</td>
				 <td bgcolor='#EFF2FB'>VALOR</td>
				</tr>";

	while($row = mysqli_fetch_array($result))
	{

		$resultado.= "<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>";
		if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorTipoMovimiento.php'){$resultado.="<td align='center'><form method='post' action='fichaTipoMovimiento.php'><input type='hidden' name='codigo_movimiento' value='".$row['codigo_movimiento']."'><input type='image' src='../images/editar.png' title='Editar' name='editar' value='editar'></form></td>";}
	$resultado.= "<td>".strtoupper($row['codigo_movimiento'])."</td>
					  <td>".strtoupper($row['descripcion'])."</td>
					  <td>".strtoupper($row['tipo'])."</td>
					  <td>".strtoupper($row['referencia'])."</td>
					  <td align='center'>".$row['estado']."</td>
					  <td align='center'>".$row['valor']."</td>
					</tr>";
		$c++;
	}

	$resultado.="</table><br><br>";
	$resultado.="Cantidad de Tipos de Movimientos:$c";

	return $resultado;
}



function listarRecepcionesPendientes($codigo_movimiento,$oc)
{
	include '../include/conexionSistema.php';

	$resultado="";

	$c="";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/listarRecepciones.php')
	{
		$resultado.="<table width='10%'>
						<tr>
							<td><a href='descargarRecepcionesPendientes.php?codigo_movimiento=$codigo_movimiento'><img src='../images/descargar.png' title='Descargar'></a></td>
							<td><a href='#' onClick='print();'><img src='../images/imprimir.png' title='Imprimir'></a></td>
							<td><a href='listarRecepciones.php'><img src='../images/recargar.png' title='Actualizar'></a></td>
						</tr>
						</table>";
	}

	if($oc){$filtro_oc="and g.oc = '$oc'";}else{$filtro_oc="";}

      $sql	=	"select distinct g.numero_interno,
							(select oc from guia where numero_interno = g.numero_interno) oc,
							(select rut_proveedor from guia where numero_interno = g.numero_interno) rut_proveedor,
							(select nombre_proveedor from guia where numero_interno = g.numero_interno) nombre_proveedor,
							(select descripcion_movimiento from guia where numero_interno = g.numero_interno) descripcion_movimiento,
							(select fecha_ingreso from guia where numero_interno = g.numero_interno) fecha_ingreso,
							(select razon_emision from guia where numero_interno = g.numero_interno) razon_emision,
							(select numero_boleta from guia where numero_interno = g.numero_interno) numero_boleta,
							(select numero_factura from guia where numero_interno = g.numero_interno) numero_factura,
							(select neto from guia where numero_interno = g.numero_interno) neto,
							(select iva from guia where numero_interno = g.numero_interno) iva,
							(select total from guia where numero_interno = g.numero_interno) total,
							(select nombre_empresa from empresa where rut_empresa = g.rut_empresa) nombre_empresa
					from guia g , guia_detalle gd
					where g.estado in ('Pendiente','Aprobado')
					and g.numero_interno = gd.numero_interno
					and g.codigo_movimiento in (select codigo_movimiento from guia_tipo_movimiento)
					and g.codigo_movimiento like '%$codigo_movimiento%'
					$filtro_oc
					and gd.id_bodega_destino in (select id_bodega from usuario_bodega where id_usuario='".$_SESSION['id_usuario']."')
					order by g.fecha_ingreso desc";

	$result = mysqli_query($mysql,$sql);



	if($_SERVER['PHP_SELF']=='/suControl/sistema/listarRecepciones.php')
	{
		$resultado.="<form >
						<table><tr><td>Filtrar:</td>
		                <td>
							<select name='codigo_movimiento' style='width:200px'>
							<option value=''>Seleccione...</option>";

		$sql		=	"select * from tipo_movimiento where referencia='GUIA' order by codigo_movimiento desc;";
		$result2 	= 	mysqli_query($mysql,$sql);
		while($row2 	=	mysqli_fetch_array($result2)){
			if($row2['codigo_movimiento'] == $codigo_movimiento){
				$resultado.="<option value='".$row2['codigo_movimiento']."' selected>".$row2['descripcion']."</option>";
			}else{
				$resultado.="<option value='".$row2['codigo_movimiento']."'>".$row2['descripcion']."</option>";
			}
		}

		$resultado.="</select></td>
					 <td></td></tr>
					 <tr><td>OC:</td><td><input type='text' name='oc' value='$oc' style='width:200px'></td>
					     <td><input type='submit' name='filtrar' value='Filtrar'></td>
					 </tr></form>
					 </table>";
	}
	$resultado.= "<table width='100%'>";
	$resultado.="<form method='post' action='recepcionarGuia.php'>
				 <input type='hidden' name='tipo_documento' value='recepcion'>
				 <tr>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/listarRecepciones.php'){$resultado.="<td bgcolor='#EFF2FB' ALIGN='CENTER'>SELECCIONE</td>";}

	$resultado.="<td bgcolor='#EFF2FB'>NUMERO INTERNO</td>
				 <td bgcolor='#EFF2FB'>OC</td>
				 <td bgcolor='#EFF2FB'>RUT</td>
				 <td bgcolor='#EFF2FB'>PROVEEDOR</td>
				 <td bgcolor='#EFF2FB'>DESCRIPCION</td>
				 <td bgcolor='#EFF2FB'>FECHA INGRESO</td>
				 <td bgcolor='#EFF2FB'>RAZON EMISION</td>
				 <td bgcolor='#EFF2FB'>NUMERO BOLETA</td>
				 <td bgcolor='#EFF2FB'>FACTURA</td>
				 <td bgcolor='#EFF2FB'>EMPRESA</td>
				 <td bgcolor='#EFF2FB' align='center'>NETO</td>
				 <td bgcolor='#EFF2FB' align='center'>IVA</td>
				 <td bgcolor='#EFF2FB' align='center'>TOTAL</td>
				</tr>";

	while($row = mysqli_fetch_array($result))
	{
		$resultado.= "<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>";
		if($_SERVER['PHP_SELF']=='/suControl/sistema/listarRecepciones.php'){$resultado.="<td align='center'><input type='radio' name='numero_interno' value='".$row['numero_interno']."'></td>";}
	$resultado.= "<td>".strtoupper($row['numero_interno'])."</td>
					  <td>".$row['oc']."</td>
					  <td>".$row['rut_proveedor']."</td>
					  <td>".$row['nombre_proveedor']."</td>
					  <td>".strtoupper($row['descripcion_movimiento'])."</td>
					  <td>".date('d-m-Y',strtotime($row['fecha_ingreso']))."</td>
					  <td>".strtoupper($row['razon_emision'])."</td>
					  <td>".$row['numero_boleta']."</td>
					  <td>".$row['numero_factura']."</td>
					  <td>".strtoupper($row['nombre_empresa'])."</td>
					  <td align='right'>$ ".number_format($row['neto'],0,'','.')."</td>
					  <td align='right'>$ ".number_format($row['iva'],0,'','.')."</td>
					  <td align='right'>$ ".number_format($row['total'],0,'','.')."</td>
					</tr>";
		$c++;
	}

	$resultado.="</table><br><br>";
	if($_SERVER['PHP_SELF']=='/suControl/sistema/listarRecepciones.php'){$resultado.="<input type='submit' name='procesar' value='Procesar'>";}
	$resultado.="</form><br><br>";
	$resultado.="Cantidad de Recepciones:$c";

	return $resultado;
}


function detalleKardex($numero_interno,$referencia)
{

		include '../include/conexionSistema.php';

		if($_SERVER['PHP_SELF']=='/suControl/sistema/imprimirEntregaRepuestosOT.php'){
			$resultado.="<h3><u>REPUESTOS ENTREGADOS</u></h3>";
		}else{
			$resultado.="<h3><u>Detalle de Proceso</u></h3>";
		}

		$resultado.="<table width='100%' border='1' rules='all'>
						<tr>
							<td bgcolor='#F2F2F2' >Codigo</td>
							<td bgcolor='#F2F2F2' >Descripcion</td>
							<td bgcolor='#F2F2F2' >Cantidad</td>
							<td bgcolor='#F2F2F2' >Fecha</td>
							<td bgcolor='#F2F2F2' >Usuario</td>
						</tr>";

		$sql	=	"select * from kardex where numero_interno ='$numero_interno' and referencia='$referencia' order by fecha_movimiento desc";
		$result = 	mysqli_query($mysql,$sql);

		while($row = mysqli_fetch_array($result))
		{
			$resultado.="<tr>
							<td>".$row['id_articulo']."</td>
							<td>".$row['descripcion']."</td>
							<td align='center'>".$row['cantidad']."</td>
							<td>".date('d/m/Y H:i',strtotime($row['fecha_movimiento']))."</td>
							<td>".$row['usuario_movimiento']."</td>
						</tr>";
			$c++;

		}

		$resultado.="</table>";

		if($c>0)
		{
			return $resultado;
		}

}



function listarEntregasPendientes($buscar_entrega)
{
	include '../include/conexionSistema.php';

	$resultado="";

	$c="";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/listarEntregas.php')
	{
		$resultado.="<table width='10%'>
						<tr>
							<td><a href='descargarListadoEntregas.php?buscar_entrega=".$buscar_entrega."'><img src='../images/descargar.png' title='Descargar'></a></td>
							<td><a href='#' onClick='print();'><img src='../images/imprimir.png' title='Imprimir'></a></td>
							<td><a href='listarEntregas.php'><img src='../images/recargar.png' title='Actualizar'></a></td>
						</tr>
						</table>";
	}

    $sql		=	"select ot.numero_interno,ot.nombre_bodega,ot.patente,ot.supervisor,ot.estado,ot.neto,ot.fecha_solicitud_repuesto,ot.usuario_solicitud_repuesto,
						(select descripcion from tipo_movimiento where codigo_movimiento=ot.codigo_movimiento) descripcion_movimiento
						from orden_trabajo ot
						where ot.estado in ('SOLICITUD REPUESTO','PROCESO')
						order by ot.fecha_liberacion desc";
	$result 	= 	mysqli_query($mysql,$sql);
	$resultado .= "<table width='100%'>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/listarEntregas.php')
	{
		$resultado.="<table>
					 <tr>
						<td></td>
		                <td><form method='post' action='listarEntregas.php'><input type='text' name='buscar_entrega' value='' placeholder='Buscar...'></form></td>
					 </tr>
					 </table>";
	}

	$resultado.="<form method='post' action='entregarRepuestos.php'>
				<table width='100%'>
				<tr>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/listarEntregas.php'){$resultado.="<td bgcolor='#EFF2FB' ALIGN='CENTER'>SELECCIONE</td>";}

	$resultado.="<td bgcolor='#EFF2FB'>OT</td>
				 <td bgcolor='#EFF2FB'>FECHA SOLICITUD</td>
				 <td bgcolor='#EFF2FB'>USUARIO SOLICITANTE</td>
				 <td bgcolor='#EFF2FB'>MOVIMIENTO</td>
				 <td bgcolor='#EFF2FB'>PATENTE</td>
				 <td bgcolor='#EFF2FB'>SUPERVISOR</td>
				 <td bgcolor='#EFF2FB'>BODEGA</td>
				 <td bgcolor='#EFF2FB'>ESTADO</td>
				 <td bgcolor='#EFF2FB' align='center'>TOTAL</td>
				</tr>";

	while($row = mysqli_fetch_array($result))
	{
		$resultado.= "<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>";
		if($_SERVER['PHP_SELF']=='/suControl/sistema/listarEntregas.php'){$resultado.="<td align='center'><input type='radio' name='numero_interno' value='".$row['numero_interno']."'></td>";}
	$resultado.= "<td>".$row['numero_interno']."</td>
					  <td>".date('d-m-Y H:i',strtotime($row['fecha_solicitud_repuesto']))."</td>
					  <td>".strtoupper($row['usuario_solicitud_repuesto'])."</td>
					  <td>".$row['descripcion_movimiento']."</td>
					  <td>".strtoupper($row['patente'])."</td>
					  <td>".strtoupper($row['supervisor'])."</td>
					  <td>".$row['nombre_bodega']."</td>
					  <td><font color='red'>".$row['estado']."</font></td>
					  <td align='right'>$ ".number_format($row['neto'],0,'','.')."</td>
					</tr>";
		$c++;
	}

	$resultado.="</table><br><br>";
	$resultado.="<input type='submit' name='entregar' value='Entregar Repuestos'>";
	$resultado.="</form><br><br>";
	$resultado.="Cantidad de Recepciones:$c";

	return $resultado;
}



function db_tractosEnTallerSemanal($ano,$mes){

	include '../include/conexionSistema.php';

	$posicion_semana = 	date('w');
	$dia 			 = 	date('d');
	$nombre_dia		 = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');



	$resultado.="<h3>Tractos en Taller Semanal</h3>";
	$resultado.="<table width='100%' rules='all' border='1'>
					<tr>
						<td>Base</td>
						<td align='center'>Total Tractos</td>
						<td align='center'>Total Rigidos</td>
						<td align='center'>Total</td>";

	//entrega las columnas de la tabla
	for($i=0;$i<=6;$i++){
		//imprime cada columna



		$resultado.="<td align='center'>".$nombre_dia[$i]."</td>";
	}


	//trae el resultado del listado de bodegas
	$sql	=	"select b.id_bodega,b.nombre,
					(select count(patente) from vehiculo where id_bodega=b.id_bodega and patente in (select patente from vehiculo where id_tipo in (select id_tipo from vehiculo_tipo where tipo='TRACTO')))total_tractos,
					(select count(patente) from vehiculo where id_bodega=b.id_bodega and patente in (select patente from vehiculo where id_tipo in (select id_tipo from vehiculo_tipo where tipo='RIGIDO')))total_rigidos
					from bodega b
					where b.reparacion='si'
					order by b.orden asc;";
	$result = 	mysqli_query($mysql,$sql);
	while($row 	= mysqli_fetch_array($result)){
		$resultado			 .="<tr>
							    <td>".$row['nombre']."</td>
								<td align='center'>".$row['total_tractos']."</td>
								<td align='center'>".$row['total_rigidos']."</td>
								<td align='center'>".($row['total_tractos'] + $row['total_rigidos'])."</td>";


		//recorre la semana
		for($i=0;$i<=6;$i++){
		$sql		=	"SELECT date_add(now(), INTERVAL ".($i - ($posicion_semana -1))." DAY) fecha";
		$result2	= 	mysqli_query($mysql,$sql);
		$array2		= 	mysqli_fetch_array($result2);
		$fecha		= 	date('y-m-d',strtotime($array2['fecha']));

		$sql		=	"select count(patente)cantidad from orden_trabajo where id_bodega='".$row['id_bodega']."' and  '$fecha' between fecha_entrada_taller and fecha_salida_taller";
		$result2 	= 	mysqli_query($mysql,$sql);
		$array2		=	mysqli_fetch_array($result2);
		$cantidad	= 	$array2['cantidad'];

		//asigna un color para los que estan en mantencion
		if($cantidad>0){$bgcolor="bgcolor='#F5A9A9'";}else{$bgcolor="";}
		$resultado .=   "<td align='center' $bgcolor>".$cantidad."</td>";

		$vehiculo_dia_taller[$i] += $cantidad;

		}

		$resultado			.="</tr>";
	}

	$resultado.="</tr>";
	$resultado.="</table>";



	return $resultado;
}


function db_OCOTPendientes(){
	include '../include/conexionSistema.php';

	$resultado.="<h3>OC/OT Pendientes</h3>";
	$resultado.="<table width='100%' rules='all' border='1'>
					<tr>
						<td>Bodega</td>
						<td align='center'>OC Pendiente Aprobacion</td>
						<td align='center'>OT Pendiente</td>
						<td align='center'>OT Solicitud Repuesto</td>
						<td align='center'>OT Solicitud Proceso</td>
					</tr>";

	   $sql		=	"select
						b.id_bodega,
						b.nombre,
						(select count(distinct oc.numero_interno) from orden_compra oc, orden_compra_detalle ocd where ocd.id_bodega_destino=b.id_bodega and ocd.numero_interno = oc.numero_interno and oc.estado='PENDIENTE APROBACION') ORDEN_COMPRA_PENDIENTE,
						(select count(*) from orden_trabajo where estado='PENDIENTE' and id_bodega=b.id_bodega) ORDEN_TRABAJO_PENDIENTE,
						(select count(*) from orden_trabajo where estado='SOLICITUD REPUESTO' and id_bodega=b.id_bodega) ORDEN_TRABAJO_SOLICITUD_REPUESTO,
						(select count(*) from orden_trabajo where estado='PROCESO' and id_bodega=b.id_bodega) ORDEN_TRABAJO_PROCESO

						from bodega b
						where b.estado='ACT'
						and b.reparacion='si'
						order by b.orden asc";
	$result 	= 	mysqli_query($mysql,$sql);
	while($row 	= 	mysqli_fetch_array($result)){
		$resultado.="<tr>
						<td>".$row['nombre']."</td>
						<td align='center'><a href='reporteOC.php?estado=PENDIENTE&id_bodega_destino=".$row['id_bodega']."&buscar=buscar&fecha_inicio=2000-01-01&fecha_termino=2050-01-01'>".$row['ORDEN_COMPRA_PENDIENTE']."</a></td>
						<td align='center'><a href='reporteOT.php?estado=PENDIENTE&id_bodega=".$row['id_bodega']."&fecha_inicio=2000-01-01&fecha_termino=2050-01-01&buscar=buscar'>".$row['ORDEN_TRABAJO_PENDIENTE']."</a></td>
						<td align='center'><a href='reporteOT.php?estado=SOLICITUD REPUESTO&id_bodega=".$row['id_bodega']."&fecha_inicio=2000-01-01&fecha_termino=2050-01-01&buscar=buscar'>".$row['ORDEN_TRABAJO_SOLICITUD_REPUESTO']."</a></td>
						<td align='center'><a href='reporteOT.php?estado=PROCESO&id_bodega=".$row['id_bodega']."&fecha_inicio=2000-01-01&fecha_termino=2050-01-01&buscar=buscar'>".$row['ORDEN_TRABAJO_PROCESO']."</a></td>
					 </tr>";
	}

	$resultado.="</table>";

	return $resultado;
}

function db_tractosEnTaller($ano,$mes){

	include '../include/conexionSistema.php';

	$sql			 = 	"select last_day(now()) ultimo_dia;";
	$result 		 = 	mysqli_query($mysql,$sql);
	$array 			 = 	mysqli_fetch_array($result);
	$ultimo_dia		 =  $array['ultimo_dia'];

	$resultado		.=	"<h3>Tractos en Taller Mensual $mes/$ano</h3>";
	$resultado		.=	"<table border='1' rules='all' width='100%'>
							<tr>
								<td>Base</td>
								<td>Total Tractos</td>
								<td>Total Rigidos</td>";

	//recorre todo el mes para entregar el calendario
	for($i=1;$i<=date('d',strtotime($ultimo_dia));$i++){

		$sql		 =	"SELECT DATE_FORMAT('$ano-$mes-$i', '%W') nombre_dia";
		$result		 =  mysqli_query($mysql,$sql);
		$array		 = 	mysqli_fetch_array($result);
		$nombre_dia	 = 	$array['nombre_dia'];

		if($nombre_dia=='Monday'){$sigla_dia="L"; $bgcolor="";}
		if($nombre_dia=='Tuesday'){$sigla_dia="M"; $bgcolor="";}
		if($nombre_dia=='Wednesday'){$sigla_dia="X"; $bgcolor="";}
		if($nombre_dia=='Thursday'){$sigla_dia="J"; $bgcolor="";}
		if($nombre_dia=='Friday'){$sigla_dia="V"; $bgcolor="";}
		if($nombre_dia=='Saturday'){$sigla_dia="S"; $bgcolor="";}
		if($nombre_dia=='Sunday'){$sigla_dia="D"; $bgcolor="bgcolor='red'";}


		if($nombre_dia=='Sunday'){
			$resultado  .="<td align='center' $bgcolor>$sigla_dia <br> $i</td>";
		}else{
			$resultado  .="<td align='center'>$sigla_dia <br> $i</td>";
		}

	}

	$resultado		.="</tr>";

	$sql	=	"select b.id_bodega,b.nombre,
					(select count(patente) from vehiculo where id_bodega=b.id_bodega and patente in (select patente from vehiculo where id_tipo in (select id_tipo from vehiculo_tipo where tipo='TRACTO')))total_tractos,
					(select count(patente) from vehiculo where id_bodega=b.id_bodega and patente in (select patente from vehiculo where id_tipo in (select id_tipo from vehiculo_tipo where tipo='RIGIDO')))total_rigidos
					from bodega b
					order by b.orden asc
					;";
	$result = 	mysqli_query($mysql,$sql);
	while($row 	= mysqli_fetch_array($result)){
		$resultado			 .="<tr>
							    <td>".$row['nombre']."</td>
								<td align='center'>".$row['total_tractos']."</td>
								<td align='center'>".$row['total_rigidos']."</td>";

		for($i=1;$i<=date('d',strtotime($ultimo_dia));$i++){

		$sql		=	"select count(patente)cantidad from orden_trabajo where id_bodega='".$row['id_bodega']."' and  '$ano-$mes-$i' between fecha_entrada_taller and fecha_salida_taller";
		$result2 	= 	mysqli_query($mysql,$sql);
		$array2		=	mysqli_fetch_array($result2);
		$cantidad	= 	$array2['cantidad'];

		if($cantidad>0){$bgcolor="bgcolor='#F5A9A9'";}else{$bgcolor="";}

		$resultado .=   "<td align='center' $bgcolor>".$cantidad."</td>";

		$vehiculo_dia_taller[$i] += $cantidad;

		}

		$resultado			 .="</tr>";
		$total_tractos +=$row['total_tractos'];
		$total_rigidos +=$row['total_rigidos'];
	}

	$resultado.="<tr>
					<td>Total en Taller:</td>
					<td align='center'>$total_tractos</td>
					<td align='center'>$total_rigidos</td>";

	for($i=1;$i<=date('d',strtotime($ultimo_dia));$i++){
		$resultado .=   "<td align='center'>".$vehiculo_dia_taller[$i]."</td>";
	}

	$resultado.="</tr>";

	$total_flota	= ($total_tractos + $total_rigidos);

	$resultado.="<tr>
					<td>Total Flota:</td>
					<td align='center'>".$total_flota."</td>
					<td></td>";

	for($i=1;$i<=date('d',strtotime($ultimo_dia));$i++){

		if($total_flota ==0 or $vehiculo_dia_taller[$i]==0 or $total_flota==null or $vehiculo_dia_taller[$i]==null){
		$resultado .=   "<td align='center'>0</td>";

		}else{
		$resultado .=   "<td align='center'>".number_format((($vehiculo_dia_taller[$i]/$total_flota)*100),0,'','')."%</td>";
		}
	}
	$resultado.="</tr>";


	$resultado.="<tr>
					<td><font color='blue'><b>Disponibilidad Flota:</b></font></td>
					<td align='center'></td>
					<td></td>";

	for($i=1;$i<=date('d',strtotime($ultimo_dia));$i++){


		$resultado .=   "<td align='center'><font color='blue'>".number_format(((1-($vehiculo_dia_taller[$i]/$total_flota))*100),0,'','')."%</font></td>";

	}
	$resultado.="</tr>";

	$resultado.="</table>";

	return $resultado;

}



function db_gastosOCMensualNeto($ano)
{

	include '../include/conexionSistema.php';

	$resultado="";

	$sql="select id_bodega,nombre from bodega where estado='ACT' order by orden asc";

	$result = mysqli_query($mysql,$sql);
	$resultado.="<h3>Gastos por OC por Base Aprobadas</H3>";
	$resultado.="<table width='100%' rules='all' border='1' style='font-size:11px;font-family:Helvetica, Arial, sans-serif;'>
					<tr>
						<td bgcolor='#F2F2F2'>BODEGA</td>
						<td bgcolor='#F2F2F2' align='center'>ENERO</td>
						<td bgcolor='#F2F2F2' align='center'>FEBRERO</td>
						<td bgcolor='#F2F2F2' align='center'>MARZO</td>
						<td bgcolor='#F2F2F2' align='center'>ABRIL</td>
						<td bgcolor='#F2F2F2' align='center'>MAYO</td>
						<td bgcolor='#F2F2F2' align='center'>JUNIO</td>
						<td bgcolor='#F2F2F2' align='center'>JULIO</td>
						<td bgcolor='#F2F2F2' align='center'>AGOSTO</td>
						<td bgcolor='#F2F2F2' align='center'>SEPTIEMBRE</td>
						<td bgcolor='#F2F2F2' align='center'>OCTUBRE</td>
						<td bgcolor='#F2F2F2' align='center'>NOVIEMBRE</td>
						<td bgcolor='#F2F2F2' align='center'>DICIEMBRE</td>
						<td bgcolor='#F2F2F2' align='center'>TOTAL</td>
						<td bgcolor='#F2F2F2' align='center'>PPTO</td>
					</tr>";
	while($row = mysqli_fetch_array($result))
	{
		  $sql="select
				(select sum(costo*cantidad) from orden_compra_detalle where id_bodega_destino='".$row['id_bodega']."' and numero_interno in ( select numero_interno from orden_compra where numero_interno in (select numero_interno from workflow_documento where referencia='orden_compra' and estado='APROBADO' AND fecha_respuesta like '%$ano-01%') AND ESTADO IN ('APROBADO'))) ENERO,
				(select sum(costo*cantidad) from orden_compra_detalle where id_bodega_destino='".$row['id_bodega']."' and numero_interno in ( select numero_interno from orden_compra where numero_interno in (select numero_interno from workflow_documento where referencia='orden_compra' and estado='APROBADO' AND fecha_respuesta like '%$ano-02%') AND ESTADO IN ('APROBADO'))) FEBRERO,
				(select sum(costo*cantidad) from orden_compra_detalle where id_bodega_destino='".$row['id_bodega']."' and numero_interno in ( select numero_interno from orden_compra where numero_interno in (select numero_interno from workflow_documento where referencia='orden_compra' and estado='APROBADO' AND fecha_respuesta like '%$ano-03%') AND ESTADO IN ('APROBADO'))) MARZO,
				(select sum(costo*cantidad) from orden_compra_detalle where id_bodega_destino='".$row['id_bodega']."' and numero_interno in ( select numero_interno from orden_compra where numero_interno in (select numero_interno from workflow_documento where referencia='orden_compra' and estado='APROBADO' AND fecha_respuesta like '%$ano-04%') AND ESTADO IN ('APROBADO'))) ABRIL,
				(select sum(costo*cantidad) from orden_compra_detalle where id_bodega_destino='".$row['id_bodega']."' and numero_interno in ( select numero_interno from orden_compra where numero_interno in (select numero_interno from workflow_documento where referencia='orden_compra' and estado='APROBADO' AND fecha_respuesta like '%$ano-05%') AND ESTADO IN ('APROBADO'))) MAYO,
				(select sum(costo*cantidad) from orden_compra_detalle where id_bodega_destino='".$row['id_bodega']."' and numero_interno in ( select numero_interno from orden_compra where numero_interno in (select numero_interno from workflow_documento where referencia='orden_compra' and estado='APROBADO' AND fecha_respuesta like '%$ano-06%') AND ESTADO IN ('APROBADO'))) JUNIO,
				(select sum(costo*cantidad) from orden_compra_detalle where id_bodega_destino='".$row['id_bodega']."' and numero_interno in ( select numero_interno from orden_compra where numero_interno in (select numero_interno from workflow_documento where referencia='orden_compra' and estado='APROBADO' AND fecha_respuesta like '%$ano-07%') AND ESTADO IN ('APROBADO'))) JULIO,
				(select sum(costo*cantidad) from orden_compra_detalle where id_bodega_destino='".$row['id_bodega']."' and numero_interno in ( select numero_interno from orden_compra where numero_interno in (select numero_interno from workflow_documento where referencia='orden_compra' and estado='APROBADO' AND fecha_respuesta like '%$ano-08%') AND ESTADO IN ('APROBADO'))) AGOSTO,
				(select sum(costo*cantidad) from orden_compra_detalle where id_bodega_destino='".$row['id_bodega']."' and numero_interno in ( select numero_interno from orden_compra where numero_interno in (select numero_interno from workflow_documento where referencia='orden_compra' and estado='APROBADO' AND fecha_respuesta like '%$ano-09%') AND ESTADO IN ('APROBADO'))) SEPTIEMBRE,
				(select sum(costo*cantidad) from orden_compra_detalle where id_bodega_destino='".$row['id_bodega']."' and numero_interno in ( select numero_interno from orden_compra where numero_interno in (select numero_interno from workflow_documento where referencia='orden_compra' and estado='APROBADO' AND fecha_respuesta like '%$ano-10%') AND ESTADO IN ('APROBADO'))) OCTUBRE,
				(select sum(costo*cantidad) from orden_compra_detalle where id_bodega_destino='".$row['id_bodega']."' and numero_interno in ( select numero_interno from orden_compra where numero_interno in (select numero_interno from workflow_documento where referencia='orden_compra' and estado='APROBADO' AND fecha_respuesta like '%$ano-11%') AND ESTADO IN ('APROBADO'))) NOVIEMBRE,
				(select sum(costo*cantidad) from orden_compra_detalle where id_bodega_destino='".$row['id_bodega']."' and numero_interno in ( select numero_interno from orden_compra where numero_interno in (select numero_interno from workflow_documento where referencia='orden_compra' and estado='APROBADO' AND fecha_respuesta like '%$ano-12%') AND ESTADO IN ('APROBADO'))) DICIEMBRE,
				(select sum(costo*cantidad) from orden_compra_detalle where id_bodega_destino='".$row['id_bodega']."' and numero_interno in ( select numero_interno from orden_compra where numero_interno in (select numero_interno from workflow_documento where referencia='orden_compra' and estado='APROBADO' AND fecha_respuesta like '%$ano%') AND ESTADO IN ('APROBADO'))) TOTAL,
				(select sum(total_anual) from bodega_presupuesto where id_bodega='".$row['id_bodega']."' and ano='$ano') ppto";

		$result2 = mysqli_query($mysql,$sql);
		$array 	 = mysqli_fetch_array($result2);

		if($_SERVER['PHP_SELF']=='/suControl/sistema/principal.php')
		{
			$resultado.="<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>
							<td>".$row['nombre']."</td>
							<td align='right'>$".number_format($array['ENERO'],0,'','.')."</td>
							<td align='right'>$".number_format($array['FEBRERO'],0,'','.')."</td>
							<td align='right'>$".number_format($array['MARZO'],0,'','.')."</td>
							<td align='right'>$".number_format($array['ABRIL'],0,'','.')."</td>
							<td align='right'>$".number_format($array['MAYO'],0,'','.')."</td>
							<td align='right'>$".number_format($array['JUNIO'],0,'','.')."</td>
							<td align='right'>$".number_format($array['JULIO'],0,'','.')."</td>
							<td align='right'>$".number_format($array['AGOSTO'],0,'','.')."</td>
							<td align='right'>$".number_format($array['SEPTIEMBRE'],0,'','.')."</td>
							<td align='right'>$".number_format($array['OCTUBRE'],0,'','.')."</td>
							<td align='right'>$".number_format($array['NOVIEMBRE'],0,'','.')."</td>
							<td align='right'>$".number_format($array['DICIEMBRE'],0,'','.')."</td>
							<td align='right'><b>$".number_format($array['TOTAL'],0,'','.')."</b></td>
							<td align='right'><b>$".number_format($array['ppto'],0,'','.')."</td>
						</tr>";
		}
		else
		{
			$resultado.="<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>
							<td>".$row['nombre']."</td>
							<td align='right'>".$array['ENERO']."</a></td>
							<td align='right'>".$array['FEBRERO']."</a></td>
							<td align='right'>".$array['MARZO']."</a></td>
							<td align='right'>".$array['ABRIL']."</a></td>
							<td align='right'>".$array['MAYO']."</a></td>
							<td align='right'>".$array['JUNIO']."</a></td>
							<td align='right'>".$array['JULIO']."</a></td>
							<td align='right'>".$array['AGOSTO']."</a></td>
							<td align='right'>".$array['SEPTIEMBRE']."</a></td>
							<td align='right'>".$array['OCTUBRE']."</a></td>
							<td align='right'>".$array['NOVIEMBRE']."</a></td>
							<td align='right'>".$array['DICIEMBRE']."</a></td>
							<td align='right'><b>".$array['TOTAL']."</b></td>
							<td align='right'><b>".$array['ppto']."</b></td>
						</tr>";
		}


		$total_enero 		+= $array['ENERO'];
		$total_febrero 		+= $array['FEBRERO'];
		$total_marzo 		+= $array['MARZO'];
		$total_abril 		+= $array['ABRIL'];
		$total_mayo 		+= $array['MAYO'];
		$total_junio 		+= $array['JUNIO'];
		$total_julio 		+= $array['JULIO'];
		$total_agosto 		+= $array['AGOSTO'];
		$total_septiembre 	+= $array['SEPTIEMBRE'];
		$total_octubre 		+= $array['OCTUBRE'];
		$total_noviembre 	+= $array['NOVIEMBRE'];
		$total_diciembre 	+= $array['DICIEMBRE'];
		$total_anual 		+= $array['TOTAL'];
		$total_ppto  		+=	$array['ppto'];

	}
		$resultado.="<tr>
						<td bgcolor='#F6E3CE'>TOTAL:</td>
						<td bgcolor='#F6E3CE' align='right'><b>$ ".number_format($total_enero,0,'','.')."</a></b></td>
						<td bgcolor='#F6E3CE' align='right'><b>$ ".number_format($total_febrero,0,'','.')."</a></b></td>
						<td bgcolor='#F6E3CE' align='right'><b>$ ".number_format($total_marzo,0,'','.')."</a></b></td>
						<td bgcolor='#F6E3CE' align='right'><b>$ ".number_format($total_abril,0,'','.')."</a></b></td>
						<td bgcolor='#F6E3CE' align='right'><b>$ ".number_format($total_mayo,0,'','.')."</a></b></td>
						<td bgcolor='#F6E3CE' align='right'><b>$ ".number_format($total_junio,0,'','.')."</a></b></td>
						<td bgcolor='#F6E3CE' align='right'><b>$ ".number_format($total_julio,0,'','.')."</a></b></td>
						<td bgcolor='#F6E3CE' align='right'><b>$ ".number_format($total_agosto,0,'','.')."</a></b></td>
						<td bgcolor='#F6E3CE' align='right'><b>$ ".number_format($total_septiembre,0,'','.')."</a></b></td>
						<td bgcolor='#F6E3CE' align='right'><b>$ ".number_format($total_octubre,0,'','.')."</a></b></td>
						<td bgcolor='#F6E3CE' align='right'><b>$ ".number_format($total_noviembre,0,'','.')."</a></b></td>
						<td bgcolor='#F6E3CE' align='right'><b>$ ".number_format($total_diciembre,0,'','.')."</a></b></td>
						<td bgcolor='#F6E3CE' align='right'><b>$ ".number_format($total_anual,0,'','.')."</a></b></td>
						<td bgcolor='#F6E3CE' align='right'><b>$ ".number_format($total_ppto,0,'','.')."</b></td>
					</tr>
					<table>";

	return $resultado;

}


function db_gastosOtMensualNeto($ano)
{

	include '../include/conexionSistema.php';

	$resultado="";

	$sql="select id_bodega,nombre from bodega where estado='ACT' order by orden asc";

	$result = mysqli_query($mysql,$sql);
	$resultado.="<h3>Gastos por Mantención por Base</H3>";
	$resultado.="<table width='100%' rules='all' border='1' style='font-size:11px;font-family:Helvetica, Arial, sans-serif;'>
					<tr>
						<td bgcolor='#F2F2F2'>BODEGA</td>
						<td bgcolor='#F2F2F2' align='center'>ENERO</td>
						<td bgcolor='#F2F2F2' align='center'>FEBRERO</td>
						<td bgcolor='#F2F2F2' align='center'>MARZO</td>
						<td bgcolor='#F2F2F2' align='center'>ABRIL</td>
						<td bgcolor='#F2F2F2' align='center'>MAYO</td>
						<td bgcolor='#F2F2F2' align='center'>JUNIO</td>
						<td bgcolor='#F2F2F2' align='center'>JULIO</td>
						<td bgcolor='#F2F2F2' align='center'>AGOSTO</td>
						<td bgcolor='#F2F2F2' align='center'>SEPTIEMBRE</td>
						<td bgcolor='#F2F2F2' align='center'>OCTUBRE</td>
						<td bgcolor='#F2F2F2' align='center'>NOVIEMBRE</td>
						<td bgcolor='#F2F2F2' align='center'>DICIEMBRE</td>
						<td bgcolor='#F2F2F2' align='center'>TOTAL</td>
						<td bgcolor='#F2F2F2' align='center'>PPTO</td>
					</tr>";
	while($row = mysqli_fetch_array($result))
	{
		$sql="select
				(select sum(neto) from orden_trabajo where id_bodega='".$row['id_bodega']."' and fecha_solicitud like '$ano-01%' AND ESTADO not IN 	('NULA','RECHAZADA')) ENERO,
				(select sum(neto) from orden_trabajo where id_bodega='".$row['id_bodega']."' and fecha_solicitud like '$ano-02%' AND ESTADO not IN 	('NULA','RECHAZADA')) FEBRERO,
				(select sum(neto) from orden_trabajo where id_bodega='".$row['id_bodega']."' and fecha_solicitud like '$ano-03%' AND ESTADO not IN 	('NULA','RECHAZADA')) MARZO,
				(select sum(neto) from orden_trabajo where id_bodega='".$row['id_bodega']."' and fecha_solicitud like '$ano-04%' AND ESTADO not IN 	('NULA','RECHAZADA')) ABRIL,
				(select sum(neto) from orden_trabajo where id_bodega='".$row['id_bodega']."' and fecha_solicitud like '$ano-05%' AND ESTADO not IN 	('NULA','RECHAZADA')) MAYO,
				(select sum(neto) from orden_trabajo where id_bodega='".$row['id_bodega']."' and fecha_solicitud like '$ano-06%' AND ESTADO not IN 	('NULA','RECHAZADA')) JUNIO,
				(select sum(neto) from orden_trabajo where id_bodega='".$row['id_bodega']."' and fecha_solicitud like '$ano-07%' AND ESTADO not IN 	('NULA','RECHAZADA')) JULIO,
				(select sum(neto) from orden_trabajo where id_bodega='".$row['id_bodega']."' and fecha_solicitud like '$ano-08%' AND ESTADO not IN 	('NULA','RECHAZADA')) AGOSTO,
				(select sum(neto) from orden_trabajo where id_bodega='".$row['id_bodega']."' and fecha_solicitud like '$ano-09%' AND ESTADO not IN 	('NULA','RECHAZADA')) SEPTIEMBRE,
				(select sum(neto) from orden_trabajo where id_bodega='".$row['id_bodega']."' and fecha_solicitud like '$ano-10%' AND ESTADO not IN 	('NULA','RECHAZADA')) OCTUBRE,
				(select sum(neto) from orden_trabajo where id_bodega='".$row['id_bodega']."' and fecha_solicitud like '$ano-11%' AND ESTADO not IN 	('NULA','RECHAZADA')) NOVIEMBRE,
				(select sum(neto) from orden_trabajo where id_bodega='".$row['id_bodega']."' and fecha_solicitud like '$ano-12%' AND ESTADO not IN 	('NULA','RECHAZADA')) DICIEMBRE,
				(select sum(neto) from orden_trabajo where id_bodega='".$row['id_bodega']."' and fecha_solicitud like '$ano%' AND ESTADO not IN 	('NULA','RECHAZADA')) TOTAL,
				(select sum(total_anual) from bodega_presupuesto where id_bodega='".$row['id_bodega']."' and ano='$ano') ppto";

		$result2 = mysqli_query($mysql,$sql);
		$array 	 = mysqli_fetch_array($result2);

		if($_SERVER['PHP_SELF']=='/suControl/sistema/principal.php')
		{
			$resultado.="<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>
							<td>".$row['nombre']."</td>
							<td align='right'><a href='#' onClick='document.location.href=`reporteOT.php?fecha_inicio_solicitud=".$ano."-01-01&fecha_termino_solicitud=".$ano."-01-31&id_bodega=".$row['id_bodega']."&buscar=buscar`'>$ 	".number_format($array['ENERO'],0,'','.')		."</a></td>
							<td align='right'><a href='#' onClick='document.location.href=`reporteOT.php?fecha_inicio_solicitud=".$ano."-02-01&fecha_termino_solicitud=".$ano."-02-28&id_bodega=".$row['id_bodega']."&buscar=buscar`'>$ 	".number_format($array['FEBRERO'],0,'','.')		."</a></td>
							<td align='right'><a href='#' onClick='document.location.href=`reporteOT.php?fecha_inicio_solicitud=".$ano."-03-01&fecha_termino_solicitud=".$ano."-03-31&id_bodega=".$row['id_bodega']."&buscar=buscar`'>$ 	".number_format($array['MARZO'],0,'','.')		."</a></td>
							<td align='right'><a href='#' onClick='document.location.href=`reporteOT.php?fecha_inicio_solicitud=".$ano."-04-01&fecha_termino_solicitud=".$ano."-04-30&id_bodega=".$row['id_bodega']."&buscar=buscar`'>$ 	".number_format($array['ABRIL'],0,'','.')		."</a></td>
							<td align='right'><a href='#' onClick='document.location.href=`reporteOT.php?fecha_inicio_solicitud=".$ano."-05-01&fecha_termino_solicitud=".$ano."-05-31&id_bodega=".$row['id_bodega']."&buscar=buscar`'>$ 	".number_format($array['MAYO'],0,'','.')		."</a></td>
							<td align='right'><a href='#' onClick='document.location.href=`reporteOT.php?fecha_inicio_solicitud=".$ano."-06-01&fecha_termino_solicitud=".$ano."-06-30&id_bodega=".$row['id_bodega']."&buscar=buscar`'>$ 	".number_format($array['JUNIO'],0,'','.')		."</a></td>
							<td align='right'><a href='#' onClick='document.location.href=`reporteOT.php?fecha_inicio_solicitud=".$ano."-07-01&fecha_termino_solicitud=".$ano."-07-31&id_bodega=".$row['id_bodega']."&buscar=buscar`'>$ 	".number_format($array['JULIO'],0,'','.')		."</a></td>
							<td align='right'><a href='#' onClick='document.location.href=`reporteOT.php?fecha_inicio_solicitud=".$ano."-08-01&fecha_termino_solicitud=".$ano."-08-31&id_bodega=".$row['id_bodega']."&buscar=buscar`'>$ 	".number_format($array['AGOSTO'],0,'','.')		."</a></td>
							<td align='right'><a href='#' onClick='document.location.href=`reporteOT.php?fecha_inicio_solicitud=".$ano."-09-01&fecha_termino_solicitud=".$ano."-09-30&id_bodega=".$row['id_bodega']."&buscar=buscar`'>$ 	".number_format($array['SEPTIEMBRE'],0,'','.')	."</a></td>
							<td align='right'><a href='#' onClick='document.location.href=`reporteOT.php?fecha_inicio_solicitud=".$ano."-10-01&fecha_termino_solicitud=".$ano."-10-31&id_bodega=".$row['id_bodega']."&buscar=buscar`'>$ 	".number_format($array['OCTUBRE'],0,'','.')		."</a></td>
							<td align='right'><a href='#' onClick='document.location.href=`reporteOT.php?fecha_inicio_solicitud=".$ano."-11-01&fecha_termino_solicitud=".$ano."-11-30&id_bodega=".$row['id_bodega']."&buscar=buscar`'>$ 	".number_format($array['NOVIEMBRE'],0,'','.')	."</a></td>
							<td align='right'><a href='#' onClick='document.location.href=`reporteOT.php?fecha_inicio_solicitud=".$ano."-12-01&fecha_termino_solicitud=".$ano."-12-31&id_bodega=".$row['id_bodega']."&buscar=buscar`'>$ 	".number_format($array['DICIEMBRE'],0,'','.')	."</a></td>
							<td align='right'><b><a href='#' onClick='document.location.href=`reporteOT.php?fecha_inicio_solicitud=".$ano."-01-01&fecha_termino_solicitud=".$ano."-12-31&id_bodega=".$row['id_bodega']."&buscar=buscar`'>$ 	".number_format($array['TOTAL'],0,'','.')		."</a></b></td>
							<td align='right'><b><a href='#'>$ 	".number_format($array['ppto'],0,'','.')		."</a></b></td>
						</tr>";
		}
		else
		{
			$resultado.="<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>
							<td>".$row['nombre']."</td>
							<td align='right'>".$array['ENERO']."</td>
							<td align='right'>".$array['FEBRERO']."</td>
							<td align='right'>".$array['MARZO']."</td>
							<td align='right'>".$array['ABRIL']."</td>
							<td align='right'>".$array['MAYO']."</td>
							<td align='right'>".$array['JUNIO']."</td>
							<td align='right'>".$array['JULIO']."</td>
							<td align='right'>".$array['AGOSTO']."</td>
							<td align='right'>".$array['SEPTIEMBRE']."</td>
							<td align='right'>".$array['OCTUBRE']."</td>
							<td align='right'>".$array['NOVIEMBRE']."</td>
							<td align='right'>".$array['DICIEMBRE']."</td>
							<td align='right'><b>".$array['TOTAL']."</b></td>
							<td align='right'><b>".$array['ppto']."</b></td>
						</tr>";
		}


		$total_enero 		+= $array['ENERO'];
		$total_febrero 		+= $array['FEBRERO'];
		$total_marzo 		+= $array['MARZO'];
		$total_abril 		+= $array['ABRIL'];
		$total_mayo 		+= $array['MAYO'];
		$total_junio 		+= $array['JUNIO'];
		$total_julio 		+= $array['JULIO'];
		$total_agosto 		+= $array['AGOSTO'];
		$total_septiembre 	+= $array['SEPTIEMBRE'];
		$total_octubre 		+= $array['OCTUBRE'];
		$total_noviembre 	+= $array['NOVIEMBRE'];
		$total_diciembre 	+= $array['DICIEMBRE'];
		$total_anual 		+= $array['TOTAL'];
		$total_ppto  		+=	$array['ppto'];

	}
		$resultado.="<tr>
						<td bgcolor='#F6E3CE'>TOTAL:</td>
						<td bgcolor='#F6E3CE' align='right'><b>$ ".number_format($total_enero,0,'','.')."</b></td>
						<td bgcolor='#F6E3CE' align='right'><b>$ ".number_format($total_febrero,0,'','.')."</b></td>
						<td bgcolor='#F6E3CE' align='right'><b>$ ".number_format($total_marzo,0,'','.')."</b></td>
						<td bgcolor='#F6E3CE' align='right'><b>$ ".number_format($total_abril,0,'','.')."</b></td>
						<td bgcolor='#F6E3CE' align='right'><b>$ ".number_format($total_mayo,0,'','.')."</b></td>
						<td bgcolor='#F6E3CE' align='right'><b>$ ".number_format($total_junio,0,'','.')."</b></td>
						<td bgcolor='#F6E3CE' align='right'><b>$ ".number_format($total_julio,0,'','.')."</b></td>
						<td bgcolor='#F6E3CE' align='right'><b>$ ".number_format($total_agosto,0,'','.')."</b></td>
						<td bgcolor='#F6E3CE' align='right'><b>$ ".number_format($total_septiembre,0,'','.')."</b></td>
						<td bgcolor='#F6E3CE' align='right'><b>$ ".number_format($total_octubre,0,'','.')."</b></td>
						<td bgcolor='#F6E3CE' align='right'><b>$ ".number_format($total_noviembre,0,'','.')."</b></td>
						<td bgcolor='#F6E3CE' align='right'><b>$ ".number_format($total_diciembre,0,'','.')."</b></td>
						<td bgcolor='#F6E3CE' align='right'><b>$ ".number_format($total_anual,0,'','.')."</b></td>
						<td bgcolor='#F6E3CE' align='right'><b>$ ".number_format($total_ppto,0,'','.')."</b></td>
					</tr>
					<table>";

	return $resultado;

}

function mostrarPresupuestoMantenciones($ano)
{
	include '../include/conexionSistema.php';

	$resultado="";

	$resultado.="<form method='post'>
				   <table width='100%'>
					<tr>
						<td colspan='15'><b>PRESUPUESTO MANTENCIONES ".$ano."</b></td>
					</tr>
					<tr>
						<td bgcolor='#F2F5A9'>Bodega</td>
						<td bgcolor='#F2F5A9'>Defecto</td>
						<td bgcolor='#F2F5A9' align='center'>Enero</td>
						<td bgcolor='#F2F5A9' align='center'>Febrero</td>
						<td bgcolor='#F2F5A9' align='center'>Marzo</td>
						<td bgcolor='#F2F5A9' align='center' >Abril</td>
						<td bgcolor='#F2F5A9' align='center'>Mayo</td>
						<td bgcolor='#F2F5A9' align='center'>Junio</td>
						<td bgcolor='#F2F5A9' align='center'>Julio</td>
						<td bgcolor='#F2F5A9' align='center'>Agosto</td>
						<td bgcolor='#F2F5A9' align='center'>Septiembre</td>
						<td bgcolor='#F2F5A9' align='center'>Octubre</td>
						<td bgcolor='#F2F5A9' align='center'>Noviembre</td>
						<td bgcolor='#F2F5A9' align='center'>Diciembre</td>
						<td bgcolor='#F2F5A9' align='center'>Total</td>
					</tr>";

	$sql		="select id_bodega, nombre from bodega where estado='ACT' order by nombre asc";
	$result 	= mysqli_query($mysql,$sql);
	while($row 	= mysqli_fetch_array($result))
	{
		$total_bodega	= null;


		$resultado.="<tr bgcolor='#A9BCF5'><td colspan='15'>".$row['id_bodega']." - ".$row['nombre']."</td></tr>";

		$sql	    ="select id_tipo_defecto,descripcion from tipo_defecto where estado='ACT' order by descripcion asc";
		$result2 	=mysqli_query($mysql,$sql);
		while($row2 =mysqli_fetch_array($result2)){

			$sql	="select * from bodega_presupuesto where id_bodega='".$row['id_bodega']."' and id_tipo_defecto='".$row2['id_tipo_defecto']."' and ano='$ano'";
			$result3=mysqli_query($mysql,$sql);
			$array3 =mysqli_fetch_array($result3);
			$resultado.="<tr>
							<td></td>
							<td>".$row2['id_tipo_defecto']." - ".strtoupper($row2['descripcion'])."</td>";

			if($_SERVER['PHP_SELF']=='/suControl/sistema/cargarPresupuesto.php'){

					$resultado.="<td><input type='number' name='".$row['id_bodega']."_".$row2['id_tipo_defecto']."_1' value='".$array3['enero']."' style='width:75px'></td>
								 <td><input type='number' name='".$row['id_bodega']."_".$row2['id_tipo_defecto']."_2' value='".$array3['febrero']."' style='width:75px'></td>
								 <td><input type='number' name='".$row['id_bodega']."_".$row2['id_tipo_defecto']."_3' value='".$array3['marzo']."' style='width:75px'></td>
								 <td><input type='number' name='".$row['id_bodega']."_".$row2['id_tipo_defecto']."_4' value='".$array3['abril']."' style='width:75px'></td>
								 <td><input type='number' name='".$row['id_bodega']."_".$row2['id_tipo_defecto']."_5' value='".$array3['mayo']."' style='width:75px'></td>
								 <td><input type='number' name='".$row['id_bodega']."_".$row2['id_tipo_defecto']."_6' value='".$array3['junio']."' style='width:75px'></td>
								 <td><input type='number' name='".$row['id_bodega']."_".$row2['id_tipo_defecto']."_7' value='".$array3['julio']."' style='width:75px'></td>
								 <td><input type='number' name='".$row['id_bodega']."_".$row2['id_tipo_defecto']."_8' value='".$array3['agosto']."' style='width:75px'></td>
								 <td><input type='number' name='".$row['id_bodega']."_".$row2['id_tipo_defecto']."_9' value='".$array3['septiembre']."' style='width:75px'></td>
								 <td><input type='number' name='".$row['id_bodega']."_".$row2['id_tipo_defecto']."_10' value='".$array3['octubre']."' style='width:75px'></td>
								 <td><input type='number' name='".$row['id_bodega']."_".$row2['id_tipo_defecto']."_11' value='".$array3['noviembre']."' style='width:75px'></td>
								 <td><input type='number' name='".$row['id_bodega']."_".$row2['id_tipo_defecto']."_12' value='".$array3['diciembre']."' style='width:75px'></td>
								 <td><input type='number' disabled value='' style='width:75px'></td>";

			}else{
					$resultado.="<td align='right'>".$array3['enero']."</td>
								 <td align='right'>".$array3['febrero']."</td>
								 <td align='right'>".$array3['marzo']."</td>
								 <td align='right'>".$array3['abril']."</td>
								 <td align='right'>".$array3['mayo']."</td>
								 <td align='right'>".$array3['junio']."</td>
								 <td align='right'>".$array3['julio']."</td>
								 <td align='right'>".$array3['agosto']."</td>
								 <td align='right'>".$array3['septiembre']."</td>
								 <td align='right'>".$array3['octubre']."</td>
								 <td align='right'>".$array3['noviembre']."</td>
								 <td align='right'>".$array3['diciembre']."</td>
								 <td></td>";
			}

			$resultado.="</tr>";

			$total_bodega[1]+=$array3['enero'];	$total_bodega[2]+=$array3['febrero'];$total_bodega[3]+=$array3['marzo'];	$total_bodega[4]+=$array3['abril'];
			$total_bodega[5]+=$array3['mayo'];$total_bodega[6]+=$array3['junio'];$total_bodega[7]+=$array3['julio'];	$total_bodega[8]+=$array3['agosto'];
			$total_bodega[9]+=$array3['septiembre'];$total_bodega[10]+=$array3['octubre'];$total_bodega[11]+=$array3['noviembre'];$total_bodega[12]+=$array3['diciembre'];

		}

		$resultado.="<tr>
						<td bgcolor='#CEE3F6'></td>
						<td bgcolor='#CEE3F6'>TOTAL:</td>
						<td bgcolor='#CEE3F6' align='right'>".$total_bodega[1]."</td>
						<td bgcolor='#CEE3F6' align='right'>".$total_bodega[2]."</td>
						<td bgcolor='#CEE3F6' align='right'>".$total_bodega[3]."</td>
						<td bgcolor='#CEE3F6' align='right'>".$total_bodega[4]."</td>
						<td bgcolor='#CEE3F6' align='right'>".$total_bodega[5]."</td>
						<td bgcolor='#CEE3F6' align='right'>".$total_bodega[6]."</td>
						<td bgcolor='#CEE3F6' align='right'>".$total_bodega[7]."</td>
						<td bgcolor='#CEE3F6' align='right'>".$total_bodega[8]."</td>
						<td bgcolor='#CEE3F6' align='right'>".$total_bodega[9]."</td>
						<td bgcolor='#CEE3F6' align='right'>".$total_bodega[10]."</td>
						<td bgcolor='#CEE3F6' align='right'>".$total_bodega[11]."</td>
						<td bgcolor='#CEE3F6' align='right'>".$total_bodega[12]."</td>
						<td bgcolor='#CEE3F6' align='right'>".(array_sum($total_bodega))."</td>
					</tr>";

	}


	$resultado.="</table>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/cargarPresupuesto.php'){
		$resultado.="<br><input type='submit' name='guardar' value='Guardar Presupuesto'>";
		$resultado.="&nbsp;<input type='button' name='descargar' value='Descargar' onClick='document.location.href=`descargarPresupuestoMantenciones.php?ano=$ano`;'>";
		$resultado.="&nbsp;<input type='button' name='eliminar' value='Eliminar' onClick='eliminarPresupuesto($ano);'>";
		$resultado.="<input type='hidden' name='ano' value='$ano'>";
	 }

	$resultado.="</form>";

	return $resultado;
}



function db_mantenecionMensualPpto($mes,$ano)
{
	include '../include/conexionSistema.php';

	//define las variables
	$resultado="";
	$cantidad_dias_mes 	= date('t', mktime(0,0,0, $mes, 1, $ano));
	$dias_transcurridos = date('d');

	if($mes==1){$nombre_mes="enero";		}
	if($mes==2){$nombre_mes="febrero";		}
	if($mes==3){$nombre_mes="marzo";		}
	if($mes==4){$nombre_mes="abril";		}
	if($mes==5){$nombre_mes="mayo";			}
	if($mes==6){$nombre_mes="junio";		}
	if($mes==7){$nombre_mes="julio";		}
	if($mes==8){$nombre_mes="agosto";		}
	if($mes==9){$nombre_mes="septiembre";	}
	if($mes==10){$nombre_mes="octubre";		}
	if($mes==11){$nombre_mes="noviembre";	}
	if($mes==12){$nombre_mes="diciembre";	}



	$resultado.="<h3>Informe Mantenciones Mensual v/s Presupuesto</3>";
	$resultado.="<table width='50%' rules='all' border='1' style='font-size:11px;font-family:Helvetica, Arial, sans-serif;'>
					<tr>
						<td bgcolor='#F2F2F2'>BOD</td>
						<td bgcolor='#F2F2F2'>ACUM</td>
						<td bgcolor='#F2F2F2'>IMPL</td>
						<td bgcolor='#F2F2F2'>PROY LINEAL</td>
						<td bgcolor='#F2F2F2'>CUMP PROY</td>
						<td bgcolor='#F2F2F2'></td>
						<td bgcolor='#F2F2F2'>PPTO</td>
						<td bgcolor='#F2F2F2'>REAL</td>
					</tr>";

	$sql		=	"select id_bodega, nombre from bodega where estado='ACT' order by orden asc";
	$result 	= 	mysqli_query($mysql,$sql);
	while($row 	= 	mysqli_fetch_array($result))
	{
		$sql="select
				(select sum(neto) 	from orden_trabajo where estado in ('NULA','RECHAZADA') and fecha_solicitud like '$ano-$mes%' and id_bodega='".$row['id_bodega']."') acum,
				(select sum(total) 	from orden_trabajo where estado in ('NULA','RECHAZADA') and fecha_solicitud like '$ano-$mes%' and id_bodega='".$row['id_bodega']."' and gasto_implementacion='SI') impl,
				(select sum($nombre_mes) from bodega_presupuesto where ano='$ano' and id_bodega='".$row['id_bodega']."') ppto";

		$result2 					= mysqli_query($mysql,$sql);
		$array 						= mysqli_fetch_array($result2);
		$bodega						= substr(str_replace("BASE ","",$row['nombre']),0,3);
		$acum						= $array['acum'];
		$impl						= $array['impl'];
		$ppto						= $array['ppto'];
		$proyeccion_lineal 			= ($acum/$dias_transcurridos)*$cantidad_dias_mes;
		$cumplimiento_proyectado	= ($proyeccion_lineal/$ppto)*100;
		$porcentaje_real 			= ($acum/$ppto)*100;


		if($cumplimiento_proyectado>=0 and $cumplimiento_proyectado<80){ $semaforo="<img src='../images/ok.png'>"; }
		if($cumplimiento_proyectado>=80 and $cumplimiento_proyectado<100){$semaforo="<img src='../images/warning.png'>";}
		if($cumplimiento_proyectado>=100){$semaforo="<img src='../images/error.png'>";}

		$resultado.="<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>
						<td>				".$bodega."</td>
						<td align='right'>$ ". number_format($acum,0,'','.') ."</td>
						<td align='right'>$ ". number_format($impl,0,'','.') ."</td>
						<td align='right'>$ ". number_format($proyeccion_lineal,0,'','.') ."</td>
						<td align='center'>	". number_format($cumplimiento_proyectado,1,',','')."%</td>
						<td align='center'>$semaforo</td>
						<td align='right'>$ ".number_format($ppto,0,'','.')."</td>
						<td align='right'>".number_format($porcentaje_real,1,',','')."%</td>
					</tr>";

		$total_acum +=$acum;
		$total_impl +=$impl;
		$total_proyeccion_lineal += $proyeccion_lineal;
		$total_ppto 			 += $ppto;


	}
		$total_cumplimiento_proyectado = ($total_proyeccion_lineal/$total_ppto)*100;
		$total_porcentaje_real			=($total_acum/$total_ppto)*100;


		if($total_cumplimiento_proyectado>=0 and $total_cumplimiento_proyectado<80){ $semaforo="<img src='../images/ok.png'>"; }
		if($total_cumplimiento_proyectado>=80 and $total_cumplimiento_proyectado<100){$semaforo="<img src='../images/warning.png'>";}
		if($total_cumplimiento_proyectado>=100){$semaforo="<img src='../images/error.png'>";}



		$resultado.="<tr>
						<td  bgcolor='#F6E3CE'><b>TOT:</b></td>
						<td  bgcolor='#F6E3CE' align='right'><b>$ ". number_format($total_acum,0,'','.') ."</b></td>
						<td  bgcolor='#F6E3CE' align='right'><b>$ ". number_format($total_impl,0,'','.') ."</b></td>
						<td  bgcolor='#F6E3CE' align='right'><b>$ ". number_format($total_proyeccion_lineal,0,'','.') ."</b></td>
						<td  bgcolor='#F6E3CE' align='center'><b> ". number_format($total_cumplimiento_proyectado,1,',','')."%</b></td>
						<td  bgcolor='#F6E3CE' align='center'>$semaforo</td>
						<td  bgcolor='#F6E3CE' align='right'><b>$ ".number_format($total_ppto,0,'','.')."</b></td>
						<td  bgcolor='#F6E3CE' align='right'><b>  ".number_format($total_porcentaje_real,1,',','')."%</b></td>
					</tr>
					</table>";

	return $resultado;
}



function reporteGuias($numero_interno,$fecha_inicio_ingreso,$fecha_termino_ingreso,$rut_empresa,$digitador,$codigo_movimiento,$descripcion_movimiento,$estado,$rut_proveedor,$nombre_proveedor,$oc,$guia_despacho,$numero_boleta,$numero_factura,$nota_credito,$razon_emision)
{
	include '../include/conexionSistema.php';


	$resultado="";

	if($numero_interno<>'')										{$filtro_numero_interno		="and numero_interno='$numero_interno'";		}
	if($oc<>'')													{$filtro_oc					="and oc like '%$oc%'";							}
	if($guia_despacho<>'')										{$filtro_guia_despacho		="and guia_despacho like '%$guia_despacho%'";	}
	if($numero_boleta<>'')										{$filtro_boleta				="and numero_boleta like '%$numero_boleta%'";	}
	if($numero_factura<>'')										{$filtro_factura			="and numero_factura like '%$numero_factura%'";	}
	if($nota_credito<>'')										{$filtro_nota_credito		="and nota_credito like '%$nota_credito%'";		}
	if($nombre_proveedor<>'')									{$filtro_nombre_proveedor	="and nombre_proveedor like '%$nombre_proveedor%'";}
	if($fecha_inicio_ingreso<>'' and $fecha_termino_ingreso<>''){$filtro_fecha_ingreso		="and fecha_ingreso between '$fecha_inicio_ingreso 00:00:00' and '$fecha_termino_ingreso 23:59:59'";}


	  $sql="select *
			from guia
			where
			rut_empresa 		  	   		like 	'%$rut_empresa%'
			$filtro_fecha_ingreso
			$filtro_numero_interno
			$filtro_nombre_proveedor
			$filtro_oc
			$filtro_guia_despacho
			$filtro_boleta
			$filtro_factura
			$filtro_nota_credito
			and digitador 				like  	'%$digitador%'
			and codigo_movimiento 		like 	'%$codigo_movimiento%'
			and descripcion_movimiento 	like 	'%$descripcion_movimiento%'
			and estado					like 	'%$estado%'
			and rut_proveedor			like 	'%$rut_proveedor%'
			and razon_emision			like 	'%$razon_emision%'
			order by fecha_ingreso desc";


	$resultado.="<table width='200%' style='font-size:10px' rules='all' border='1'>
					<tr>
						<td bgcolor='#D8D8D8'>Numero Interno</td>
						<td bgcolor='#D8D8D8'>Rut Empresa</td>
						<td bgcolor='#D8D8D8'>Codigo Movimiento</td>
						<td bgcolor='#D8D8D8'>Descripcion de Movimiento</td>
						<td bgcolor='#D8D8D8'>Fecha Ingreso</td>
						<td bgcolor='#D8D8D8'>Digitador</td>
						<td bgcolor='#D8D8D8'>Rut Proveedor</td>
						<td bgcolor='#D8D8D8'>Nombre Proveedor</td>
						<td bgcolor='#D8D8D8'>Estado</td>
						<td bgcolor='#D8D8D8'>OC</td>
						<td bgcolor='#D8D8D8'>Guia Despacho</td>
						<td bgcolor='#D8D8D8'>Boleta</td>
						<td bgcolor='#D8D8D8'>Factura</td>
						<td bgcolor='#D8D8D8'>Nota Credito</td>
						<td bgcolor='#D8D8D8'>Razon Emision</td>
						<td bgcolor='#D8D8D8'>Patente</td>
						<td bgcolor='#D8D8D8'>Rut Conductor</td>
						<td bgcolor='#D8D8D8'>Nombre Conductor</td>
						<td bgcolor='#D8D8D8'>Despachar A</td>
						<td bgcolor='#D8D8D8'>Neto</td>
						<td bgcolor='#D8D8D8'>IVA</td>
						<td bgcolor='#D8D8D8'>Total</td>
					</tr>";

	$result = mysqli_query($mysql,$sql);

	while($row = mysqli_fetch_array($result))
	{

		$resultado.="<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>
						<td>".$row['numero_interno']."</td>
						<td>".$row['rut_empresa']."</td>
						<td>".$row['codigo_movimiento']."</td>
						<td>".$row['descripcion_movimiento']."</td>
						<td>".$row['fecha_ingreso']."</td>
						<td>".$row['digitador']."</td>
						<td>".$row['rut_proveedor']."</td>
						<td>".$row['nombre_proveedor']."</td>
						<td>".$row['estado']."</td>
						<td>".$row['oc']."</td>
						<td>".$row['guia_despacho']."</td>
						<td>".$row['numero_boleta']."</td>
						<td>".$row['numero_factura']."</td>
						<td>".$row['nota_credito']."</td>
						<td>".$row['razon_emision']."</td>
						<td>".$row['patente']."</td>
						<td>".$row['rut_conductor']."</td>
						<td>".$row['nombre_conductor']."</td>
						<td>".$row['despachar_a']."</td>";

		if($_SERVER['PHP_SELF']=='/suControl/sistema/reporteGuias.php')
		{
			$resultado.="	<td align='right'>".number_format($row['neto'],0,'','.')."</td>
							<td align='right'>".number_format($row['iva'],0,'','.')."</td>
							<td align='right'>".number_format($row['total'],0,'','.')."</td>";

		}
		else
		{
			$resultado.="	<td>".$row['neto']."</td>
							<td>".$row['iva']."</td>
							<td>".$row['total']."</td>";
		}

		$resultado.="</tr>";

					$neto += $row['neto'];
					$iva  += $row['iva'];
					$total+= $row['total'];
	}


	//si esta viendo la informacion desde el navegador debe darle formato de numero


	$resultado.="<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/reporteGuias.php')
	{
		$resultado.="		<td align='right'>".number_format($neto,0,'','.')."</td>
							<td align='right'>".number_format($iva,0,'','.')."</td>
							<td align='right'>".number_format($total,0,'','.')."</td>
						</tr>";

	}
	else
	{
		$resultado.="		<td align='right'>$neto</td>
							<td align='right'>$iva</td>
							<td align='right'>$total</td>
						</tr>";

	}



	$resultado.="</table>";


	return $resultado	;
}


function reporteOT2($numero_interno,$rut_empresa,$codigo_movimiento,$patente,$digitador,$id_bodega,$id_operacion,$nombre_bodega,$nombre_operacion,$supervisor,$fecha_inicio_solicitud,$fecha_termino_solicitud,$estado,$tipo_movimiento,$comentario,$tipo_defecto)
{
	include '../include/conexionSistema.php';

	$resultado="";

	$resultado.="<table width='230%'>
					<tr>
						<td bgcolor='#D8D8D8'>OT</td>
						<td bgcolor='#D8D8D8'>Rut Empresa</td>
						<td bgcolor='#D8D8D8'>Empresa</td>
						<td bgcolor='#D8D8D8'>Estado</td>
						<td bgcolor='#D8D8D8'>Codigo Movimiento</td>
						<td bgcolor='#D8D8D8'>Movimiento</td>
						<td bgcolor='#D8D8D8'>Patente</td>
						<td bgcolor='#D8D8D8'>Kilometraje</td>
						<td bgcolor='#D8D8D8'>Mantencion Preventiva</td>
						<td bgcolor='#D8D8D8'>Digitador</td>
						<td bgcolor='#D8D8D8'>Fecha Digitacion</td>
						<td bgcolor='#D8D8D8'>Bodega</td>
						<td bgcolor='#D8D8D8'>Operacion</td>
						<td bgcolor='#D8D8D8'>Rut Conductor</td>
						<td bgcolor='#D8D8D8'>Nombre Conductor</td>
						<td bgcolor='#D8D8D8'>Marca</td>
						<td bgcolor='#D8D8D8'>Modelo</td>
						<td bgcolor='#D8D8D8'>Tipo Vehiculo</td>
						<td bgcolor='#D8D8D8'>Supervisor</td>
						<td bgcolor='#D8D8D8'>Fecha Solicitud</td>
						<td bgcolor='#D8D8D8'>Fecha Liberacion</td>
						<td bgcolor='#D8D8D8'>Fecha Entrada Taller</td>
						<td bgcolor='#D8D8D8'>Fecha Salida Taller</td>
						<td bgcolor='#D8D8D8'>Fecha Termino Taller</td>
						<td bgcolor='#D8D8D8'>Fecha Rechazo Defecto</td>
						<td bgcolor='#D8D8D8'>Comentario Rechazo Defecto Vehiculo</td>
						<td bgcolor='#D8D8D8'>Comentario Defecto Vehiculo</td>
						<td bgcolor='#D8D8D8'>Comentario Final Orden Trabajo</td>
						<td bgcolor='#D8D8D8'>Neto</td>
						<td bgcolor='#D8D8D8'>Iva</td>
						<td bgcolor='#D8D8D8'>Total</td>
					</tr>";

	$filtro_id_bodega = "";
	$filtro_numero_interno = "";
	$filtro_estado = "";
	$filtro_fecha_solicitud = "";
	$filtro_nombre_operacion = "";
	$filtro_codigo_movimiento = "";
	$filtro_comentario = "";
	$filtro_tipo_defecto = "";

	if($numero_interno)	{$filtro_numero_interno="and ot.numero_interno='$numero_interno'";}
	if($id_bodega){$filtro_id_bodega="and ot.id_bodega='$id_bodega'";}
	if($fecha_inicio_solicitud<>'' and $fecha_termino_solicitud<>''){$filtro_fecha_solicitud="and ot.fecha_solicitud between '$fecha_inicio_solicitud' and '$fecha_termino_solicitud'";}
	if($estado=='dashboard')
	{
		$filtro_estado=" ot.estado in ('PENDIENTE','LIBERADA') ";
	} else
	{
		$filtro_estado=" estado like '%$estado%' ";
	}
	if($nombre_operacion<>''){ $filtro_nombre_operacion  = "and ot.id_operacion in (select id_operacion from operacion where nombre_operacion ='$nombre_operacion')";}else{$filtro_nombre_operacion="";}
	if($tipo_movimiento<>''){$filtro_codigo_movimiento="and ot.codigo_movimiento='$tipo_movimiento'";}

	if($tipo_defecto<>''){$filtro_tipo_defecto=" and ot.numero_interno in (select numero_interno from orden_trabajo_defecto where id_tipo_defecto = $tipo_defecto)";}

	if($comentario<>''){$filtro_comentario="and ot.comentario_final_orden_trabajo like '%$comentario%'";}
	  $sql="select ot.*,
			(select marca from vehiculo_marca where id_marca=ot.id_marca)marca,
			(select modelo from vehiculo_modelo where id_modelo=ot.id_modelo)modelo,
			(select tipo from vehiculo_tipo where id_tipo=ot.id_tipo)tipo_vehiculo,
			(select descripcion from tipo_movimiento where codigo_movimiento = ot.codigo_movimiento) descripcion_movimiento,
			(select nombre_operacion from operacion where id_operacion = ot.id_operacion) nombre_operacion
			from orden_trabajo ot
			where " .
			$filtro_estado .
			($codigo_movimiento <> '' ? "and ot.codigo_movimiento like '%$codigo_movimiento%' " : "") .
			($patente <> '' ? "and ot.patente like '%$patente%' " : " ") .
			($digitador <> '' ? "and ot.digitador like '%$digitador%' " : "") .
			($nombre_bodega <> '' ? "and ot.nombre_bodega like '%$nombre_bodega%' " : "") .
			($supervisor <> '' ? "and ot.supervisor like '%$supervisor%' " : "") .
			($rut_empresa <> '' ? "and ot.rut_empresa like '%$rut_empresa%' " : "") .
			$filtro_id_bodega .
			$filtro_numero_interno .
			$filtro_fecha_solicitud .
			$filtro_nombre_operacion .
			$filtro_codigo_movimiento .
			$filtro_tipo_defecto .
			$filtro_comentario .
			" order by ot.numero_interno desc";




	$neto = 0;
	$iva = 0;
	$total = 0;
	if ($result = mysqli_query($mysql,$sql) and $result->num_rows > 0)
	{

		while($row = mysqli_fetch_array($result))
		{
			$resultado.="<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>";

			//if($_SERVER['PHP_SELF']=='/suControl/sistema/reporteOT.php'){
				$resultado.="<td><a href='generarOT.php?numero_interno=".$row['numero_interno']."'>".$row['numero_interno']."</a></td>";
			/*}else{
				$resultado.="<td>".$row['numero_interno']."</td>";
			}*/

			$resultado.="	<td nowrap>".$row['rut_empresa']."</td>
							<td >".$row['nombre_empresa']."</td>
							<td ><font color='red'>".$row['estado']."</font></td>
							<td >".$row['codigo_movimiento']."</td>
							<td >".$row['descripcion_movimiento']."</td>
							<td >".$row['patente']."</td>
							<td >".number_format($row['kilometraje'],0,'','.')."</td>
							<td >".number_format($row['mantencion_preventiva_kilometraje'],0,'','.')."</td>
							<td >".$row['digitador']."</td>
							<td >".$row['fecha_digitacion']."</td>
							<td >".$row['nombre_bodega']."</td>
							<td >".$row['nombre_operacion']."</td>
							<td >".$row['rut_conductor']."</td>
							<td >".$row['nombre_conductor']."</td>
							<td >".$row['marca']."</td>
							<td >".$row['modelo']."</td>
							<td >".$row['tipo_vehiculo']."</td>
							<td >".$row['supervisor']."</td>
							<td >".$row['fecha_solicitud']."</td>
							<td >".$row['fecha_liberacion']."</td>
							<td >".$row['fecha_entrada_taller']."</td>
							<td >".$row['fecha_salida_taller']."</td>
							<td >".$row['fecha_termino_taller']."</td>
							<td >".$row['fecha_rechazo_defecto_vehiculo']."</td>
							<td >".$row['comentario_rechazo_defecto_vehiculo']."</td>
							<td >".$row['comentario_defecto_vehiculo']."</td>
							<td >".$row['comentario_final_orden_trabajo']."</td>";

			if($_SERVER['PHP_SELF']=='/suControl/sistema/reporteOT.php')
			{
				$resultado.="	<td align='right'>$".number_format($row['neto'],0,'','.')."</td>
								<td align='right'>$".number_format($row['iva'],0,'','.')."</td>
								<td align='right'>$".number_format($row['total'],0,'','.')."</td>";

			}
			else
			{
				$resultado.="	<td align='right'>".$row['neto']."</td>
								<td align='right'>".$row['iva']."</td>
								<td align='right'>".$row['total']."</td>";

			}

				$resultado.="</tr>";

				$neto+=$row['neto'];
				$iva +=$row['iva'];
				$total+=$row['total'];
		}
		$result->close();
	}


	$resultado.="<tr>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>";

		if($_SERVER['PHP_SELF']=='/suControl/sistema/reporteOT.php')
		{
			$resultado.="	<td align='right'><b>$".number_format($neto,0,'','.')."</b></td>
							<td align='right'><b>$".number_format($iva,0,'','.')."</b></td>
							<td align='right'><b>$".number_format($total,0,'','.')."</b></td>";

		}
		else
		{
			$resultado.="	<td align='right'><b>".$neto."</b></td>
							<td align='right'><b>".$iva."</b></td>
							<td align='right'><b>".$total."</b></td>";

		}

			$resultado.="</tr>";


	$resultado.="</table>";

 return $resultado;
}

function reporteOT($numero_interno,$rut_empresa,$codigo_movimiento,$patente,$digitador,$id_bodega,$id_operacion,$nombre_bodega,$nombre_operacion,$supervisor,$fecha_inicio_solicitud,$fecha_termino_solicitud,$estado)
{
	include '../include/conexionSistema.php';

	$resultado="";

	$resultado.="<table width='230%'>
					<tr>
						<td bgcolor='#D8D8D8'>OT</td>
						<td bgcolor='#D8D8D8'>Rut Empresa</td>
						<td bgcolor='#D8D8D8'>Empresa</td>
						<td bgcolor='#D8D8D8'>Estado</td>
						<td bgcolor='#D8D8D8'>Codigo Movimiento</td>
						<td bgcolor='#D8D8D8'>Movimiento</td>
						<td bgcolor='#D8D8D8'>Patente</td>
						<td bgcolor='#D8D8D8'>Kilometraje</td>
						<td bgcolor='#D8D8D8'>Mantencion Preventiva</td>
						<td bgcolor='#D8D8D8'>Digitador</td>
						<td bgcolor='#D8D8D8'>Fecha Digitacion</td>
						<td bgcolor='#D8D8D8'>Bodega</td>
						<td bgcolor='#D8D8D8'>Operacion</td>
						<td bgcolor='#D8D8D8'>Rut Conductor</td>
						<td bgcolor='#D8D8D8'>Nombre Conductor</td>
						<td bgcolor='#D8D8D8'>Marca</td>
						<td bgcolor='#D8D8D8'>Modelo</td>
						<td bgcolor='#D8D8D8'>Tipo Vehiculo</td>
						<td bgcolor='#D8D8D8'>Supervisor</td>
						<td bgcolor='#D8D8D8'>Fecha Solicitud</td>
						<td bgcolor='#D8D8D8'>Fecha Liberacion</td>
						<td bgcolor='#D8D8D8'>Fecha Entrada Taller</td>
						<td bgcolor='#D8D8D8'>Fecha Salida Taller</td>
						<td bgcolor='#D8D8D8'>Fecha Termino Taller</td>
						<td bgcolor='#D8D8D8'>Fecha Rechazo Defecto</td>
						<td bgcolor='#D8D8D8'>Comentario Rechazo Defecto Vehiculo</td>
						<td bgcolor='#D8D8D8'>Comentario Defecto Vehiculo</td>
						<td bgcolor='#D8D8D8'>Comentario Final Orden Trabajo</td>
						<td bgcolor='#D8D8D8'>Neto</td>
						<td bgcolor='#D8D8D8'>Iva</td>
						<td bgcolor='#D8D8D8'>Total</td>
					</tr>";

	if($numero_interno)	{$filtro_numero_interno="and ot.numero_interno='$numero_interno'";}
	if($id_bodega){$filtro_id_bodega="and ot.id_bodega='$id_bodega'";}
	if($fecha_inicio_solicitud<>'' and $fecha_termino_solicitud<>''){$filtro_fecha_solicitud="and ot.fecha_solicitud between '$fecha_inicio_solicitud' and '$fecha_termino_solicitud'";}
	if($estado=='dashboard'){$filtro_estado="and ot.estado in ('PENDIENTE','LIBERADA')";}else{$filtro_estado="and estado like '%$estado%'";}
	if($nombre_operacion<>''){ $filtro_nombre_operacion  = "and ot.id_operacion in (select id_operacion from operacion where nombre_operacion ='$nombre_operacion')";}else{$filtro_nombre_operacion="";}

	  $sql="select ot.*,
			(select marca from vehiculo_marca where id_marca=ot.id_marca)marca,
			(select modelo from vehiculo_modelo where id_modelo=ot.id_modelo)modelo,
			(select tipo from vehiculo_tipo where id_tipo=ot.id_tipo)tipo_vehiculo,
			(select descripcion from tipo_movimiento where codigo_movimiento = ot.codigo_movimiento) descripcion_movimiento,
			(select nombre_operacion from operacion where id_operacion = ot.id_operacion) nombre_operacion
			from orden_trabajo ot
			where ot.codigo_movimiento like '%$codigo_movimiento%'
			and ot.patente like '%$patente%'
			and ot.digitador like '%$digitador%'
			and ot.nombre_bodega like '%$nombre_bodega%'
			and ot.supervisor like '%$supervisor%'
			and ot.rut_empresa like '%$rut_empresa%'
			$filtro_id_bodega
			$filtro_numero_interno
			$filtro_estado
			$filtro_fecha_solicitud
			$filtro_nombre_operacion
			order by ot.numero_interno desc";

	$result = mysqli_query($mysql,$sql);

	while($row = mysqli_fetch_array($result))
	{
		$resultado.="<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>";

		if($_SERVER['PHP_SELF']=='/suControl/sistema/reporteOT.php'){
			$resultado.="<td><a href='generarOT.php?numero_interno=".$row['numero_interno']."'>".$row['numero_interno']."</a></td>";
		}else{
			$resultado.="<td>".$row['numero_interno']."</td>";
		}

		$resultado.="	<td >".$row['rut_empresa']."</td>
						<td >".$row['nombre_empresa']."</td>
						<td ><font color='red'>".$row['estado']."</font></td>
						<td >".$row['codigo_movimiento']."</td>
						<td >".$row['descripcion_movimiento']."</td>
						<td >".$row['patente']."</td>
						<td >".number_format($row['kilometraje'],0,'','.')."</td>
						<td >".number_format($row['mantencion_preventiva_kilometraje'],0,'','.')."</td>
						<td >".$row['digitador']."</td>
						<td >".$row['fecha_digitacion']."</td>
						<td >".$row['nombre_bodega']."</td>
						<td >".$row['nombre_operacion']."</td>
						<td >".$row['rut_conductor']."</td>
						<td >".$row['nombre_conductor']."</td>
						<td >".$row['marca']."</td>
						<td >".$row['modelo']."</td>
						<td >".$row['tipo_vehiculo']."</td>
						<td >".$row['supervisor']."</td>
						<td >".$row['fecha_solicitud']."</td>
						<td >".$row['fecha_liberacion']."</td>
						<td >".$row['fecha_entrada_taller']."</td>
						<td >".$row['fecha_salida_taller']."</td>
						<td >".$row['fecha_termino_taller']."</td>
						<td >".$row['fecha_rechazo_defecto_vehiculo']."</td>
						<td >".$row['comentario_rechazo_defecto_vehiculo']."</td>
						<td >".$row['comentario_defecto_vehiculo']."</td>
						<td >".$row['comentario_final_orden_trabajo']."</td>";

		if($_SERVER['PHP_SELF']=='/suControl/sistema/reporteOT.php')
		{
			$resultado.="	<td align='right'>$".number_format($row['neto'],0,'','.')."</td>
							<td align='right'>$".number_format($row['iva'],0,'','.')."</td>
							<td align='right'>$".number_format($row['total'],0,'','.')."</td>";

		}
		else
		{
			$resultado.="	<td align='right'>".$row['neto']."</td>
							<td align='right'>".$row['iva']."</td>
							<td align='right'>".$row['total']."</td>";

		}

			$resultado.="</tr>";

			$neto+=$row['neto'];
			$iva +=$row['iva'];
			$total+=$row['total'];
	}


	$resultado.="<tr>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td ></td>";

		if($_SERVER['PHP_SELF']=='/suControl/sistema/reporteOT.php')
		{
			$resultado.="	<td align='right'><b>$".number_format($neto,0,'','.')."</b></td>
							<td align='right'><b>$".number_format($iva,0,'','.')."</b></td>
							<td align='right'><b>$".number_format($total,0,'','.')."</b></td>";

		}
		else
		{
			$resultado.="	<td align='right'><b>".$neto."</b></td>
							<td align='right'><b>".$iva."</b></td>
							<td align='right'><b>".$total."</b></td>";

		}

			$resultado.="</tr>";


	$resultado.="</table>";

 return $resultado;
}



function listarProveedores($buscar_proveedor)
{
	include '../include/conexionSistema.php';

	$resultado="";

	$c="";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorProveedores.php')
	{
		$resultado.="<table width='10%'>
						<tr>
							<td><a href='agregarProveedor.php'><img src='../images/agregar.png' title='Agregar'></a></td>
							<td><a href='descargarListadoProveedores.php?buscar_proveedor=".$buscar_proveedor."'><img src='../images/descargar.png' title='Descargar'></a></td>
							<td><a href='#' onClick='print();'><img src='../images/imprimir.png' title='Imprimir'></a></td>
							<td><a href='mantenedorProveedores.php'><img src='../images/recargar.png' title='Actualizar'></a></td>
						</tr>
						</table>";
	}

     $sql="select p.*,
				(select descripcion from condicion_pago where id_condicion_pago = p.id_condicion_pago) condicion_pago
				from proveedor p
				where p.rut_proveedor like '%$buscar_proveedor%'
				or p.nombre like '%$buscar_proveedor%'
				or p.estado like '%$buscar_proveedor%'
				or p.tipo like '%$buscar_proveedor%'
				or p.id_condicion_pago in (select id_condicion_pago from condicion_pago where descripcion like '%$buscar_proveedor%')
				or p.telefono like '%$buscar_proveedor%'
				or p.direccion like '%$buscar_proveedor%'
				or p.contacto like '%$buscar_proveedor%'
				order by p.nombre asc;";

	$result = mysqli_query($mysql,$sql);

	$resultado.= "<table width='100%'>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorProveedores.php')
	{
		$resultado.="<tr>
						<td></td>
		                <td><form method='post' action='mantenedorProveedores.php'><input type='text' name='buscar_proveedor' value='$buscar_proveedor' placeholder='Buscar...'></form></td>
					 </tr>";
	}

	$resultado.="<tr>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorProveedores.php'){$resultado.="<td bgcolor='#EFF2FB' ALIGN='CENTER'>EDITAR</td>";}

	$resultado.="<td bgcolor='#EFF2FB'>RUT</td>
				 <td bgcolor='#EFF2FB'>NOMBRE</td>
				 <td bgcolor='#EFF2FB'>DIRECCION</td>
				 <td bgcolor='#EFF2FB'>TELEFONO</td>
				 <td bgcolor='#EFF2FB'>CONDICION DE PAGO</td>
				 <td bgcolor='#EFF2FB'>CONTACTO</td>
				 <td bgcolor='#EFF2FB'>TIPO</td>
				 <td bgcolor='#EFF2FB'>ESTADO</td>
				</tr>";

	while($row = mysqli_fetch_array($result))
	{

		$resultado.= "<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>";
		if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorProveedores.php'){$resultado.="<td align='center'><form method='post' action='fichaProveedor.php'><input type='hidden' name='rut_proveedor' value='".$row['rut_proveedor']."'><input type='image' src='../images/editar.png' title='Editar' name='editar' value='editar'></form></td>";}
	$resultado.= "<td>".strtoupper($row['rut_proveedor'])."</td>
					  <td>".strtoupper($row['nombre'])."</td>
					  <td>".strtoupper($row['direccion'])."</td>
					  <td>".strtoupper($row['telefono'])."</td>
					  <td>".strtoupper($row['condicion_pago'])."</td>
					  <td>".strtoupper($row['contacto'])."</td>
					  <td>".strtoupper($row['tipo'])."</td>
					  <td>".strtoupper($row['estado'])."</td>
					</tr>";
		$c++;
	}

	$resultado.="</table><br><br>";
	$resultado.="Cantidad de Proveedores:$c";

	return $resultado;

}




function configuracionGuiasTiposMovimientos()
{

	include '../include/conexionSistema.php';

	$resultado="";

	$c="";

    $sql="select * from tipo_movimiento where estado='ACT' order by codigo_movimiento asc";

	$result = mysqli_query($mysql,$sql);

	$resultado.= "<table width='100%'>";


	$resultado.="<tr>
				 <td bgcolor='#EFF2FB'></td>
				 <td bgcolor='#EFF2FB'>CODIGO</td>
				 <td bgcolor='#EFF2FB'>DESCRIPCION</td>
				 <td bgcolor='#EFF2FB'>TIPO</td>
				 <td bgcolor='#EFF2FB'>REFERENCIA</td>
				 <td bgcolor='#EFF2FB'>ESTADO</td>
				</tr>";

	while($row = mysqli_fetch_array($result))
	{

		$sql="select count(*) existe from guia_tipo_movimiento where codigo_movimiento='".$row['codigo_movimiento']."'";
		$result2 = mysqli_query($mysql,$sql);
		$array2  = mysqli_fetch_array($result2);

		if($array2['existe']==1){ $checked="checked";}else{ $checked="";}

		$resultado.= "<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>
					  <td><input type='checkbox' name='".$row['codigo_movimiento']."' value='1' $checked></td>
					  <td>".strtoupper($row['codigo_movimiento'])."</td>
					  <td>".strtoupper($row['descripcion'])."</td>
					  <td>".strtoupper($row['tipo'])."</td>
					  <td>".strtoupper($row['referencia'])."</td>
					  <td align='center'>".$row['estado']."</td>
					</tr>";
		$c++;
	}

	$resultado.="</table><br><br>";

	return $resultado;
}





function listarCircuitosAprobacion($buscar_circuito)
{
	include '../include/conexionSistema.php';

	$resultado="";

	$c="";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/listarCircuitosAprobacion.php')
	{
		$resultado.="<table width='10%'>
						<tr>
							<td><a href='agregarCircuitoAprobacion.php'><img src='../images/agregar.png' title='Agregar'></a></td>
							<td><a href='descargarListadoCircuitosAprobacion.php?buscar_circuito=".$buscar_circuito."'><img src='../images/descargar.png' title='Descargar'></a></td>
							<td><a href='#' onClick='print();'><img src='../images/imprimir.png' title='Imprimir'></a></td>
							<td><a href='listarCircuitosAprobacion.php'><img src='../images/recargar.png' title='Actualizar'></a></td>
						</tr>
					 </table>";
	}

      $sql="select *
			from workflow
			where id_workflow 		like '%$buscar_circuito%'
			or codigo_movimiento 	like '%$buscar_circuito%'
			or descripcion 			like '%$buscar_circuito%'
			or usuario_creacion		like '%$buscar_circuito%'
			or fecha_creacion		like '%$buscar_circuito%'
			or usuario_modifica		like '%$buscar_circuito%'
			or fecha_modifica		like '%$buscar_circuito%'
			order by id_workflow asc";

	$result = mysqli_query($mysql,$sql);

	$resultado.= "<table width='100%'>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorArticulo.php')
	{
		$resultado.="<tr>
						<td></td>
		                <td><form method='post' action='listarCircuitosAprobacion.php'><input type='text' name='buscar_circuito' value='$buscar_circuito' placeholder='Buscar...'></form></td>
					 </tr>";
	}

	$resultado.="<tr>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/listarCircuitosAprobacion.php'){$resultado.="<td bgcolor='#EFF2FB' ALIGN='CENTER'>EDITAR</td>";}

	$resultado.="<td bgcolor='#EFF2FB'>CODIGO MOVIMIENTO</td>
				 <td bgcolor='#EFF2FB'>DESCRIPCION</td>
				 <td bgcolor='#EFF2FB'>USUARIOS</td>
				 <td bgcolor='#EFF2FB'>NOTIFICA AL CORREO</td>
				 <td bgcolor='#EFF2FB'>ESTADO</td>
				</tr>";

	while($row = mysqli_fetch_array($result))
	{
		$usuarios=null;
		$sql		="select id_usuario from workflow_usuario where id_workflow='".$row['id_workflow']."'";
		$result2	=mysqli_query($mysql,$sql);
		while($row2 =mysqli_fetch_array($result2))
		{
			$usuarios.="&bull; ".strtoupper($row2['id_usuario'])."<br>";
		}

		$resultado.= "<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>";
		if($_SERVER['PHP_SELF']=='/suControl/sistema/listarCircuitosAprobacion.php'){$resultado.="<td align='center'><form method='post' action='fichaCircuitoAprobacion.php'><input type='hidden' name='id_workflow' value='".$row['id_workflow']."'><input type='image' src='../images/editar.png' title='Editar' name='editar' value='editar'></form></td>";}
	$resultado.= "<td>".strtoupper($row['codigo_movimiento'])."</td>
					  <td>".strtoupper($row['descripcion'])."</td>
					  <td>".$usuarios."</td>
					  <td>".strtoupper($row['notifica_correo'])."</td>
					  <td>".strtoupper($row['estado'])."</td>
					 </tr>";
		$c++;
	}

	$resultado.="</table><br><br>";
	$resultado.="Cantidad de Circuitos:$c";

	return $resultado;
}



function aprobacionesPendientes($estado,$registros)
{
	include '../include/conexionSistema.php';
	$resultado.="<script>
					function aprobarDocumento(numero_interno,nombre_tabla) {
						ancho = 600; // ancho de tu swf
						alto = 300;  // alto de tu swf
						y = screen.height/2 - alto/2;
						x = screen.width/2 - ancho/2;
						window.open('aprobarDocumento.php?numero_interno='+numero_interno+'&nombre_tabla='+nombre_tabla+'', 'w', 'width='+ancho+',height='+alto+',left='+x+',top='+y+',status=no,scrollbars=yes');
					}
					</script>";

	$resultado.="<h3>Aprobaciones Pendientes</h3>
					<table width='100%' border='1' rules='all'>
					<tr>
						<td bgcolor='#A9D0F5'>Numero Interno</td>
						<td bgcolor='#A9D0F5'>ID Empresa</td>
						<td bgcolor='#A9D0F5'>Descripcion Movimiento</td>
						<td bgcolor='#A9D0F5'>Fecha Ingreso</td>
						<td bgcolor='#A9D0F5'>Dias Transcurridos</td>
						<td bgcolor='#A9D0F5'>Digitador</td>
						<td bgcolor='#A9D0F5'>Proveedor</td>
						<td bgcolor='#A9D0F5'>Tipo Gasto</td>
						<td bgcolor='#A9D0F5'>Bodega</td>
						<td bgcolor='#A9D0F5'>Revision Supervisor</td>
						<td bgcolor='#A9D0F5'>Total</td>
						<td bgcolor='#A9D0F5'>Estado</td>
						<td bgcolor='#A9D0F5'>Usuarios Aprueban</td>
						<td bgcolor='#A9D0F5'></td>
						<td bgcolor='#A9D0F5'>Usuario Responde</td>
						<td bgcolor='#A9D0F5'>Fecha Respuesta</td>
						<td bgcolor='#A9D0F5'>Comentario</td>
					</tr>";


	if($estado=='TODOS'){$estado='';}
		/*
	       $sql="
		  select
				oc.codigo_movimiento,
				oc.numero_interno,
				oc.rut_empresa,
				oc.descripcion_movimiento,
				oc.fecha_digitacion,
				(select datediff(now(),oc.fecha_digitacion))dias_transcurridos,
				oc.digitador,
				oc.nombre_proveedor,
				oc.total,
				'orden_compra' nombre_tabla,
				oc.estado,
				(select id_usuario from workflow_documento where numero_interno=oc.numero_interno and referencia='orden_compra') usuario_respuesta,
				(select fecha_respuesta from workflow_documento where numero_interno=oc.numero_interno and referencia='orden_compra') fecha_respuesta,
				(select comentario from workflow_documento where numero_interno=oc.numero_interno and referencia='orden_compra') respuesta,
				oc.tipo_gasto,

				(select nombre from bodega where id_bodega in (select max(id_bodega_destino) from orden_compra_detalle where numero_interno=oc.numero_interno)) bodega_destino,
				oc.check_supervisor

				from orden_compra oc
				where oc.codigo_movimiento in (select codigo_movimiento from workflow where estado='ACT')
				and oc.total between
				(select monto_inicio from workflow_usuario where id_usuario ='".$_SESSION['id_usuario']."' and id_workflow in (select id_workflow from workflow where codigo_movimiento=oc.codigo_movimiento))
				and
				(select monto_termino from workflow_usuario where id_usuario='".$_SESSION['id_usuario']."' and id_workflow in (select id_workflow from workflow where codigo_movimiento=oc.codigo_movimiento))
				and oc.estado like '%$estado%'
	      UNION
		  select
				ot.numero_interno,
				ot.codigo_movimiento,
				'' rut_empresa,
				'ORDEN DE TRABAJO' descripcion_movimiento ,
				ot.fecha_digitacion,
				(select datediff(now(),ot.fecha_digitacion))dias_transcurridos,
				ot.digitador,
				ot.nombre_bodega,
				ot.total,
				'orden_trabajo' nombre_tabla,
				ot.estado,
				(select id_usuario from workflow_documento where numero_interno=ot.numero_interno and referencia='orden_trabajo') usuario_respuesta,
				(select fecha_respuesta from workflow_documento where numero_interno=ot.numero_interno and referencia='orden_trabajo') fecha_respuesta,
				(select comentario from workflow_documento where numero_interno=ot.numero_interno and referencia='orden_trabajo') respuesta,
				'' tipo_gasto,
				'' nombre_bodega,
				'' check_supervisor

				from orden_trabajo ot
				where ot.codigo_movimiento in (select codigo_movimiento from workflow where estado='ACT')
				and ot.estado like '%$estado%'
		  UNION
		  select
				g.codigo_movimiento,
				g.numero_interno,
				g.rut_proveedor,
				g.descripcion_movimiento,
				g.fecha_digitacion,
				(select datediff(now(),g.fecha_digitacion))dias_transcurridos,
				g.digitador,
				g.nombre_proveedor,
				g.total,
				'guia' nombre_tabla,
				g.estado,
				(select id_usuario from workflow_documento where numero_interno=g.numero_interno and referencia='guia') usuario_respuesta,
				(select fecha_respuesta from workflow_documento where numero_interno=g.numero_interno and referencia='guia') fecha_respuesta,
				(select comentario from workflow_documento where numero_interno=g.numero_interno and referencia='guia') respuesta ,
				'' tipo_gasto,
				'' nombre_bodega,
				'' check_supervisor
				from guia g
				where g.codigo_movimiento in (select codigo_movimiento from workflow where estado='ACT')
				and g.total
				between
				(select monto_inicio from workflow_usuario where id_usuario ='".$_SESSION['id_usuario']."' and id_workflow in (select id_workflow from workflow  where codigo_movimiento=g.codigo_movimiento))
				and
				(select monto_termino from workflow_usuario where id_usuario='".$_SESSION['id_usuario']."' and id_workflow in (select id_workflow from workflow where codigo_movimiento=g.codigo_movimiento))
				and g.estado like '%$estado%'
				order by dias_transcurridos desc
		  limit 0,$registros";

		  */

		    $sql="
		  select
				oc.codigo_movimiento,
				oc.numero_interno,
				oc.rut_empresa,
				oc.descripcion_movimiento,
				oc.fecha_digitacion,
				(select datediff(now(),oc.fecha_digitacion))dias_transcurridos,
				oc.digitador,
				oc.nombre_proveedor,
				oc.total,
				'orden_compra' nombre_tabla,
				oc.estado,
				(select id_usuario from workflow_documento where numero_interno=oc.numero_interno and referencia='orden_compra') usuario_respuesta,
				(select fecha_respuesta from workflow_documento where numero_interno=oc.numero_interno and referencia='orden_compra') fecha_respuesta,
				(select comentario from workflow_documento where numero_interno=oc.numero_interno and referencia='orden_compra') respuesta,
				oc.tipo_gasto,

				(select nombre from bodega where id_bodega in (select max(id_bodega_destino) from orden_compra_detalle where numero_interno=oc.numero_interno)) bodega_destino,
				oc.check_supervisor

				from orden_compra oc
				where oc.codigo_movimiento in (select codigo_movimiento from workflow where estado='ACT')
				and oc.total between
				(select monto_inicio from workflow_usuario where id_usuario ='".$_SESSION['id_usuario']."' and id_workflow in (select id_workflow from workflow where codigo_movimiento=oc.codigo_movimiento))
				and
				(select monto_termino from workflow_usuario where id_usuario='".$_SESSION['id_usuario']."' and id_workflow in (select id_workflow from workflow where codigo_movimiento=oc.codigo_movimiento))
				and oc.estado like '%$estado%'


		  limit 0,$registros";

	$result = mysqli_query($mysql,$sql);

	while($row = mysqli_fetch_array($result)){

		//lista los usuarios que aprueban el documento
		$usuarios_que_aprueban	="";
		$sql					="select id_usuario from workflow_usuario where id_workflow in (select id_workflow  from workflow where codigo_movimiento='".$row['codigo_movimiento']."') and ".$row['total']." between monto_inicio and monto_termino";
		$result2 				=mysqli_query($mysql,$sql);
		while($row2 = mysqli_fetch_array($result2)){
			$usuarios_que_aprueban.="&bull;".$row2['id_usuario']."<br>";
		}

		if($row['fecha_respuesta']<>''){
			$fecha_respuesta=date('d-m-Y H:i',strtotime($row['fecha_respuesta']));
			$responder		="<form method='post'>
									<input type='image' src='../images/deshacer.png'>
									<input type='hidden' name='numero_interno' value='".$row['numero_interno']."'>
									<input type='hidden' name='nombre_tabla' value='".$row['nombre_tabla']."'>
									<input type='hidden' name='estado' value='".$_REQUEST['estado']."'>
									<input type='hidden' name='registros' value='".$_REQUEST['registros']."'>
									<input type='hidden' name='deshacer' value='deshacer'>
							   </form>";
		}
		else{
			$fecha_respuesta=$row['fecha_respuesta'];
			$responder		="<a href='#' onClick='aprobarDocumento(".$row['numero_interno'].",`".$row['nombre_tabla']."`)'><input type='image' src='../images/aprobar.png'></a>";
		}


		if($_SERVER['PHP_SELF']=='/suControl/sistema/aprobacionesPendientes.php' or $_SERVER['PHP_SELF']=='/suControl/sistema/principal.php')
		{
			if($row['nombre_tabla']=='orden_compra')	{$aplicacion="ordenCompra.php";}
			if($row['nombre_tabla']=='guia')			{$aplicacion="guias.php";}
			if($row['check_supervisor']==1){ $check_supervisor="<input type='checkbox' checked disabled>";}else{ $check_supervisor= "<input type='checkbox' disabled>";}

			$resultado.="<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>
							<td><a href='#' onClick='document.location.href=`".$aplicacion."?numero_interno=".$row['numero_interno']."`;'>".$row['numero_interno']."</a></td>
							<td>".$row['rut_empresa']."</td>
							<td>".$row['descripcion_movimiento']."</td>
							<td>".date('d/m/Y',strtotime($row['fecha_digitacion']))."</td>
							<td align='center'><font color='red'><b>".$row['dias_transcurridos']."</b></font></td>
							<td>".$row['digitador']."</td>
							<td>".$row['nombre_proveedor']."</td>
							<td>".$row['tipo_gasto']."</td>
							<td>".$row['bodega_destino']."</td>
							<td align='center'>".$check_supervisor."</td>
							<td align='right'>$".number_format($row['total'],0,'','.')."</td>
							<td>".$row['estado']."</td>
							<td>$usuarios_que_aprueban</td>
							<td>$responder</td>
							<td>".strtoupper($row['usuario_respuesta'])."</td>
							<td>".$fecha_respuesta."</td>
							<td>".$row['respuesta']."</td>
						</tr>";
		}
		else
		{
			if($row['check_supervisor']==1){ $check_supervisor="SI";}else{ $check_supervisor= "NO";}

			//en excel muestra el formato orginal
			$resultado.="<tr>
							<td>".$row['numero_interno']."</td>
							<td>".$row['rut_empresa']."</td>
							<td>".$row['descripcion_movimiento']."</td>
							<td>".date('d/m/Y',strtotime($row['fecha_digitacion']))."</td>
							<td align='center'><font color='red'><b>".$row['dias_transcurridos']."</b></font></td>
							<td>".$row['digitador']."</td>
							<td>".$row['nombre_proveedor']."</td>
							<td>".$row['tipo_gasto']."</td>
							<td>".$row['bodega_destino']."</td>
							<td align='center'>".$check_supervisor."</td>
							<td align='right'>$".number_format($row['total'],0,'','.')."</td>
							<td>".$row['estado']."</td>
							<td>$usuarios_que_aprueban</td>
							<td>$responder</td>
							<td>".strtoupper($row['usuario_respuesta'])."</td>
							<td>".$fecha_respuesta."</td>
							<td>".$row['respuesta']."</td>
						</tr>";
		}
		$c++;
	}

	$resultado.="</table>";
	$resultado.="<br>Total Registros: $c";

	if($c==0){
		$resultado="<font color='green'><h3>No hay documentos pendientes por aprobar</h3></font>";
	}

	return $resultado;
}




function listarDefecto($buscar_defecto)
{
	include '../include/conexionSistema.php';

	$resultado="";

	$c="";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorDefectos.php')
	{
		$resultado.="<table width='10%'>
						<tr>
							<td><a href='agregarDefecto.php'><img src='../images/agregar.png' title='Agregar'></a></td>
							<td><a href='descargarListadoDefectos.php?buscar_defecto=".$_REQUEST['buscar_defecto']."'><img src='../images/descargar.png' title='Descargar'></a></td>
							<td><a href='#' onClick='print();'><img src='../images/imprimir.png' title='Imprimir'></a></td>
							<td><a href='mantenedorDefectos.php'><img src='../images/recargar.png' title='Actualizar'></a></td>
						</tr>
						</table>";
	}

    $sql		="select * from tipo_defecto where descripcion like '%$buscar_defecto%' or cuenta_contable like '%$buscar_defecto%' order by descripcion asc";

	$result 	= mysqli_query($mysql,$sql);

	$resultado	.= "<table width='100%'>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorDefectos.php')
	{
		$resultado.="<tr>
						<td></td>
		                <td><form method='post' action='mantenedorDefectos.php'><input type='text' name='buscar_defecto' value='' placeholder='Buscar...'></form></td>
					 </tr>";
	}

	$resultado.="<tr>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorDefectos.php'){$resultado.="<td bgcolor='#EFF2FB' ALIGN='CENTER'>EDITAR</td>";}

	$resultado.="<td bgcolor='#EFF2FB'>ID</td>
				 <td bgcolor='#EFF2FB'>CUENTA CONTABLE</td>
				 <td bgcolor='#EFF2FB'>DESCRIPCION</td>
				 <td bgcolor='#EFF2FB'>ESTADO</td>
				 </tr>";

	while($row = mysqli_fetch_array($result))
	{
		$resultado.= "<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>";
		if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorDefectos.php'){$resultado.="<td align='center'><form method='post' action='fichaDefecto.php'><input type='hidden' name='id_tipo_defecto' value='".$row['id_tipo_defecto']."'><input type='image' src='../images/editar.png' title='Editar' name='editar' value='editar'></form></td>";}
	$resultado.= "<td>".strtoupper($row['id_tipo_defecto'])."</td>
					  <td>".strtoupper($row['cuenta_contable'])."</td>
					  <td>".strtoupper($row['descripcion'])."</td>
					  <td>".strtoupper($row['estado'])."</td>
					</tr>";
		$c++;
	}

	$resultado.="</table><br><br>";
	$resultado.="Cantidad de Defectos:$c";

	return $resultado;
}

function listarMotivosRechazo($buscar_motivo_rechazo)
{
	include '../include/conexionSistema.php';

	$resultado="";

	$c="";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorMotivosRechazo.php')
	{
		$resultado.="<table width='10%'>
						<tr>
							<td><a href='agregarMotivoRechazo.php'><img src='../images/agregar.png' title='Agregar'></a></td>
							<td><a href='descargarListadoMotivosRechazo.php?buscar_defecto=".$_REQUEST['buscar_motivos_rechazo']."'><img src='../images/descargar.png' title='Descargar'></a></td>
							<td><a href='#' onClick='print();'><img src='../images/imprimir.png' title='Imprimir'></a></td>
							<td><a href='mantenedorMotivosRechazo.php'><img src='../images/recargar.png' title='Actualizar'></a></td>
						</tr>
						</table>";
	}

    $sql		="select * from motivos_rechazo where descripcion like '%$buscar_motivo_rechazo%' order by id_motivo_rechazo asc";

	$result 	= mysqli_query($mysql,$sql);

	$resultado	.= "<table width='100%'>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorMotivosRechazo.php')
	{
		$resultado.="<tr>
						<td></td>
		                <td><form method='post' action='mantenedorMotivosRechazo.php'><input type='text' name='buscar_motivos_rechazo' value='' placeholder='Buscar...'></form></td>
					 </tr>";
	}

	$resultado.="<tr>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorMotivosRechazo.php'){$resultado.="<td bgcolor='#EFF2FB' ALIGN='CENTER'>EDITAR</td>";}

	$resultado.="<td bgcolor='#EFF2FB'>ID</td>
				 <td bgcolor='#EFF2FB'>DESCRIPCION</td>
				 <td bgcolor='#EFF2FB'>ESTADO</td>
				 </tr>";

	while($row = mysqli_fetch_array($result))
	{
		$resultado.= "<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>";
		if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorMotivosRechazo.php'){
			$resultado.="<td align='center'>
						 <form method='post' action='fichaMotivoRechazo.php'>
						 <input type='hidden' name='id_motivo_rechazo' value='".$row['id_motivo_rechazo']."'>
						 <input type='image' src='../images/editar.png' title='Editar' name='editar' value='editar'>
						 </form>
						 </td>";
		}

		$resultado.= "<td>".strtoupper($row['id_motivo_rechazo'])."</td>
					  <td>".strtoupper($row['descripcion'])."</td>
					  <td>".strtoupper($row['estado'])."</td>
					</tr>";
		$c++;
	}

	$resultado.="</table><br><br>";
	$resultado.="Cantidad de Motivos de Rechazo:$c";

	return $resultado;
}

function listarPuntoVenta()
{
	include '../include/conexionSistema.php';

	$resultado="";

	$sql	=	"select count(*) tiene_dato from punto_venta where id_venta=0;";
	$result = 	mysqli_query($mysql,$sql);
	$array	= 	mysqli_fetch_array($result);

	if($array['tiene_dato']>0)
	{


		$resultado.="<form method='post'><table width='100%'>
						<tr>
							<td>
								<table style='font-size:17px' width='75%'>
									<tr>
										<td bgcolor='#D8D8D8'></td>
										<td bgcolor='#D8D8D8'>DESCRIPCION</td>
										<td bgcolor='#D8D8D8' align='right'>PRECIO</td>
									</tr>";

		$sql	=	"select * from punto_venta where id_venta='0' order by id_punto_venta asc";
		$result = 	mysqli_query($mysql,$sql);
		while($row = mysqli_fetch_array($result))
		{
			$resultado.="<tr>";

			if($_SERVER['PHP_SELF']=='/suControl/sistema/puntoVenta.php'){
					$resultado.="<td align='center'><img src='../images/eliminar.png' onClick='eliminarItem(".$row['id_punto_venta'].")'></td>";
			}

			$resultado.="<td>".$row['descripcion']."</td>
						 <td align='right'>".number_format($row['costo_unitario'],0,'','.')."</td>
						</tr>";

			$total +=$row['costo_unitario'];
		}


		$resultado.="<tr>
						<td colspan='3'>____________________________________________________________________________</td>
					 </tr>
					 <tr>
						<td>TOTAL:</td>
						<td align='right'>$".number_format($total,0,'','.')."</td>
					  </tr>
					  <tr>
						<td>PAGA CON:</td>
						<td align='right'><input type='number' id='paga_con' name='paga_con'  onKeyUp='restar(this.value,$total);' ></td>
					  </tr>
					  <tr>
						<td>VUELTO:</td>
						<td align='right'><span id='spTotal'></span></td>
					  </tr>


					  </table>
					  </td>
					  <td>
							<input type='submit' name='emitir_venta' value='EMITIR VENTA' style='width:200px; height:50px;font-size:20px;color: teal; background-color: #9FF781;'>
							<br>
							<input type='submit' name='cancelar_venta' value='CANCELAR VENTA' style='width:200px; height:50px;font-size:20px;color: teal; background-color: #F5A9A9;'>

					  </td>
					  </tr>
					  </table>
					  </form>";
	}

	return $resultado;
}




function reporteLogTransaccion($fecha_inicio,$fecha_termino,$id_usuario,$modulo,$descripcion)
{
	include '../include/conexionSistema.php';

	$resultado="";

	$resultado.="<table width='100%' rules='all' border='1'>
					<tr>
						<td>ID</td>
						<td>ID USUARIO</td>
						<td width='10%'>FECHA</td>
						<td>MODULO</td>
						<td>DESCRIPCION</td>
					</TR>";

	$sql	=	"select * from log_transaccion where fecha between '$fecha_inicio 00:00:00' and '$fecha_termino 23:59:59' and id_usuario like '%$id_usuario%' and modulo like '%$modulo%' and descripcion like '%$descripcion%' order by fecha desc";

	$result = 	mysqli_query($mysql,$sql);

	while($row = mysqli_fetch_array($result))
	{
		$resultado.="<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>
						<td>".$row['id_log_transaccion']."</td>
						<td>".$row['id_usuario']."</td>
						<td>".$row['fecha']."</td>
						<td>".$row['modulo']."</td>
						<td>".$row['descripcion']."</td>
					</TR>";
	}

	$resultado.="</table>";

	return $resultado;

}


function solicitudesPendientesTaller()
{
	include '../include/conexionSistema.php';

	$resultado	= "";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/taller.php')
	{
		$resultado.="<table width='10%'>
						<tr>
							<td><a href='descargarListadoSolicitudesTaller.php'><img src='../images/descargar.png' title='Descargar'></a></td>
							<td><a href='#' onClick='print();'><img src='../images/imprimir.png' title='Imprimir'></a></td>
							<td><a href='taller.php'><img src='../images/recargar.png' title='Actualizar'></a></td>
						</tr>
						</table>";
	}

	$resultado .= "<table width='100%'>
					<tr>
						<td BGCOLOR='#A9BCF5' align='center' WIDTH='1%'>DETALLE</td>
						<td BGCOLOR='#A9BCF5'>OT</td>
						<td BGCOLOR='#A9BCF5' WIDTH='1%'>FECHA<BR>SOLICITUD</td>
						<td BGCOLOR='#A9BCF5'>PRIORIDAD</td>
						<td BGCOLOR='#A9BCF5'>CANT. DIAS EN TALLER</td>
						<td BGCOLOR='#A9BCF5'>ESTADO</td>
						<td BGCOLOR='#A9BCF5'>TIPO MANTENCION</td>
						<td BGCOLOR='#A9BCF5'>PATENTE</td>
						<td BGCOLOR='#A9BCF5'>MARCA</td>
						<td BGCOLOR='#A9BCF5'>MODELO</td>
						<td BGCOLOR='#A9BCF5'>KILOMETRAJE</td>
						<td BGCOLOR='#A9BCF5'>BASE DE REPARACION</td>
						<td BGCOLOR='#A9BCF5' WIDTH='1%'>TIEMPO ESPERA</td>
					</tr>";

	$sql		= "select * from v_solicitudes_taller where id_bodega in (select id_bodega from usuario_bodega where id_usuario='".$_SESSION['id_usuario']."')";
	$result		= mysqli_query($mysql,$sql);
	while($row	= mysqli_fetch_array($result))
	{

		$fecha_entrada_taller 		=	$row['fecha_entrada_taller'];
		if($row['fecha_termino_taller']=='0000-00-00 00:00:00'){ $fecha_termino_taller	=	date('Y-m-d'); }else{ $fecha_termino_taller 	= 	$row['fecha_termino_taller']; }

		$sql						=	"select datediff('$fecha_termino_taller','$fecha_entrada_taller') cantidad_dias_en_taller";
		$result2					=	mysqli_query($mysql,$sql);
		$array2						= 	mysqli_fetch_array($result2);

		$cantidad_dias_en_taller	=	$array2['cantidad_dias_en_taller'];



		if($row['estado']=='PENDIENTE'){$formulario="generarOT.php";}else{$formulario="detalleOT.php";}
		if($row['estado']=='SOLICITUD REPUESTO'){$estado="<font color='red'>".$row['estado']."</font>";}else{$estado=$row['estado'];}


		$resultado.="<tr>";
		if($_SERVER['PHP_SELF']=='/suControl/sistema/taller.php'){$resultado.="<tr><td ALIGN='center'><a href='$formulario?numero_interno=".$row['numero_interno']."'><img src='../images/ver_en_sistema.jpg' width='25' height='25'></a></td>";}
		$resultado.="<td>".$row['numero_interno']."</td>
					 <td align='center'>".date('d-m-Y',strtotime($row['fecha_solicitud']))."</td>
					 <td align='center' bgcolor='".$row['color_prioridad']."'>".$row['prioridad']."</td>
					 <td align='center'>".$cantidad_dias_en_taller."</td>
					 <td>".$estado."</td>
					 <td>".$row['descripcion_movimiento']."</td>
					 <td><b>".$row['patente']."</b></td>
					 <td>".$row['marca']."</td>
					 <td>".$row['modelo']."</td>
					 <td align='right'>".number_format($row['kilometraje'],0,'','.')."</td>
					 <td>".$row['nombre_bodega']."</td>
					 <TD align='center'>".(tiempoEsperaTaller($row['numero_interno']))."</td>
					</tr>";
		$c++;
	}

	$resultado.="</table></form>";

	if($c>0)
	{
		$resultado.="<br>Cantidad de Registros:$c";
	}
	else
	{
		$resultado="<h3>No hay mantenciones pendientes</h3>";
	}

	return $resultado;
}



function reporteOC($numero_interno,$fecha_inicio,$fecha_termino,$rut_empresa,$digitador,$rut_proveedor,$nombre_proveedor,$contacto_proveedor,$estado,$razon_emision,$id_condicion_pago,$solicitado_por,$numero_factura,$fecha_factura,$factura_pagada,$comentario_factura,$folio_memo,$tipo_gasto,$id_bodega_destino){
	include '../include/conexionSistema.php';


	# Busca las OC y Bodega destino, luego las asocia a la tabla encabezado cuando id_bodega_destino esta vacio

	$sql	=	"select distinct ocd.numero_interno, ocd.id_bodega_destino from orden_compra_detalle ocd left join orden_compra oc on oc.numero_interno=ocd.numero_interno where oc.fecha_ingreso between '$fecha_inicio' and '$fecha_termino' and oc.id_bodega_destino=''";
	$result =	mysqli_query($mysql,$sql);
	while($row = mysqli_fetch_array($result)){

		# Actualiza el registro
		mysqli_query($mysql,"update orden_compra set id_bodega_destino='".$row['id_bodega_destino']."' where numero_interno='".$row['numero_interno']."';");

	}


	$resultado="";
	$resultado.="<table width='230%' rules='all' border='1' >
					<tr>
						<td bgcolor='#D8D8D8'>Numero Interno</td>
						<td bgcolor='#D8D8D8'>Fecha Ingreso</td>
						<td bgcolor='#D8D8D8'>Digitador</td>
						<td bgcolor='#D8D8D8'>Tipo Gasto</td>
						<td bgcolor='#D8D8D8'>Fecha Digitación</td>
						<td bgcolor='#D8D8D8'>Estado</td>
						<td bgcolor='#D8D8D8'>Bodega Destino</td>
						<td bgcolor='#D8D8D8'>Rut Empresa</td>
						<td bgcolor='#D8D8D8'>Nombre Empresa</td>
						<td bgcolor='#D8D8D8'>Descripcion Movimiento</td>
						<td bgcolor='#D8D8D8'>Rut Proveedor</td>
						<td bgcolor='#D8D8D8'>Nombre Proveedor</td>
						<td bgcolor='#D8D8D8'>Contacto Proveedor</td>
						<td bgcolor='#D8D8D8'>Numero Cotizacion</td>
						<td bgcolor='#D8D8D8'>Razon Emision</td>
						<td bgcolor='#D8D8D8'>Folio Memo</td>
						<td bgcolor='#D8D8D8'>Fecha Memo</td>
						<td bgcolor='#D8D8D8'>Neto</td>
						<td bgcolor='#D8D8D8'>Iva</td>
						<td bgcolor='#D8D8D8'>Total</td>
						<td bgcolor='#D8D8D8'>Condicion de Pago</td>
						<td bgcolor='#D8D8D8'>Solicitado Por</td>
						<td bgcolor='#D8D8D8'>Numero Factura</td>
						<td bgcolor='#D8D8D8'>Numero Boleta</td>
						<td bgcolor='#D8D8D8'>Boleta Honorario</td>
						<td bgcolor='#D8D8D8'>Observaciones</td>
						<td bgcolor='#D8D8D8'>Fecha Factura</td>
						<td bgcolor='#D8D8D8'>Factura Pagada</td>
						<td bgcolor='#D8D8D8'>Comentario Factura</td>
						<td bgcolor='#D8D8D8'>Usuario Aprobacion</td>
						<td bgcolor='#D8D8D8'>Fecha Aprobacion</td>
						<td bgcolor='#D8D8D8'>Comentario Aprobacion</td>
						<td bgcolor='#D8D8D8'>Comentario Anulacion</td>
						<td bgcolor='#D8D8D8'>Fecha Anulacion</td>
						<td bgcolor='#D8D8D8'>Usuario Anulacion</td>

					</tr>";

	  if($numero_interno	 	 )	{ $filtro_numero_interno 		 = "and oc.numero_interno 		= '$numero_interno'";		}
	  if($rut_empresa		 	 )	{ $filtro_rut_empresa 	 		 = "and oc.rut_empresa 			= '$rut_empresa'";			}
	  if($id_condicion_pago	 	 )	{ $filtro_id_condicion_pago		 = "and oc.id_condicion_pago	= '$id_condicion_pago'";	}
	  if($numero_factura	 	 )	{ $filtro_numero_factura		 = "and oc.numero_factura   	= '$numero_factura'";		}
	  if($fecha_factura	 	 	 )	{ $filtro_fecha_factura		 	 = "and oc.fecha_factura    	= '$fecha_factura'";		}
	  if($factura_pagada	 	 )	{ $filtro_factura_pagada	 	 = "and oc.factura_pagada   	= '$factura_pagada'";		}
	  if($id_bodega_destino	 	 )	{ $filtro_bodega_destino		 = "and oc.numero_interno		in (select numero_interno from orden_compra_detalle where id_bodega_destino='$id_bodega_destino')";	}


	    /*  $sql		=	"select oc.*,
					(select descripcion from condicion_pago where id_condicion_pago = oc.id_condicion_pago) condicion_pago,
					(select id_usuario from workflow_documento where numero_interno =oc.numero_interno and referencia='orden_compra') usuario_workflow,
					(select fecha_respuesta from workflow_documento where numero_interno =oc.numero_interno and referencia='orden_compra') fecha_workflow,
					(select comentario from workflow_documento where numero_interno =oc.numero_interno and referencia='orden_compra') respuesta_workflow,
					(select nombre from bodega where id_bodega in (select max(id_bodega_destino) from orden_compra_detalle where numero_interno=oc.numero_interno))bodega_destino
					from orden_compra oc
					where fecha_ingreso between '$fecha_inicio' and '$fecha_termino'
					$filtro_numero_interno
					$filtro_rut_empresa
					$filtro_id_condicion_pago
					$filtro_numero_factura
					$filtro_fecha_factura
					$filtro_factura_pagada
					$filtro_comentario_factura
					$filtro_bodega_destino
					and oc.digitador like '%$digitador%'
					and oc.rut_proveedor like '%$rut_proveedor%'
					and oc.nombre_proveedor like '%$nombre_proveedor%'
					and oc.contacto_proveedor like '%$contacto_proveedor%'
					and oc.estado like '%$estado%'
					and oc.razon_emision like '%$razon_emision%'
					and oc.solicitado_por like '%$solicitado_por%'
					and oc.comentario_factura   like '%$comentario_factura%'
					and oc.folio_memo			like '%$folio_memo%'
					and oc.tipo_gasto			like '%$tipo_gasto%'
					order by numero_interno desc";*/

	$sql="select
			oc.*,
			cp.descripcion condicion_pago,
			wf.id_usuario usuario_workflow,
			wf.fecha_respuesta fecha_workflow,
			wf.comentario respuesta_workflow,
			b.nombre bodega_destino
			from orden_compra oc
			left join condicion_pago cp on oc.id_condicion_pago=cp.id_condicion_pago
			left join workflow_documento wf on oc.numero_interno=wf.numero_interno and wf.referencia='orden_compra'
			left join bodega b on b.id_bodega=oc.id_bodega_destino
			where oc.fecha_ingreso  between '$fecha_inicio' and '$fecha_termino'
					$filtro_numero_interno
					$filtro_rut_empresa
					$filtro_id_condicion_pago
					$filtro_numero_factura
					$filtro_fecha_factura
					$filtro_factura_pagada
					$filtro_comentario_factura
					$filtro_bodega_destino
					and oc.digitador like '%$digitador%'
					and oc.rut_proveedor like '%$rut_proveedor%'
					and oc.nombre_proveedor like '%$nombre_proveedor%'
					and oc.contacto_proveedor like '%$contacto_proveedor%'
					and oc.estado like '%$estado%'
					and oc.razon_emision like '%$razon_emision%'
					and oc.solicitado_por like '%$solicitado_por%'
					and oc.comentario_factura   like '%$comentario_factura%'
					and oc.folio_memo			like '%$folio_memo%'
					and oc.tipo_gasto			like '%$tipo_gasto%'
			 order by numero_interno desc";

	$result 	= mysqli_query($mysql,$sql);

	while($row 	= mysqli_fetch_array($result)){

			$fecha_factura	="";
			if($row['fecha_factura'] )	{ $fecha_factura  = date('d/m/Y',strtotime($row['fecha_factura'])); }
			if($row['fecha_workflow'])	{ $fecha_workflow = date('d/m/Y',strtotime($row['fecha_workflow']));}

			$resultado.="<tr>
							<td>".$row['numero_interno']."</td>
							<td>".date('d/m/Y',strtotime($row['fecha_ingreso']))."</td>
							<td>".strtoupper($row['digitador'])."</td>
							<td>".$row['tipo_gasto']."</td>
							<td>".date('d/m/Y H:i',strtotime($row['fecha_digitacion']))."</td>
							<td><font color='red'>".$row['estado']."</font></td>
							<td>".$row['bodega_destino']."</td>
							<td>".$row['rut_empresa']."</td>
							<td>".$row['nombre_empresa']."</td>
							<td>".$row['codigo_movimiento']." - ".$row['descripcion_movimiento']."</td>
							<td>".$row['rut_proveedor']."</td>
							<td>".$row['nombre_proveedor']."</td>
							<td>".$row['contacto_proveedor']."</td>
							<td>".$row['numero_cotizacion']."</td>
							<td>".$row['razon_emision']."</td>
							<td>".$row['folio_memo']."</td>
							<td>".$row['fecha_memo']."</td>
							<td align='right'>$".number_format($row['neto'],0,'','.')."</td>
							<td align='right'>$".number_format($row['iva'],0,'','.')."</td>
							<td align='right'>$".number_format($row['total'],0,'','.')."</td>
							<td>".$row['condicion_pago']."</td>
							<td>".$row['solicitado_por']."</td>
							<td>".$row['numero_factura']."</td>
							<td>".$row['numero_boleta']."</td>
							<td>".$row['boleta_honorario']."</td>
							<td>".$row['observaciones']."</td>
							<td>".$fecha_factura."</td>
							<td>".$row['factura_pagada']."</td>
							<td>".$row['comentario_factura']."</td>
							<td>".$row['usuario_workflow']."</td>
							<td>".$row['fecha_workflow']."</td>
							<td>".$row['respuesta_workflow']."</td>
							<td>".$row['comentario_anulacion']."</td>
							<td>".$row['fecha_anulacion']."</td>
							<td>".$row['usuario_anulacion']."</td>
						</tr>";

			$neto 	+=$row['neto'];
			$iva 	+=$row['iva'];
			$total 	+=$row['total'];


	}


	$resultado.="<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td align='right'>$".number_format($neto,0,'','.')."</td>
							<td align='right'>$".number_format($iva,0,'','.')."</td>
							<td align='right'>$".number_format($total,0,'','.')."</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>";

	$resultado.="</table>";



	return $resultado;
}


function reporteAprobaciones($numero_interno,$fecha_inicio,$fecha_termino,$referencia,$estado,$id_usuario){

	include 	'../include/conexionSistema.php';

	$resultado	 =	"";
	$resultado 	.= 	"<table width='100%' rules='all' border='1' >
						<tr>
							<td bgcolor='#D8D8D8'>NUMERO INTERNO</td>
							<td bgcolor='#D8D8D8'>FECHA DOCUMENTO</td>
							<td bgcolor='#D8D8D8'>TIPO DOCUMENTO</td>
							<td bgcolor='#D8D8D8'>USUARIO RESPUESTA</td>
							<td bgcolor='#D8D8D8'>ESTADO</td>
							<td bgcolor='#D8D8D8'>FECHA RESPUESTA</td>
							<td bgcolor='#D8D8D8'>DIAS TRANSCURRIDOS</td>
							<td bgcolor='#D8D8D8'>COMENTARIO RESPUESTA</td>
						</tr>";

	if($numero_interno){$filtro_numero_interno = "and wd.numero_interno = '$numero_interno'";}

	$sql		 =	"select
						wd.referencia  nombre_tabla,
						wd.numero_interno,
						wd.id_usuario  usuario_respuesta,
						wd.estado estado_respuesta,
						wd.fecha_respuesta,
						wd.comentario comentario_respuesta,
						(select descripcion from tipo_movimiento where referencia = wd.referencia limit 0,1) referencia
					from workflow_documento wd
					where fecha_respuesta between '$fecha_inicio 00:00:00' and '$fecha_termino 23:59:59'
					$filtro_numero_interno
					and wd.referencia like '%$referencia%'
					and wd.estado 	  like '%$estado%'
					and wd.id_usuario like '%$id_usuario%'
					order by wd.fecha_respuesta desc";

	$result		 = 	mysqli_query($mysql,$sql);
	while($row 	 =	mysqli_fetch_array($result)){

		$sql	 			=	"select fecha_ingreso from ".$row['nombre_tabla']." where numero_interno ='".$row['numero_interno']."'";
		$result2 			=	mysqli_query($mysql,$sql);
		$array2  			=	mysqli_fetch_array($result2);
		$fecha_ingreso 		=	$array2['fecha_ingreso'];

		$sql				=	"select datediff('".$row['fecha_respuesta']."','$fecha_ingreso') dias_transcurridos;";
		$result2			=	mysqli_query($mysql,$sql);
		$array2				=	mysqli_fetch_array($result2);
		$dias_transcurridos	=	$array2['dias_transcurridos'];

		$resultado.="<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>
						<td>".$row['numero_interno']."</td>
						<td>".date('d/m/Y',strtotime($fecha_ingreso))."</td>
						<td>".$row['referencia']."</td>
						<td>".$row['usuario_respuesta']."</td>
						<td>".$row['estado_respuesta']."</td>
						<td>".date('d/m/Y H:i',strtotime($row['fecha_respuesta']))."</td>
						<td>".$dias_transcurridos."</td>
						<td>".$row['comentario_respuesta']."</td>
					</tr>";

		$dias_transcurridos=0;
	}

	$resultado.="</table>";
	return $resultado;
}



function reporteKardex($numero_interno,$id_articulo,$descripcion,$referencia,$tipo_movimiento,$fecha_inicio,$fecha_termino,$id_bodega){

	include '../include/conexionSistema.php';

	$resultado	=	"";
	$resultado .=	"<table width='100%' rules='all' border='1' >
						<tr>
							<td bgcolor='#D8D8D8'>ID</td>
							<td bgcolor='#D8D8D8'>NUMERO INTERNO</td>
							<td bgcolor='#D8D8D8'>REFERENCIA</td>
							<td bgcolor='#D8D8D8'>TIPO MOVIMIENTO</td>
							<td bgcolor='#D8D8D8'>ID BODEGA</td>
							<td bgcolor='#D8D8D8'>ID ARTICULO</td>
							<td bgcolor='#D8D8D8'>DESCRIPCION</td>
							<td bgcolor='#D8D8D8'>CANTIDAD</td>
							<td bgcolor='#D8D8D8'>FECHA MOVIMIENTO</td>
							<td bgcolor='#D8D8D8'>USUARIO MOVIMIENTO</td>
						</tr>";

	if($numero_interno){ $filtro_numero_interno = "and k.numero_interno = '$numero_interno'";}
	if($id_bodega)	   { $filtro_id_bodega		= "and k.id_bodega		= '$id_bodega'";	 }

	$sql		=	"select k.*
							from kardex k
						where k.fecha_movimiento between '$fecha_inicio 00:00:00' and '$fecha_termino 23:59:59'
						$filtro_numero_interno
						$filtro_id_bodega
						and k.id_articulo like '%$id_articulo%'
						and k.descripcion like '%$descripcion%'
						and k.referencia  like '%$referencia%'
						and k.tipo_movimiento like '%$tipo_movimiento%'
						order by k.fecha_movimiento asc";
	$result 	= 	mysqli_query($mysql,$sql);
	while($row 	= 	mysqli_fetch_array($result)){
		$resultado.="<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>
						<td>".$row['id']."</td>
						<td>".$row['numero_interno']."</td>
						<td>".$row['referencia']."</td>
						<td>".$row['tipo_movimiento']."</td>
						<td>".$row['id_bodega']."</td>
						<td>".$row['id_articulo']."</td>
						<td>".$row['descripcion']."</td>
						<td>".$row['cantidad']."</td>
						<td>".$row['fecha_movimiento']."</td>
						<td>".$row['usuario_movimiento']."</td>
					 </tr>";
	}

	$resultado.="</table>";
	return $resultado;
}



function reporteResumenPuntoVenta($fecha_inicio,$fecha_termino){

	include '../include/conexionSistema.php';

	$resultado	=	"";
	$resultado .=	"<table width='100%' rules='all' border='1' >
						<tr>
							<td bgcolor='#D8D8D8'>ID</td>
							<td bgcolor='#D8D8D8'>DESCRIPCION</td>
							<td bgcolor='#D8D8D8' align='center'>CANTIDAD</td>
							<td bgcolor='#D8D8D8' align='center'>TOTAL VENTA</td>
						</tr>";
	$sql	=	"select
					distinct pv.id_articulo,
					(select descripcion from articulo where id_articulo=pv.id_articulo) descripcion,
					(select count(*) from punto_venta where id_articulo=pv.id_articulo and fecha between '$fecha_inicio 00:00:00' and '$fecha_termino 23:59:59') cantidad,
					(select sum(costo_unitario) from punto_venta where id_articulo=pv.id_articulo and fecha between '$fecha_inicio 00:00:00' and '$fecha_termino 23:59:59') venta
					from punto_venta pv
					where pv.fecha between '$fecha_inicio 00:00:00' and '$fecha_termino 23:59:59'
					order by cantidad desc;";

	$result = 	mysqli_query($mysql,$sql);

	while($row = mysqli_fetch_array($result)){
		$resultado .=	"<tr>
							<td>".$row['id_articulo']."</td>
							<td>".$row['descripcion']."</td>
							<td align='center'>".$row['cantidad']."</td>
							<td align='right'>$".number_format($row['venta'],0,'','.')."</td>
						</tr>";
		$total +=	$row['venta'];
	}

	$resultado .=	"<tr>
						<td></td>
						<td></td>
						<td align='center'><B>TOTAL:</B></td>
						<td align='right'><B>".number_format($total,0,'','.')."</B></td>
					</tr>
					</table>";

	return $resultado;

}


function listarOperaciones($buscar_operacion){

	include '../include/conexionSistema.php';

	$resultado	="";
	$c			="";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorOperaciones.php')
	{
		$resultado.="<table width='10%'>
						<tr>
							<td><a href='agregarOperacion.php'><img src='../images/agregar.png' title='Agregar'></a></td>
							<td><a href='descargarListadoOperaciones.php?buscar_operacion=".$_REQUEST['buscar_operacion']."'>
								<img src='../images/descargar.png' title='Descargar'></a></td>
							<td><a href='#' onClick='print();'><img src='../images/imprimir.png' title='Imprimir'></a></td>
							<td><a href='mantenedorOperaciones.php'><img src='../images/recargar.png' title='Actualizar'></a></td>
						</tr>
						</table>";
	}

    $sql		="select * FROM operacion where id_operacion like '%$buscar_operacion%' or nombre_operacion like '%$buscar_operacion%' order by nombre_operacion asc";

	$result 	= mysqli_query($mysql,$sql);

	$resultado .= "<table width='100%'>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorOperaciones.php')
	{
		$resultado.="<tr>
						<td></td>
		                <td><form method='post' action='mantenedorOperaciones.php'>
							<input type='text' name='buscar_operacion' value='$buscar_operacion' placeholder='Buscar...'></form>
							</td>
					 </tr>";
	}

	$resultado.="<tr>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorOperaciones.php'){
		$resultado.="<td bgcolor='#EFF2FB'></td>";
		}

	$resultado.="<td bgcolor='#EFF2FB'>CODIGO OPERACION</td>
				 <td bgcolor='#EFF2FB'>NOMBRE</td>
				 <td bgcolor='#EFF2FB'>ESTADO</td>
				</tr>";

	while($row = mysqli_fetch_array($result))
	{

		$resultado.= "<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>";
		if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorOperaciones.php'){
			$resultado.="<td align='center'><form method='post' action='fichaOperacion.php'>
			<input type='hidden' name='id_operacion' value='".$row['id_operacion']."'>
			<input type='image' src='../images/editar.png' title='Editar' name='editar' value='editar'></form></td>";
			}
	$resultado.= "<td>".strtoupper($row['id_operacion'])."</td>
					  <td>".strtoupper($row['nombre_operacion'])."</td>
					  <td>".strtoupper($row['estado'])."</td>
					</tr>";
		$c++;
	}

	$resultado.="</table><br><br>";
	$resultado.="Cantidad de Operaciones:$c";

	return $resultado;

}




function listarProyectos($buscar_proyecto){

	include '../include/conexionSistema.php';

	$resultado	="";
	$c			="";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorProyecto.php')
	{
		$resultado.="<table width='10%'>
						<tr>
							<td><a href='agregarProyecto.php'><img src='../images/agregar.png' title='Agregar'></a></td>
							<td><a href='descargarListadoProyecto.php?buscar_proyecto=".$_REQUEST['buscar_proyecto']."'>
								<img src='../images/descargar.png' title='Descargar'></a></td>
							<td><a href='#' onClick='print();'><img src='../images/imprimir.png' title='Imprimir'></a></td>
							<td><a href='mantenedorProyecto.php'><img src='../images/recargar.png' title='Actualizar'></a></td>
						</tr>
						</table>";
	}

    $sql		="select * FROM proyecto where id_proyecto like '%$buscar_proyecto%' or nombre_proyecto like '%$buscar_proyecto%' order by nombre_proyecto asc";

	$result 	= mysqli_query($mysql,$sql);

	$resultado .= "<table width='150%'>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorProyecto.php')
	{
		$resultado.="<tr>
						<td></td>
		                <td><form method='post' action='mantenedorProyecto.php'>
							<input type='text' name='buscar_proyecto' value='$buscar_proyecto' placeholder='Buscar...'></form>
							</td>
					 </tr>";
	}

	$resultado.="<tr>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorProyecto.php'){
		$resultado.="<td bgcolor='#EFF2FB'></td>";
		}

	$resultado.="<td bgcolor='#EFF2FB'>CODIGO</td>
				 <td bgcolor='#EFF2FB'>NOMBRE</td>
				 <td bgcolor='#EFF2FB'>LINEA NEGOCIO</td>
				 <td bgcolor='#EFF2FB'>DIRECCION</td>
				 <td bgcolor='#EFF2FB'>PISO</td>
				 <td bgcolor='#EFF2FB'>OFICINA</td>
				 <td bgcolor='#EFF2FB'>EDIFICIO</td>
				 <td bgcolor='#EFF2FB'>COMUNA</td>
				 <td bgcolor='#EFF2FB'>TURNO</td>
				 <td bgcolor='#EFF2FB'>OBSERVACION</td>
				 <td bgcolor='#EFF2FB'>INICIO</td>
				 <td bgcolor='#EFF2FB'>TERMINO</td>
				 <td bgcolor='#EFF2FB'>FERIADO</td>
				 <td bgcolor='#EFF2FB'>DOTACION</td>
				</tr>";

	while($row = mysqli_fetch_array($result))
	{

		$resultado.= "<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>";
		if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorProyecto.php'){
			$resultado.="<td align='center'><form method='post' action='fichaProyecto.php'>
			<input type='hidden' name='id_proyecto' value='".$row['id_proyecto']."'>
			<input type='image' src='../images/editar.png' title='Editar' name='editar' value='editar'></form></td>";
			}
	$resultado.= "<td>".strtoupper($row['id_proyecto'])."</td>
					  <td>".strtoupper($row['nombre_proyecto'])."</td>
					  <td>".strtoupper($row['linea_negocio'])."</td>
					  <td>".strtoupper($row['direccion'])."</td>
					  <td>".strtoupper($row['piso'])."</td>
					  <td>".strtoupper($row['oficina'])."</td>
					  <td>".strtoupper($row['edificio'])."</td>
					  <td>".strtoupper($row['comuna'])."</td>
					  <td>".strtoupper($row['turno'])."</td>
					  <td>".strtoupper($row['observacion'])."</td>
					  <td>".strtoupper($row['inicio'])."</td>
					  <td>".strtoupper($row['termino'])."</td>
					  <td>".strtoupper($row['feriado'])."</td>
					  <td>".strtoupper($row['dotacion'])."</td>
					</tr>";
		$c++;
	}

	$resultado.="</table><br><br>";
	$resultado.="Cantidad de Proyectos:$c";

	return $resultado;

}




function reporteEntregasPendientesSinStock(){
	include '../include/conexionSistema.php';

	$resultado="<table width='100%' rules='all' border='1'>
					<tr>
						<td bgcolor='#D8D8D8'>Numero Interno</td>
						<td bgcolor='#D8D8D8'>Fecha Solicitud Repuesto</td>
						<td bgcolor='#D8D8D8'>Digitador</td>
						<td bgcolor='#D8D8D8'>Nombre Bodega</td>
						<td bgcolor='#D8D8D8'>Operacion</td>
						<td bgcolor='#D8D8D8'>Prioridad</td>
						<td bgcolor='#D8D8D8'>Patente</td>
						<td bgcolor='#D8D8D8'>Codigo</td>
						<td bgcolor='#D8D8D8'>Repuesto</td>
						<td bgcolor='#D8D8D8'>Cantidad</td>
						<td bgcolor='#D8D8D8'>Cantidad Disponible</td>
					</tr>";

	 $sql	=	"select
					ot.numero_interno,
					ot.fecha_solicitud_repuesto,
					ot.digitador,
					ot.nombre_bodega,
					(select nombre_operacion from operacion where id_operacion = ot.id_operacion)operacion,
					ot.id_prioridad,
					ot.patente,
					ota.id_articulo,
					ota.descripcion,
					ota.cantidad,
					(select cantidad from existencia where id_bodega=ot.id_bodega and id_articulo=ota.id_articulo) cantidad_disponible
					from orden_trabajo_articulo ota,orden_trabajo ot
					where ota.numero_interno = ot.numero_interno
					and ot.estado in ('PENDIENTE','PROCESO','SOLICITUD REPUESTO')
					order by ot.fecha_solicitud_repuesto asc;";

	 $result = 	mysqli_query($mysql,$sql);

	 while($row = mysqli_fetch_array($result)){

		 if($row['cantidad_disponible']==0){
			 $resultado.="<tr>
						<td>".$row['numero_interno']."</td>
						<td>".$row['fecha_solicitud_repuesto']."</td>
						<td>".$row['digitador']."</td>
						<td>".$row['nombre_bodega']."</td>
						<td>".$row['operacion']."</td>
						<td>".$row['id_prioridad']."</td>
						<td>".$row['patente']."</td>
						<td>".$row['id_articulo']."</td>
						<td>".$row['descripcion']."</td>
						<td align='center'>".number_format($row['cantidad'],0,'','.')."</td>
						<td align='center'>".number_format($row['cantidad_disponible'],0,'','.')."</td>
					</tr>";
		  $c++;
		 }


	 }
	$resultado.="</table><br><br>Cantidad de Registros:$c";

	return $resultado;
}



function listarArchivosAdjuntosAplicacion($carpeta,$numero_interno){

	include '../include/conexionSistema.php';
	$resultado		=	"<table width='80%'>";
	$sql			=	"select id_archivo_adjunto,nombre_archivo from archivo_adjunto where carpeta='$carpeta' and numero_interno='$numero_interno' and numero_interno<>''";
	$result 		= 	mysqli_query($mysql,$sql);
	while($row 		=	mysqli_fetch_array($result)){
        $resultado .= "<tr><td><a href='#' onClick='mostrarArchivoAdjunto(`$carpeta`,`".$row['nombre_archivo']."`)'>".$row['nombre_archivo']."</a></td><td><img src='../images/eliminar.png' onClick='eliminarArchivoAdjunto(`$carpeta`,`$numero_interno`,`".$row['id_archivo_adjunto']."`,`".$row['nombre_archivo']."`);'></td></tr>";
	}
	$resultado.="</table>";

	return $resultado;
}


function listarPautasMantencion($buscar_pauta_mantencion){

	include '../include/conexionSistema.php';


	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorPautasMantencion.php')
	{
		$resultado.="<table width='10%'>
						<tr>
							<td><a href='agregarPautaMantencion.php'><img src='../images/agregar.png' title='Agregar'></a></td>
							<td><a href='descargarListadoPautaMantencion.php?buscar_pauta_mantencion=".$_REQUEST['buscar_pauta_mantencion']."'><img src='../images/descargar.png' title='Descargar'></a></td>
							<td><a href='#' onClick='print();'><img src='../images/imprimir.png' title='Imprimir'></a></td>
							<td><a href='mantenedorPautasMantencion.php'><img src='../images/recargar.png' title='Actualizar'></a></td>
						</tr>
						</table>";
	}

	 $resultado.="<table width='100%'><tr>";
	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorPautasMantencion.php'){ $resultado.="<td bgcolor='#EFF2FB' align='center'>EDITAR</td>";}
	 $resultado.="	<td bgcolor='#EFF2FB'>ID PAUTA</td>
					<td bgcolor='#EFF2FB'>MARCA</td>
					<td bgcolor='#EFF2FB'>MODELO</td>
					<td bgcolor='#EFF2FB'>DESCRIPCION</td>
					<td bgcolor='#EFF2FB'>KILOMETRAJE</td>
					<td bgcolor='#EFF2FB'>MOTOR</td>
					</tr>";

	$sql		=	"select * from pauta_mantencion order by id_pauta_mantencion asc";
	$result		= 	mysqli_query($mysql,$sql);

	while($row	=	mysqli_fetch_array($result)){
		$resultado.="<tr>";
	        if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorPautasMantencion.php'){$resultado.="<td align='center'><img src='../images/editar.png' onClick='document.location.href=`fichaPautaMantencion.php?id_pauta_mantencion=".$row['id_pauta_mantencion']."`;'></td>";}
		$resultado.="<td>".$row['id_pauta_mantencion']."</td>
						<td>".$row['marca']."</td>
						<td>".$row['modelo']."</td>
						<td>".$row['descripcion']."</td>
						<td>".$row['kilometraje']."</td>
						<td>".$row['motor']."</td>

		</tr>";
	}

	$resultado.="</table>";

	return $resultado;
}


function listarDetallePautaMantencion($id_pauta_mantencion){

include '../include/conexionSistema.php';

$resultado.="<table width='100%'>
                <tr>";
if($_SERVER['PHP_SELF']=='/suControl/sistema/fichaPautaMantencion.php'){$resultado.="<td bgcolor='#DBDBDB' align='center'>EDITAR</td><td bgcolor='#DBDBDB' align='center'>ELIMINAR</td>";}
$resultado.="       <td bgcolor='#DBDBDB'>TIPO MANTENCION</td>
                    <td bgcolor='#DBDBDB'>DESCRIPCION</td>
                    <td bgcolor='#DBDBDB'>KILOMETRAJE</td>
                    <td bgcolor='#DBDBDB'>ID REPUESTO</td>
                    <td bgcolor='#DBDBDB'>REPUESTO</td>
                    <td bgcolor='#DBDBDB'>CANTIDAD UNIDADES</td>
                </tr>";

$sql        =   "select * from pauta_mantencion_detalle where id_pauta_mantencion='$id_pauta_mantencion' order by detalle_mantencion,tipo_mantencion asc";
$result     =   mysqli_query($mysql,$sql);

while($row  =   mysqli_fetch_array($result)){
$resultado.="<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>";

if($_SERVER['PHP_SELF']=='/suControl/sistema/fichaPautaMantencion.php'){$resultado.="<td align='center'><img src='../images/editar.png' onClick='modificarItem(".$row['id_pauta_mantencion_detalle'].");'></td>
                                                                                       <td align='center'><img src='../images/eliminar.png' onClick='eliminarItem(".$row['id_pauta_mantencion'].",".$row['id_pauta_mantencion_detalle'].");'></td>";}
$resultado.="       <td>".$row['tipo_mantencion']."</td>
                    <td>".$row['detalle_mantencion']."</td>
                    <td>".number_format($row['kilometraje'],0,'','.')."</td>
                    <td>".$row['id_articulo']."</td>
                    <td>".$row['descripcion_articulo']."</td>
                    <td>".$row['cantidad_unidades']."</td>
            </tr>";
}

$resultado.="</table>";

return $resultado;


}



function nombreDia($nombre_dia){

		if($nombre_dia=='Monday')	{$nombre_dia="Lunes";}
		if($nombre_dia=='Tuesday')	{$nombre_dia="Martes";}
		if($nombre_dia=='Wednesday'){$nombre_dia="Miercoles";}
		if($nombre_dia=='Thursday')	{$nombre_dia="Jueves";}
		if($nombre_dia=='Friday')	{$nombre_dia="Viernes";}
		if($nombre_dia=='Saturday')	{$nombre_dia="Sabado";}
		if($nombre_dia=='Sunday')	{$nombre_dia="Domingo";}

	return $nombre_dia;
}




function formularioRegistrarTurnos($id_proyecto,$ano,$mes,$linea_negocio,$rut_cliente){

	include '../include/conexionSistema.php';

	$sql	   		=	"select last_day('".$ano."-".$mes."-01') ultimo_dia;";
	$result	   		=	mysqli_query($mysql,$sql);
	$array	   		=	mysqli_fetch_array($result);
	$ultimo_dia		=	$array['ultimo_dia'];
	$cantidad_dias  =	date('d',strtotime($ultimo_dia));


	$sql			=	"select * from proyecto_sigla";
	$result 		= 	mysqli_query($mysql,$sql);
	while($row 		=	mysqli_fetch_array($result)){
		$id_sigla[$row['id_proyecto_sigla']]=$row['id_proyecto_sigla'];
		$descripcion_sigla[$row['id_proyecto_sigla']]=$row['descripcion_sigla'];
	}
	//lista todos los resultados para luego imprimirlos en el formulario.
	foreach($id_sigla as $sigla){$resultado2.="<option value='$sigla'>".$sigla." - ".$descripcion_sigla[$sigla]."</option>";}




	$resultado.="<form method='post'>";
	$resultado.="<table width='1400%' rules='all' border='1'><tr><td bgcolor='#848484'></td><td bgcolor='#848484'></td>";
	for($i=1;$i<=$cantidad_dias;$i++){
		$sql		="select DAYNAME('".$ano."-".$mes."-".$i."')nombre_dia;";
		$result		=mysqli_query($mysql,$sql);
		$array		=mysqli_fetch_array($result);
		$nombre_dia =nombreDia($array['nombre_dia']);
		$resultado .="<td colspan='6' align='center' bgcolor='#848484'><b><font color='white'>$nombre_dia $i de $mes $ano</font></b></td>";
		$array_dia[$i]=$nombre_dia;
	}
	$resultado .="<td colspan='6' align='center' bgcolor='#848484'><b><font color='white'>TOTALES</font></b></td>";
	$resultado.=	"</tr>";
	$resultado.=	"<tr><td bgcolor='#BDBDBD'>RUT</td><td bgcolor='#BDBDBD'>NOMBRE</td>";

	for($i=1;$i<=$cantidad_dias;$i++){
				$resultado.="<td align='center' bgcolor='#BDBDBD'>SIGLA</td>";
				$resultado.="<td align='center' bgcolor='#BDBDBD'>HN</td>";
				$resultado.="<td align='center' bgcolor='#BDBDBD'>HEN</td>";
				$resultado.="<td align='center' bgcolor='#BDBDBD'>HEE</td>";
				$resultado.="<td align='center' bgcolor='#BDBDBD'>HAB</td>";
				$resultado.="<td align='center' bgcolor='#BDBDBD'>BONO</td>";
	}

				$resultado.="<td align='center' bgcolor='#BDBDBD'>TOTAL HN</td>";
				$resultado.="<td align='center' bgcolor='#BDBDBD'>TOTAL HEN</td>";
				$resultado.="<td align='center' bgcolor='#BDBDBD'>TOTAL HEE</td>";
				$resultado.="<td align='center' bgcolor='#BDBDBD'>TOTAL HAB</td>";
				$resultado.="<td align='center' bgcolor='#BDBDBD'>TOTAL BONO</td>";

	$resultado.=	"</tr>";
	$sql	   =	"select rut_persona,nombre_persona,vinculo from proyecto_persona where id_proyecto ='$id_proyecto' order by nombre_persona asc";
	$result	   =	mysqli_query($mysql,$sql);
	while($row =	mysqli_fetch_array($result)){
		$resultado.="<tr>
						<td>".$row['rut_persona']."</td>
						<td>".$row['nombre_persona']."</td>";

		for($i=1;$i<=$cantidad_dias;$i++){

			$sql	=	"select * from proyecto_registro_turno where id_proyecto = '$id_proyecto' and fecha_turno ='".$ano."-".$mes."-".$i."' and rut_persona='".$row['rut_persona']."'";
			$result2=	mysqli_query($mysql,$sql);
			$array2 =	mysqli_fetch_array($result2);
			$hn		=	$array2['horas_trabajadas'];
			$hen	=	$array2['horas_extra_trabajadas'];
			$hee	=	$array2['horas_extra_especiales'];
			$hab	=	$array2['horas_extra_acuerdo'];
			$bono	=	$array2['bono_pagar'];



			if($hab=='00:00:00' or $hab=='' or $hab==null){ $estado_bono="disabled";}else{$estado_bono="enabled";}



			$resultado.="<td align='center'><select name='sigla_".$row['rut_persona']."_".$i."' style='width:50px'><option value=''>Seleccione...</option>";
			$resultado.="<option value='".$array2['sigla']."' selected>".$array2['sigla']." - ".$descripcion_sigla[$array2['sigla']]."</option>";
			$resultado.=$resultado2."</select></td>";
			$resultado.="<td align='center'><input type='time' 	name='hn_".$row['rut_persona']."_".$i."' 	value='$hn' 				style='width:60px'></td>";
			$resultado.="<td align='center'><input type='time' 	name='hen_".$row['rut_persona']."_".$i."'	value='$hen'				style='width:60px'></td>";
			$resultado.="<td align='center'><input type='time' 	name='hee_".$row['rut_persona']."_".$i."'	value='$hee'				style='width:60px'></td>";
			$resultado.="<td align='center'><input type='time' 	name='hab_".$row['rut_persona']."_".$i."'	value='$hab'				style='width:60px'></td>";
			$resultado.="<td align='center'><input type='number' 	name='bono_".$row['rut_persona']."_".$i."' 	value='$bono' $estado_bono	style='width:60px'></td>";

		}


			$sql	=	"SELECT
							SEC_TO_TIME(SUM(TIME_TO_SEC(horas_trabajadas))) total_horas_trabajadas,
							SEC_TO_TIME(SUM(TIME_TO_SEC(horas_extra_trabajadas))) total_horas_extra_trabajadas,
							SEC_TO_TIME(SUM(TIME_TO_SEC(horas_extra_especiales))) total_horas_extra_especiales,
							SEC_TO_TIME(SUM(TIME_TO_SEC(horas_extra_acuerdo))) total_horas_extra_acuerdo,
							SUM(bono_pagar) bono
							FROM proyecto_registro_turno
							where id_proyecto='$id_proyecto'
							and fecha_turno LIKE '%".$ano."-".$mes."%'
							and rut_persona='".$row['rut_persona']."'";
			$result2=	mysqli_query($mysql,$sql);
			$array2 =	mysqli_fetch_array($result2);


			$resultado.="<td align='center'>".$array2['total_horas_trabajadas']."</td>";
			$resultado.="<td align='center'>".$array2['total_horas_extra_trabajadas']."</td>";
			$resultado.="<td align='center'>".$array2['total_horas_extra_especiales']."</td>";
			$resultado.="<td align='center'>".$array2['total_horas_extra_acuerdo']."</td>";
			$resultado.="<td align='center'>$".number_format($array2['bono'],0,'','.')."</td>";

		$resultado.="</tr>";

	}



	//entrega los totales al final de la tabla
	$resultado.="<tr><td bgcolor='#F7BE81'></td><td bgcolor='#F7BE81'>TOTAL HH:</td>";

		for($i=1;$i<=$cantidad_dias;$i++){

			$sql	=	"SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(horas_trabajadas))) total_horas_trabajadas, SEC_TO_TIME(SUM(TIME_TO_SEC(horas_extra_trabajadas))) total_horas_extra_trabajadas, SEC_TO_TIME(SUM(TIME_TO_SEC(horas_extra_especiales))) total_horas_extra_especiales, SEC_TO_TIME(SUM(TIME_TO_SEC(horas_extra_acuerdo))) total_horas_extra_acuerdo, SUM(bono_pagar) bono FROM proyecto_registro_turno where id_proyecto='$id_proyecto' and fecha_turno='".$ano."-".$mes."-".$i."'";
			$result2=	mysqli_query($mysql,$sql);
			$array2 =	mysqli_fetch_array($result2);


			$resultado.="<td bgcolor='#F7BE81'></td>";
			$resultado.="<td bgcolor='#F7BE81' align='center'>".$array2['total_horas_trabajadas']."</td>";
			$resultado.="<td bgcolor='#F7BE81' align='center'>".$array2['total_horas_extra_trabajadas']."</td>";
			$resultado.="<td bgcolor='#F7BE81' align='center'>".$array2['total_horas_extra_especiales']."</td>";
			$resultado.="<td bgcolor='#F7BE81' align='center'>".$array2['total_horas_extra_acuerdo']."</td>";
			$resultado.="<td bgcolor='#F7BE81' align='center'>$".number_format($array2['bono'],0,'','.')."</td>";
		}

			$sql	=	"SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(horas_trabajadas))) total_horas_trabajadas,
								SEC_TO_TIME(SUM(TIME_TO_SEC(horas_extra_trabajadas))) total_horas_extra_trabajadas,
								SEC_TO_TIME(SUM(TIME_TO_SEC(horas_extra_especiales))) total_horas_extra_especiales,
								SEC_TO_TIME(SUM(TIME_TO_SEC(horas_extra_acuerdo))) total_horas_extra_acuerdo,
								SUM(bono_pagar) bono
								FROM proyecto_registro_turno
								where id_proyecto='$id_proyecto' and fecha_turno LIKE '%".$ano."-".$mes."%'";
			$result2=	mysqli_query($mysql,$sql);
			$array2 =	mysqli_fetch_array($result2);


			$resultado.="<td bgcolor='#F7BE81' align='center'>".$array2['total_horas_trabajadas']."</td>";
			$resultado.="<td bgcolor='#F7BE81' align='center'>".$array2['total_horas_extra_trabajadas']."</td>";
			$resultado.="<td bgcolor='#F7BE81' align='center'>".$array2['total_horas_extra_especiales']."</td>";
			$resultado.="<td bgcolor='#F7BE81' align='center'>".$array2['total_horas_extra_acuerdo']."</td>";
			$resultado.="<td bgcolor='#F7BE81' align='center'>$".number_format($array2['bono'],0,'','.')."</td>";
			$resultado.="</tr>";



			$resultado					.=	"<tr><td bgcolor='#F5D0A9'></td><td bgcolor='#F5D0A9'>HH REALIZADAS:</td>";
		for($i=1;$i<=$cantidad_dias;$i++){
			$sql						 =	"SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(horas_trabajadas + horas_extra_trabajadas +horas_extra_especiales + horas_extra_acuerdo))) horas_realizadas FROM proyecto_registro_turno where id_proyecto='$id_proyecto' and fecha_turno='".$ano."-".$mes."-".$i."'";
			$result2					 =	mysqli_query($mysql,$sql);
			$array2 					 =	mysqli_fetch_array($result2);
			$resultado	   		   		.=	"<td bgcolor='#F5D0A9'></td><td bgcolor='#F5D0A9' colspan='5' align='center'>".$array2['horas_realizadas']."</td>";
			$hh							 =	explode(":",$array2['horas_realizadas']);
			$horas_realizadas[$i]  		+= 	$hh[0];
			$total_horas_realizadas[$i]	+=	$hh[0];
		}
			$sql						 =	"SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(horas_trabajadas + horas_extra_trabajadas +horas_extra_especiales + horas_extra_acuerdo))) horas_realizadas FROM proyecto_registro_turno where id_proyecto='$id_proyecto' and fecha_turno like '%".$ano."-".$mes."%'";
			$result2					 =	mysqli_query($mysql,$sql);
			$array2 					 =	mysqli_fetch_array($result2);
			$hh_total_realizadas		 =	$array2['horas_realizadas'];
			$resultado				    .=	"<td colspan='5' bgcolor='#F5D0A9' align='center'>$hh_total_realizadas</td></tr>";


			$resultado					.=	"<tr><td bgcolor='#F6E3CE'></td><td bgcolor='#F6E3CE'>HH REQUERIDAS:</td>";
		for($i=1;$i<=$cantidad_dias;$i++){
			$hh		    				 = 	horasTotalesDiaProyecto($_REQUEST['id_proyecto'],$array_dia[$i]);
			$resultado 					.=	"<td bgcolor='#F6E3CE'></td><td bgcolor='#F6E3CE' colspan='5' align='center'>".($hh)."</td>";
			$hh							 =  explode(":",$hh);
			$horas_requeridas[$i]		+=	$hh[0];
			$total_horas_requeridas[$i] +=  $hh[0];
		}
			$resultado					.=	"<td colspan='5' bgcolor='#F6E3CE' align='center'>".array_sum($total_horas_requeridas)."</td></tr>";


			$resultado					.=	"<tr><td bgcolor='#F8ECE0'></td><td bgcolor='#F8ECE0'>CUMPLIMIENTO:</td>";
		for($i=1;$i<=$cantidad_dias;$i++){
			$resultado					.=	"<td bgcolor='#F8ECE0'></td><td bgcolor='#F8ECE0' colspan='5' align='center'>".number_format((($horas_realizadas[$i]/$horas_requeridas[$i])*100),1,',','')." %</td>";
		}
			$resultado					.=	"<td bgcolor='#F8ECE0' colspan='5' align='center'>".number_format((($hh_total_realizadas/array_sum($total_horas_requeridas))*100),1,',','')."%</td></tr>";



	$resultado.="</table>";
	$resultado.="<input type='hidden' name='buscar' value='Buscar'>";
	$resultado.="<input type='hidden' name='ano' value='$ano'>";
	$resultado.="<input type='hidden' name='mes' value='$mes'>";
	$resultado.="<input type='hidden' name='id_proyecto' value='$id_proyecto'>";
	$resultado.="<input type='hidden' name='linea_negocio' value='$linea_negocio'>";
	$resultado.="<input type='hidden' name='rut_cliente' value='$rut_cliente'>";

	$resultado.="<br><input type='submit' name='guardar' value='Guardar Datos'>";
	$resultado.="&nbsp;<input type='button' name='descargar' value='Descargar' onClick='document.location.href=`descargarRegistrarTurnos.php?id_proyecto=".$_REQUEST['id_proyecto']."&ano=".$_REQUEST['ano']."&mes=".$_REQUEST['mes']."&linea_negocio=".$_REQUEST['linea_negocio']."&rut_cliente=".$_REQUEST['rut_cliente']."`'>";
	$resultado.="&nbsp;<input type='button' name='informacion_proyecto' value='Informacion del Proyecto' onClick='informacionProyecto(`".$id_proyecto."`);'>";
	$resultado.="</form>";


	return $resultado;
}




function reporteRegistroTurnos($fecha_inicio,$fecha_termino,$id_proyecto,$paga_bono){
	include '../include/conexionSistema.php';

	$resultado.="<table width='200%'>
					<tr>
						<td bgcolor='#BDBDBD'>ID</td>
						<td bgcolor='#BDBDBD'>ID PROYECTO</td>
						<td bgcolor='#BDBDBD'>ID SUCURSAL</td>
						<td bgcolor='#BDBDBD'>LINEA NEGOCIO</td>
						<td bgcolor='#BDBDBD'>RUT PERSONA</td>
						<td bgcolor='#BDBDBD'>NOMBRE PERSONA</td>
						<td bgcolor='#BDBDBD'>VINCULO</td>
						<td bgcolor='#BDBDBD'>FECHA TURNO</td>
						<td bgcolor='#BDBDBD'>SIGLA</td>
						<td bgcolor='#BDBDBD'>HORAS TRABAJADAS</td>
						<td bgcolor='#BDBDBD'>HORAS EXTRA TRABAJADAS</td>
						<td bgcolor='#BDBDBD'>HORAS EXTRA ESPECIALES</td>
						<td bgcolor='#BDBDBD'>HORAS EXTRA ACUERDO</td>
						<td bgcolor='#BDBDBD'>BONO PAGAR</td>
						<td bgcolor='#BDBDBD'>USUARIO DIGITADOR</td>
						<td bgcolor='#BDBDBD'>FECHA DIGITACION</td>
						<td bgcolor='#BDBDBD'>USUARIO MODIFICA</td>
						<td bgcolor='#BDBDBD'>FECHA MODIFICA</td>
					</tr>";
	if($id_proyecto<>''){$filtro_id_proyecto="and prt.id_proyecto='$id_proyecto'";}
	if($paga_bono=='S')	{$filtro_paga_bono  ="and prt.bono_pagar>0";}
	$sql		=	"select prt.*,(select upper(nombre_persona) from proyecto_persona where rut_persona=prt.rut_persona limit 0,1) nombre_persona,
							(select linea_negocio from proyecto where id_proyecto=prt.id_proyecto) linea_negocio,
							(select id_sucursal from proyecto where id_proyecto=prt.id_proyecto) id_sucursal

							from proyecto_registro_turno prt where prt.fecha_turno between '$fecha_inicio' and '$fecha_termino' $filtro_id_proyecto $filtro_paga_bono order by fecha_turno asc";
	$result 	=	mysqli_query($mysql,$sql);
	while($row 	=	mysqli_fetch_array($result)){
		$resultado.="<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>
						<td>".$row['id_proyecto_registro_turno']."</td>
						<td>".$row['id_proyecto']."</td>
						<td>".$row['id_sucursal']."</td>
						<td>".$row['linea_negocio']."</td>
						<td>".$row['rut_persona']."</td>
						<td>".$row['nombre_persona']."</td>
						<td>".$row['vinculo']."</td>
						<td>".date('d-m-Y',strtotime($row['fecha_turno']))."</td>
						<td>".$row['sigla']."</td>
						<td>".$row['horas_trabajadas']."</td>
						<td>".$row['horas_extra_trabajadas']."</td>
						<td>".$row['horas_extra_especiales']."</td>
						<td>".$row['horas_extra_acuerdo']."</td>
						<td valign='right'>$".number_format($row['bono_pagar'],0,',','.')."</td>
						<td>".$row['usuario_digitador']."</td>
						<td>".$row['fecha_digitacion']."</td>
						<td>".$row['usuario_modifica']."</td>
						<td>".$row['fecha_modifica']."</td>
					</tr>";
	}

	$resultado.="</table>";

	return $resultado;
}


function vehiculo_archivo_adjunto($patente,$fecha_inicio,$fecha_termino,$usuario_digitacion,$descripcion,$id_bodega,$id_operacion){

	include '../include/conexionSistema.php';

	$resultado.="<script>
				 function verDocumento(nombre_archivo)
					{
						ancho 	= 800; // ancho de tu swf
						alto 	= 500;  // alto de tu swf
						y 		= screen.height/2 - alto/2;
						x 		= screen.width/2 - ancho/2;
						window.open('popUp_mostrarArchivoAdjuntoVehiculo.php?nombre_archivo='+nombre_archivo, 'w', 'width='+ancho+',height='+alto+',left='+x+',top='+y+',status=no,scrollbars=yes');
					}

				  function modificarDocumento(id,patente)
					{
						ancho 	= 800; // ancho de tu swf
						alto 	= 250;  // alto de tu swf
						y 		= screen.height/2 - alto/2;
						x 		= screen.width/2 - ancho/2;
						window.open('popUp_modificarArchivoAdjuntoVehiculo.php?id='+id+'&patente='+patente, 'w', 'width='+ancho+',height='+alto+',left='+x+',top='+y+',status=no,scrollbars=yes');
					}
				 </script>";

	$resultado.="<h3>Documentacion</h3>

					<table width='100%' rules='all' border='1'>
					<tr>";
	if($_SERVER['PHP_SELF']=='/suControl/sistema/fichaVehiculo.php'){
			$resultado.="<td align='center'>ELIMINAR</td>";
			$resultado.="<td align='center'>MODIFICAR</td>";
		}

	$resultado.=	   "<td bgcolor='#EFF2FB'>FECHA DIGITACION</td>
						<td bgcolor='#EFF2FB'>PATENTE</td>
						<td bgcolor='#EFF2FB'>BODEGA</td>
						<td bgcolor='#EFF2FB'>OPERACION</td>
						<td bgcolor='#EFF2FB'>USUARIO DIGITACION</td>
						<td bgcolor='#EFF2FB'>DESCRIPCION</td>
						<td bgcolor='#EFF2FB'>FECHA VENCIMIENTO</td>
						<td bgcolor='#EFF2FB'>DIAS RESTANTES</td>
						<td bgcolor='#EFF2FB'>ARCHIVO</td>
					</tr>";

	if($id_bodega		<>''){$filtro_id_bodega		="and v.patente in (select patente from vehiculo where  id_bodega	  ='$id_bodega'	)";}
	if($id_operacion	<>''){$filtro_id_operacion	="and v.patente	in (select patente from vehiculo where 	id_operacion  ='$id_operacion')";}



	if($fecha_inicio =='' and $fecha_termino==''){
		$sql	="select v.*,(select datediff(v.fecha_vencimiento,now())) dias_restantes,(select nombre from bodega where id_bodega in (select id_bodega from vehiculo where patente=v.patente))nombre_bodega,(select nombre_operacion from operacion where id_operacion in (select id_operacion from vehiculo where patente=v.patente))nombre_operacion from vehiculo_archivo_adjunto v where v.patente like '%$patente%' $filtro_id_bodega $filtro_id_operacion ";
	}else{
		$sql	="select v.*,(select datediff(v.fecha_vencimiento,now())) dias_restantes,(select nombre from bodega where id_bodega in (select id_bodega from vehiculo where patente=v.patente))nombre_bodega,(select nombre_operacion from operacion where id_operacion in (select id_operacion from vehiculo where patente=v.patente))nombre_operacion from vehiculo_archivo_adjunto v where v.patente like '%$patente%' and v.fecha_digitacion between '$fecha_inicio' and '$fecha_termino' and v.descripcion like '%$descripcion%' $filtro_id_bodega $filtro_id_operacion";
	}

	$result		=mysqli_query($mysql,$sql);
	while($row 	=mysqli_fetch_array($result)){

		if($row['dias_restantes']<=0 and $row['fecha_vencimiento']<>'0000-00-00'){$dias_restantes="<font color='red'><B>CITAR</B></font>";}else{$dias_restantes=$row['dias_restantes'];}
		if($row['fecha_vencimiento']=='' or $row['fecha_vencimiento']=='0000-00-00'){$fecha_vencimiento = "";}else{$fecha_vencimiento=date('d/m/Y',strtotime($row['fecha_vencimiento']));}

		$resultado.="<tr>";
		if($_SERVER['PHP_SELF']=='/suControl/sistema/fichaVehiculo.php'){
			$resultado.="<td align='center'><img src='../images/eliminar.png' onClick='eliminarDocumento(".$row['id'].",`".$row['patente']."`);'></td>";
			$resultado.="<td align='center'><img src='../images/editar.png' onClick='modificarDocumento(".$row['id'].",`".$row['patente']."`);'></td>";

		}
		$resultado.="	<td>".date('d/m/Y H:i',strtotime($row['fecha_digitacion']))."</td>
						<td>".$row['patente']."</td>
						<td>".$row['nombre_bodega']."</td>
						<td>".$row['nombre_operacion']."</td>
						<td>".$row['usuario_digitacion']."</td>
						<td>".$row['descripcion']."</td>
						<td>$fecha_vencimiento</td>
						<td>$dias_restantes</td>";

		if($row['nombre_archivo']<>''){
		$resultado.="<td><a href='#' onClick='verDocumento(`".$row['nombre_archivo']."`);'>Ver Archivo</a></td>";

		}else{
		$resultado.="<td></td>";
		}

		$resultado.="</tr>";
	}

	$resultado.="</table>";

	return $resultado;
}


function listarMarcas($buscar_marca){

	include '../include/conexionSistema.php';

	$resultado="";

	$c="";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorMarcas.php')
	{
		$resultado.="<table width='10%'>
						<tr>
							<td><a href='agregarMarca.php'><img src='../images/agregar.png' title='Agregar'></a></td>
							<td><a href='descargarListadoMarcas.php?buscar_marca=".$_REQUEST['buscar_marca']."'><img src='../images/descargar.png' title='Descargar'></a></td>
							<td><a href='#' onClick='print();'><img src='../images/imprimir.png' title='Imprimir'></a></td>
							<td><a href='mantenedorMarcas.php'><img src='../images/recargar.png' title='Actualizar'></a></td>
						</tr>
						</table>";
	}

    $sql="select * from vehiculo_marca where marca like '%$buscar_marca%' order by marca asc";

	$result = mysqli_query($mysql,$sql);

	$resultado.= "<table width='100%'>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorMarcas.php')
	{
		$resultado.="<tr>
						<td></td>
		                <td><form method='post' action='mantenedorMarcas.php'><input type='text' name='buscar_marca' value='$buscar_marca' placeholder='Buscar...'></form></td>
					 </tr>";
	}

	$resultado.="<tr>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorMarcas.php'){$resultado.="<td bgcolor='#EFF2FB' ALIGN='CENTER'>EDITAR</td>";}

	$resultado.="<td bgcolor='#EFF2FB'>ID</td>
				 <td bgcolor='#EFF2FB'>MARCA</td>
				 <td bgcolor='#EFF2FB'>ESTADO</td>
				 </tr>";

	while($row = mysqli_fetch_array($result))
	{
		$resultado.= "<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>";
		if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorMarcas.php'){$resultado.="<td align='center'><form method='post' action='fichaMarca.php'><input type='hidden' name='id_marca' value='".$row['id_marca']."'><input type='image' src='../images/editar.png' title='Editar' name='editar' value='editar'></form></td>";}
		$resultado.="<td>".strtoupper($row['id_marca'])."</td>";
		$resultado.="<td>".strtoupper($row['marca'])."</td>";
		$resultado.="<td>".$row['estado']."</td>";
		$resultado.="</tr>";
		$c++;
	}

	$resultado.="</table><br><br>";
	$resultado.="Cantidad de Marcas:$c";

	return $resultado;

}



function listarModelos($buscar_modelo){

	include '../include/conexionSistema.php';

	$resultado="";

	$c		  ="";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorModelos.php')
	{
		$resultado.="<table width='10%'>
						<tr>
							<td><a href='agregarModelo.php'><img src='../images/agregar.png' title='Agregar'></a></td>
							<td><a href='descargarListadoModelos.php?buscar_modelo=".$_REQUEST['buscar_modelo']."'><img src='../images/descargar.png' title='Descargar'></a></td>
							<td><a href='#' onClick='print();'><img src='../images/imprimir.png' title='Imprimir'></a></td>
							<td><a href='mantenedorModelos.php'><img src='../images/recargar.png' title='Actualizar'></a></td>
						</tr>
						</table>";
	}

    $sql	=" select  vm.id_modelo, vm.id_marca,  (select marca from vehiculo_marca where id_marca=vm.id_marca) marca,  vm.modelo,  vm.estado   from vehiculo_modelo vm   where vm.modelo like '%$buscar_modelo%' order by vm.id_modelo asc";

	$result = mysqli_query($mysql,$sql);

	$resultado.= "<table width='100%'>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorModelos.php')
	{
		$resultado.="<tr>
						<td></td>
		                <td><form method='post' action='mantenedorModelos.php'><input type='text' name='buscar_modelo' value='$buscar_modelo' placeholder='Buscar...'></form></td>
					 </tr>";
	}

	$resultado.="<tr>";

	if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorModelos.php'){$resultado.="<td bgcolor='#EFF2FB' ALIGN='CENTER'>EDITAR</td>";}

	$resultado.="<td bgcolor='#EFF2FB'>ID MODELO</td>
				 <td bgcolor='#EFF2FB'>ID MARCA</td>
				 <td bgcolor='#EFF2FB'>MARCA</td>
				 <td bgcolor='#EFF2FB'>MODELO</td>
				 <td bgcolor='#EFF2FB'>ESTADO</td>
			 </tr>";

	while($row = mysqli_fetch_array($result))
	{
		$resultado.= "<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>";
		if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorModelos.php'){$resultado.="<td align='center'><form method='post' action='fichaModelo.php'><input type='hidden' name='id_modelo' value='".$row['id_modelo']."'><input type='image' src='../images/editar.png' title='Editar' name='editar' value='editar'></form></td>";}
		$resultado.="<td>".strtoupper($row['id_modelo'])."</td>";
		$resultado.="<td>".strtoupper($row['id_marca'])."</td>";
		$resultado.="<td>".strtoupper($row['marca'])."</td>";
		$resultado.="<td>".$row['modelo']."</td>";
		$resultado.="<td>".$row['estado']."</td>";
		$resultado.="</tr>";
		$c++;
	}

	$resultado.="</table><br><br>";
	$resultado.="Cantidad de Modelos:$c";

	return $resultado;


}


function reporteCotizacion($id_cotizacion,$fecha_inicio,$fecha_termino,$para,$nombre_cliente,$usuario_digitacion,$descripcion){

	include '../include/conexionSistema.php';

	$resultado="";

	$resultado.="<table width='150%' RULES='ALL' BORDER='1'>
					<tr>
						<td BGCOLOR='#D8D8D8'>NUMERO INTERNO</td>
						<td BGCOLOR='#D8D8D8'>FECHA</td>
						<td BGCOLOR='#D8D8D8'>PARA</td>
						<td BGCOLOR='#D8D8D8'>NOMBRE CLIENTE</td>
						<td BGCOLOR='#D8D8D8'>CORREO</td>
						<td BGCOLOR='#D8D8D8'>TELEFONO</td>
						<td BGCOLOR='#D8D8D8'>VALIDEZ</td>
						<td BGCOLOR='#D8D8D8'>DESCUENTO %</td>
						<td BGCOLOR='#D8D8D8'>COMENTARIO</td>
						<td BGCOLOR='#D8D8D8'>SUBTOTAL NETO</td>
						<td BGCOLOR='#D8D8D8'>TOTAL NETO</td>
						<td BGCOLOR='#D8D8D8'>TOTAL DESCUENTO</td>
						<td BGCOLOR='#D8D8D8'>TOTAL IMPUESTO</td>
						<td BGCOLOR='#D8D8D8'>TOTAL</td>
						<td BGCOLOR='#D8D8D8'>USUARIO DIGITACION</td>
						<td BGCOLOR='#D8D8D8'>FECHA DIGITACION</td>
						<td BGCOLOR='#D8D8D8'>NUMERO INTERNO DETALLE</td>
						<td BGCOLOR='#D8D8D8'>DESCRIPCION</td>
						<td BGCOLOR='#D8D8D8'>NETO</td>
						<td BGCOLOR='#D8D8D8'>CANTIDAD</td>
						<td BGCOLOR='#D8D8D8'>TOTAL</td>
					</tr>";

	if($id_cotizacion<>''){
		$filtro_cotizacion="and cotizacion.id_cotizacion = '$id_cotizacion'";
	}

	$sql		=	"select *
						from cotizacion inner join cotizacion_detalle
						on cotizacion.id_cotizacion=cotizacion_detalle.id_cotizacion
						where
						cotizacion.fecha between '".$fecha_inicio."' and '".$fecha_termino."'
						$filtro_cotizacion
						and cotizacion.para like '%$para%'
						and cotizacion.nombre_cliente like '%$nombre_cliente%'
						and cotizacion.usuario_digitacion like '%$usuario_digitacion%'
						and cotizacion_detalle.descripcion like '%$descripcion%'
						order by cotizacion.fecha asc";

	$result 	=	mysqli_query($mysql,$sql);


	while($row 	= 	mysqli_fetch_array($result)){


		$resultado.="<tr>
						<td>".$row['id_cotizacion']."</td>
						<td>".date('d/m/Y',strtotime($row['fecha']))."</td>
						<td>".$row['para']."</td>
						<td>".$row['nombre_cliente']."</td>
						<td>".$row['correo']."</td>
						<td>".$row['telefono']."</td>
						<td>".$row['validez']."</td>
						<td>".$row['descuento']."</td>
						<td>".$row['comentario']."</td>
						<td align='right'>".number_format($row['subtotal_neto'],0,'','.')."</td>
						<td align='right'>".number_format($row['total_neto'],0,'','.')."</td>
						<td align='right'>".number_format($row['total_descuento'],0,'','.')."</td>
						<td align='right'>".number_format($row['total_impuesto'],0,'','.')."</td>
						<td align='right'>".number_format($row['total'],0,'','.')."</td>
						<td>".$row['usuario_digitacion']."</td>
						<td>".date('d/m/Y',strtotime($row['fecha_digitacion']))."</td>
						<td>".$row['id_cotizacion_detalle']."</td>
						<td>".$row['descripcion']."</td>
						<td align='right'>".number_format($row['neto'],0,'','.')."</td>
						<td>".$row['cantidad']."</td>
						<td align='right'>".number_format($row['total'],0,'','.')."</td>
					</tr>";

	}


	$resultado.="</table>";

	return $resultado;
}


function reporteOCDetalle($numero_interno,$fecha_inicio,$fecha_termino,$digitador,$rut_proveedor,$nombre_proveedor,$id_articulo,$descripcion,$id_bodega_destino){

	include '../include/conexionSistema.php';


	$resultado.="NUMERO INTERNO;RUT EMPRESA;NOMBRE EMPRESA;FECHA INGRESO;DIGITADOR;RUT PROVEEDOR;NOMBRE PROVEEDOR;CONTACTO PROVEEDOR;ESTADO;GUIA DESPACHO;NUMERO FACTURA;NUMERO BOLETA;BOLETA HONORARI;NOTA CREDITO;RAZON EMISION;PATENTE;NETO;IVA;TOTAL;FECHA DIGITACION;FECHA APROBACION;MES APROBACION;AÑO APROBACION;ID CONDICION PAGO;SOLICITADO POR;FECHA FACTURA;COMENTARIO FACTURA;OT;NUMERO COTIZACION;OBSERVACIONES;TIPO GASTO;ID DETALLE;ID ARTICULO;DESCRIPCION;ID BODEGA DESTINO;COSTO ITEM;CANTIDAD ITEM;IVA ITEM;TOTAL ITEM;COMENTARIO ANULACION;FECHA ANULACION;USUARIO ANULACION\n";


	$sql		="select
						orden_compra.*,
						orden_compra_detalle.id_detalle,
						orden_compra_detalle.id_articulo,
						orden_compra_detalle.descripcion_articulo,
						orden_compra_detalle.id_bodega_destino,
						orden_compra_detalle.costo costo_detalle,
						orden_compra_detalle.cantidad cantidad_detalle,
						orden_compra_detalle.iva iva_detalle,
						orden_compra_detalle.total total_detalle,
						(SELECT fecha_respuesta from workflow_documento where numero_interno=orden_compra.numero_interno and referencia='orden_compra')fecha_aprobacion
					from orden_compra inner join orden_compra_detalle
					on orden_compra.numero_interno = orden_compra_detalle.numero_interno
					where orden_compra.fecha_ingreso between '$fecha_inicio' and '$fecha_termino'
					and orden_compra.digitador like '%$digitador%'
					and orden_compra.numero_interno like '%$numero_interno%'
					and orden_compra.rut_proveedor like '%$rut_proveedor'
					and orden_compra.nombre_proveedor like '%$nombre_proveedor%'
					and orden_compra_detalle.id_articulo like '%$id_articulo%'
					and orden_compra_detalle.descripcion_articulo like '%$descripcion%'
					and orden_compra_detalle.id_bodega_destino like '%$id_bodega_destino%'
					order by orden_compra.numero_interno desc";

	$result 	=mysqli_query($mysql,$sql);
	while($row  =mysqli_fetch_array($result)){

		if($row['fecha_aprobacion']<>''){
				$mes_aprobacion=date('m',strtotime($row['fecha_aprobacion']));
				$ano_aprobacion=date('Y',strtotime($row['fecha_aprobacion']));
			}else{
				$mes_aprobacion="";
				$ano_aprobacion="";
			}

		$resultado.=$row['numero_interno'].";".$row['rut_empresa'].";".str_replace(";","",$row['nombre_empresa']).";".$row['fecha_ingreso'].";".$row['digitador'].";".$row['rut_proveedor'].";".str_replace(";","",$row['nombre_proveedor']).";".str_replace(";","",$row['contacto_proveedor']).";".$row['estado'].";".$row['guia_despacho'].";".$row['numero_factura'].";".$row['numero_boleta'].";".$row['boleta_honorario'].";".$row['nota_credito'].";".str_replace(";","",$row['razon_emision']).";".$row['patente'].";$".number_format($row['neto'],0,'','.').";$".number_format($row['iva'],0,'','.').";$".number_format($row['total'],0,'','.').";".$row['fecha_digitacion'].";".$row['fecha_aprobacion'].";".$mes_aprobacion.";".$ano_aprobacion.";".$row['id_condicion_pago'].";".$row['solicitado_por'].";".$row['fecha_factura'].";".str_replace(";","",$row['comentario_factura']).";".$row['ot'].";".$row['numero_cotizacion'].";".str_replace("\n","",str_replace("\r","",str_replace(";","",$row['observaciones']))).";".str_replace(";","",$row['tipo_gasto']).";".$row['id_detalle'].";".$row['id_articulo'].";".str_replace(";","",$row['descripcion_articulo']).";".$row['id_bodega_destino'].";$".number_format($row['costo_detalle'],0,'','.').";".$row['cantidad_detalle'].";$".number_format($row['iva_detalle'],0,'','.').";$".number_format($row['total_detalle'],0,'','.').";".str_replace("\n","",str_replace("\r","",str_replace(";","",$row['comentario_anulacion']))).";".$row['fecha_anulacion'].";".$row['usuario_anulacion']."\n";
	}


	return $resultado;
}



function procesoActualizarKilometrajes($id_bodega,$id_operacion){

	include '../include/conexionSistema.php';


	$resultado.="<form method='post'>
					<table width='50%' rules='all' border='1'>
					<tr>
						<td BGCOLOR='#D8D8D8'>PATENTE</td>
						<td BGCOLOR='#D8D8D8'>INGRESE KILOMETRAJE ACTUAL</td>
						<td BGCOLOR='#D8D8D8'>MARCA</td>
						<td BGCOLOR='#D8D8D8'>MODELO</td>
						<td BGCOLOR='#D8D8D8'>TIPO</td>

					</tr>";

	$sql	=	"select
					v.patente,
					(select marca from vehiculo_marca where id_marca=v.id_marca)marca,
					(select modelo from vehiculo_modelo where id_modelo=v.id_modelo) modelo,
					(select tipo from vehiculo_tipo where id_tipo=v.id_tipo) tipo
				from vehiculo v
				where v.id_bodega='$id_bodega'
				and v.id_operacion='$id_operacion'
				order by v.patente asc";
	$result = 	mysqli_query($mysql,$sql);
	while($row = mysqli_fetch_array($result)){
		$resultado.= "<tr>
						<td>".$row['patente']."</td>
						<td><input type='number' name='kilometraje_".$row['patente']."' value='' STYLE='WIDTH:100PX'></td>
						<td>".$row['marca']."</td>
						<td>".$row['modelo']."</td>
						<td>".$row['tipo']."</td>
					</tr>";
		$c++;
	}

	$resultado.="</table>
					<br>
					<input type='hidden' name='id_bodega' value='$id_bodega'>
					<input type='hidden' name='id_operacion' value='$id_operacion'>

					<input type='submit' name='guardar' value='Guardar'>
					<br>
					<br>Cantidad de Registros:$c
				 <br>
				 </form>";

	return $resultado;
}


function reporteProximasMantenciones($patente,$bodega,$operacion,$tipo_vehiculo,$citar_desde){

	include '../include/conexionSistema.php';

	$resultado	="";
	$c			="";

    $sql		="select v.*,
					(select nombre 			 from bodega 			where id_bodega 	= v.id_bodega) nombre_bodega,
					(select nombre_operacion from operacion 		where id_operacion	= v.id_operacion) nombre_operacion,
					(select marca  			 from vehiculo_marca 	where id_marca  	= v.id_marca ) marca,
					(select modelo 			 from vehiculo_modelo 	where id_modelo 	= v.id_modelo) modelo,
					(select tipo   			 from vehiculo_tipo 	where id_tipo   	= v.id_tipo  ) tipo,
					(select kilometraje 	 from orden_trabajo 	WHERE patente		= V.PATENTE and codigo_movimiento='004' order by numero_interno desc limit 0,1)   kilometraje_ultima_mantencion_preventiva,
					(select numero_interno 	 from orden_trabajo 	WHERE patente		= V.PATENTE and codigo_movimiento='004' order by numero_interno desc limit 0,1)   numero_interno_ultima_mantencion_preventiva
					from vehiculo v
					where
					v.patente 						like '%$patente%'
					and v.id_bodega   	in (select id_bodega 	from bodega 		 where nombre 			like '%$bodega%')
					and v.id_operacion 	in (select id_operacion from operacion 		 where nombre_operacion like '%$operacion%')
					and v.id_tipo 		in (select id_tipo from vehiculo_tipo where tipo like '%$tipo_vehiculo%')
					and v.estado		in ('ACT')
					order by v.patente asc";

	$result 	= mysqli_query($mysql,$sql);
	$resultado .= "<table width='100%'>
						<tr>
						 <td bgcolor='#EFF2FB'>PATENTE</td>
						 <td bgcolor='#EFF2FB'>MARCA</td>
						 <td bgcolor='#EFF2FB'>MODELO</td>
						 <td bgcolor='#EFF2FB'>TIPO</td>
						 <td bgcolor='#EFF2FB'>VIN</td>
						 <td bgcolor='#EFF2FB'>BODEGA</td>
						 <td bgcolor='#EFF2FB'>OPERACION</td>
						 <td bgcolor='#EFF2FB'>AÑO</td>
						 <td bgcolor='#EFF2FB'>K.M ACTUAL</td>
						 <td bgcolor='#EFF2FB'>K.M PROX. MANTENCION</td>
						 <td bgcolor='#EFF2FB'>K.M RESTANTE PROX. MANTENCION</td>

						 <td bgcolor='#EFF2FB'>KILOMETRAJE ULTIMA MANTENCION PREVENTIVA</td>
						 <td bgcolor='#EFF2FB'>OT ULTIMA MANTENCION PREVENTIVA</td>
						  <td bgcolor='#EFF2FB'>FECHA ULTIMO INGRESO A TALLER</td>
						</tr>";

	while($row = mysqli_fetch_array($result))
	{

		$kilometraje_restante_proxima_mantencion=number_format($row['kilometraje_proxima_mantencion']-$row['kilometraje_actual'],0,'','.');

		if($kilometraje_restante_proxima_mantencion<=0){
			$kilometraje_restante_proxima_mantencion="<font color='red'><b>CITAR</b></font>";
		}

		if(($row['kilometraje_proxima_mantencion']-$row['kilometraje_actual'])<=$citar_desde){



		if($row['fecha_ultima_mantencion']<>'0000-00-00'){$fecha_ultima_mantencion=date('d-m-Y',strtotime($row['fecha_ultima_mantencion']));}else{$fecha_ultima_mantencion="";}
		$resultado.= "<tr onmouseover='this.style.background=`#CECEF6`' onmouseout='this.style.background=`white`'>";
		if($_SERVER['PHP_SELF']=='/suControl/sistema/mantenedorVehiculo.php'){$resultado.="<td align='center'><form method='post' action='fichaVehiculo.php'><input type='hidden' name='patente' value='".$row['patente']."'><input type='image' src='../images/editar.png' title='Editar' name='editar' value='editar'></form></td>";}
	$resultado.= "<td>".strtoupper($row['patente'])."</td>
					  <td>".strtoupper($row['marca'])."</td>
					  <td>".strtoupper($row['modelo'])."</td>
					  <td>".strtoupper($row['tipo'])."</td>
					  <td>".strtoupper($row['vin'])."</td>
					  <td>".strtoupper($row['nombre_bodega'])."</td>
					  <td>".strtoupper($row['nombre_operacion'])."</td>
					  <td>".strtoupper($row['ano'])."</td>
					  <td align='right'>".number_format($row['kilometraje_actual'],0,'','.')."</td>
					  <td align='right'>".number_format($row['kilometraje_proxima_mantencion'],0,'','.')."</td>
					  <td align='right'>".$kilometraje_restante_proxima_mantencion."</td>
					  <td align='right'>".number_format($row['kilometraje_ultima_mantencion_preventiva'],0,'','.')."</td>
					  <td align='CENTER'>".strtoupper($row['numero_interno_ultima_mantencion_preventiva'])."</td>
					  <td align='center'>".$fecha_ultima_mantencion."</td>
					 </tr>";
		$c++;

		}
	}

	$resultado.="</table><br>";
	$resultado.="Cantidad de Vehiculos:$c";

	return $resultado;

}
?>