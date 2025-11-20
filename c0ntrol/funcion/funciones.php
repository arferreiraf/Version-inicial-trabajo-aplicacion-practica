<?php
//valida el login del sistema

function logTransaccion1($id_usuario,$descripcion)
{
	if($id_usuario<>'')
	{
		include 'include/conexionSistema.php';
		//guarda un log por cada movimiento
		$sql			=	"insert into log_transaccion (id_log_transaccion,id_usuario,fecha,modulo,descripcion) values (null,'$id_usuario',now(),'".str_replace('/suControl/','',$_SERVER['PHP_SELF'])."','$descripcion');";
		$result 		= 	mysqli_query($mysql,$sql);
	}
}

function logTransaccion2($id_usuario,$descripcion)
{
	if($id_usuario<>'')
	{
		include '../include/conexionSistema.php';
		//guarda un log por cada movimiento
		$sql		=	"insert into log_transaccion (id_log_transaccion,id_usuario,fecha,modulo,descripcion) values (null,'$id_usuario',now(),'".str_replace('/suControl/sistema/','',$_SERVER['PHP_SELF'])."','$descripcion');";
		$result 	= 	mysqli_query($mysql,$sql);
	}
}


function cantidadDiasMes($ano,$mes){

	$sql	   		=	"select last_day('".$ano."-".$mes."-01') ultimo_dia;";
	$result	   		=	mysqli_query($mysql,$sql);
	$array	   		=	mysqli_fetch_array($result);
	$ultimo_dia		=	$array['ultimo_dia'];
	return 				date('d',strtotime($ultimo_dia));

}

function registrarKardex($numero_interno,$id_articulo,$cantidad,$tipo_movimiento,$referencia,$id_bodega){

		include '../include/conexionSistema.php';
		//guarda un registro en el kardex por cada movimiento
		$sql		=	"insert into kardex (id,numero_interno,id_articulo,descripcion,cantidad,fecha_movimiento,usuario_movimiento,tipo_movimiento,referencia,id_bodega)values(null,'$numero_interno','$id_articulo',(select descripcion from articulo where id_articulo='$id_articulo'),'$cantidad',now(),'".$_SESSION['id_usuario']."','$tipo_movimiento','$referencia','$id_bodega')";
		mysqli_query($mysql,$sql);

}


function cambiaPasswordUsuario($id_usuario,$password_actual,$nueva_password_1,$nueva_password_2){
	 include '../include/conexionSistema.php';

	 //pregunta si el usuario y la password son correctas
	$sql		=	"select count(*) existe from usuario where id_usuario ='$id_usuario' and password_usuario=SHA1('$password_actual')";
	$result 		= mysqli_query($mysql,$sql);
	$array 			= mysqli_fetch_array($result);
	$existe 	= 	$array[0];


	 if($existe==1){
		//si la password es correcta
		if($nueva_password_1==$nueva_password_2){
			//si la nueva password coincide, actualiza la password
			$sql="update usuario set password_usuario=SHA1('$nueva_password_1') where id_usuario='$id_usuario'";
			if(mysqli_query($mysql,$sql)){
				$resultado="<table width='100%'><tr><td bgcolor='green'><b><font color='white'>Se ha modificado la password exitosamente!</font></b></td></tr></table>";
				logTransaccion2($_SESSION['id_usuario'],"Cambia su password");
			}else{
				$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>Ocurrio un error al modificar la password</font></b></td></tr></table>";
			}
		}else{
				$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>La nueva password deben coincidir</font></b></td></tr></table>";
		}
	 }else{
		$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>La password actual es incorrecta</font></b></td></tr></table>";
	 }

	 return $resultado;
}

function validaUsuario($id_empresa,$id_usuario,$password_usuario)
{
	 //se conecta a la base de datos segun la empresa id_empresa

	 include 'include/conexion.php';

	 // trae los datos segun la empresa

	 $sql="select
				*,
				datediff(now(),caduca) caduco_dias,
				(select valor from parametros_generales where codigo='ip_publica') ip_publica
			from usuario where id_usuario='$id_usuario' and password_usuario=sha1('$password_usuario')";

	 $result = mysqli_query($mysql,$sql);

	 $array = mysqli_fetch_array($result);

	 if($array[0]<>'')
	 {
		 //si el login es correcto consulta si el usuario esta activo

		 if($array['estado']=='ACT')
		 {
			//si el usuario esta activo, consulta hace cuantos dias caduco el usuario

			if($array['caduco_dias']<=0)
			{

				//si los parametros anteriores estan OK el programa inicia la sesion

				session_start();

				 $_SESSION['id_empresa']			= $id_empresa;

				 $_SESSION['id_usuario'] 			= $id_usuario;

				 $_SESSION['nombre']				= $array['nombre'];

				 $_SESSION['password_usuario']		= $password_usuario;

				 //actualiza el ultimo ingreso al sistema
				 $sql="update usuario set ultimo_ingreso=now() where id_usuario='$id_usuario'";
				 $result = mysqli_query($mysql,$sql);

				 $resultado = 1;
			}
			else
			{
				//envia el mensaje que la cuenta caduco
				echo "<script>alert('La cuenta de usuario caduco hace ".$array['caduco_dias']." dias. Contacte al administrador del sistema.');</script>";
				$resultado=0;
			}
		 }
		 else
		 {
			//envia el mensaje que la cuenta esta inactiva
			echo "<script>alert('La cuenta de usuario se encuentra inactiva');</script>";
			$resultado=0;
		 }
	 }
	 else
	 {
		//envia el mensaje que el usuario y la password son incorrectos
		echo "<script>alert('Usuario y password incorrectos');document.location.href='index.php';</script>";
		 $resultado = 0;
	 }

	 //retorna el resutado
	 return $resultado;

}


function mostrarOpcionDashBoard($id_usuario,$nombre_permiso)
{
	include 	'../include/conexionSistema.php';
	$sql		=	"select count(id_permiso) existe FROM permiso_usuario where id_usuario='$id_usuario' and id_permiso in (SELECT id_permiso FROM permiso WHERE nombre_permiso='$nombre_permiso')";
	$result 	= 	mysqli_query($mysql,$sql);
	$array  	= 	mysqli_fetch_array($result);
	return 		$array[0];
}


//formulario agregar usuario
function agregarUsuario($id_usuario,$nombre,$correo,$caduca,$password_1,$password_2)
{
	include '../include/conexionSistema.php';

	$sql="select count(id_usuario) existe from usuario where id_usuario='$id_usuario'";

	$result = mysqli_query($mysql,$sql);

	$array = mysqli_fetch_array($result);

	if($array['existe']==1)
	{
		//si el usuario existe en la base de datos indica mensaje de error
		$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>El usuario ya existe en la base de datos!</font></b></td></tr></table>";
	}
	else
	{
		if($password_1==$password_2)
		{

			//si la password coincide guarda el registro en la base de datos

			 $sql="INSERT INTO `usuario` (`id_usuario`, `password_usuario`, `nombre`, `fecha_creacion`, `fecha_modificacion`, `usuario_modifica`, `correo`, `ultimo_cambio_password`, `caduca`,`estado`) VALUES ('".strtoupper($id_usuario)."', sha1('$password_1'), '".strtoupper($nombre)."', now(), '', '', '".strtoupper($correo)."', now(), '$caduca','ACT');";

			if(mysqli_query($mysql,$sql))
			{
				//si se guarda el registro en la base de datos, emite un mensaje OK

				logTransaccion2($_SESSION['id_usuario'],"Agrega usuario $id_usuario $nombre $correo $caduca");

				include '../funcion/reporte.php';

				$resultado="<table width='100%'><tr><td bgcolor='green'><font color='white'><b>Se ha creado el usuario exitosamente</b></font></td></tr></table>";

			}
			else
			{
				//si no se guarda el registro en la base de datos emite un mensaje de error

				$resultado="<table width='100%'><tr><td bgcolor='red'><font color='white'><b>Hubo un error al crear el usuario!</b></font></td></tr></table>";
			}
		}
		else
		{
			//si la password no coincide envia un mensaje de error

			$resultado="<table width='100%'><tr><td bgcolor='red'><font color='white'><b>Las password no coinciden!</b></font></td></tr></table>";
		}
	}

	//envia el resultado a la pantalla del usuario
	return $resultado;
}


//formulario modifica usuario
function modificaUsuario($id_usuario,$nombre,$correo,$password_usuario,$caduca,$estado,$firma,$firmar_digitalmente)
{
	include '../include/conexionSistema.php';

	$sql	="select count(id_usuario) existe from usuario where id_usuario='$id_usuario'";

	$result = mysqli_query($mysql,$sql);

	$array 	= mysqli_fetch_array($result);

	if($array['existe']==0)
	{
		//si el usuario existe en la base de datos indica mensaje de error

		$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>El usuario no existe en la base de datos!</font></b></td></tr></table>";

	}
	else
	{
		if($password_usuario<>'')
		{
			//si la password lleva datos, actualiza los datos y la password

			 $sql="update usuario set firmar_digitalmente='$firmar_digitalmente',firma_digital='$firma',nombre='$nombre',password_usuario=sha1('$password_usuario'),ultimo_cambio_password=now(),fecha_modificacion=now(),usuario_modifica='".strtoupper($_SESSION['id_usuario'])."', correo='".strtoupper($correo)."', caduca='$caduca',estado='$estado' where id_usuario='$id_usuario'";

		}
		else
		{
			//si el campo password va vacio solo actualiza los datos y no la password

			 $sql="update usuario set firmar_digitalmente='$firmar_digitalmente',firma_digital='$firma',nombre='$nombre',fecha_modificacion=now(),usuario_modifica='".strtoupper($_SESSION['id_usuario'])."', correo='".strtoupper($correo)."', caduca='$caduca',estado='$estado' where id_usuario='$id_usuario'";

		}

		if(mysqli_query($mysql,$sql))
		{
			logTransaccion2($_SESSION['id_usuario'],"Modifica usuario $id_usuario $nombre $correo $caduca $firma");

			$resultado="<table width='100%'><tr><td bgcolor='green'><b><font color='white'>Se han modificado los datos exitosamente!</font></b></td></tr></table>";
		}
		else
		{
			$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>Hubo un error al modificar el registro!</font></b></td></tr></table>";
		}
	}

	return $resultado;
}




//formulario agregar articulo
function agregarArticulo($id_articulo,$descripcion,$costo_promedio,$stock_critico,$unidad_medida,$cuenta_contable,$familia,$tipo)
{
	include '../include/conexionSistema.php';

	$sql="select count(id_articulo) existe from articulo where id_articulo='$id_articulo'";

	$result = mysqli_query($mysql,$sql);

	$array = mysqli_fetch_array($result);

	if($array['existe']==1)
	{
		//si el articulo existe en la base de datos indica mensaje de error
		$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>El articulo ya existe en la base de datos!</font></b></td></tr></table>";
	}
	else
	{

		//si la password coincide guarda el registro en la base de datos

		$sql="INSERT INTO `articulo` (`id_articulo`, `descripcion`, `estado`, `costo_promedio`, `stock_critico`, `usuario_creacion`, `fecha_creacion`, `usuario_modifica`, `fecha_modificacion`,`unidad_medida`,`cuenta_contable`,`familia`,`tipo`)
				VALUES ('$id_articulo', '".(str_replace("'", "\'",(strtoupper($descripcion))))."', 'ACT', '$costo_promedio', '$stock_critico', '".$_SESSION['id_usuario']."', now(), '', '','$unidad_medida','$cuenta_contable','$familia','$tipo');";

		if(mysqli_query($mysql,$sql))
		{
			logTransaccion2($_SESSION['id_usuario'],"Agrega articulo id_articulo:$id_articulo decripcion:$descripcion costo_promedio:$costo_promedio stock_critico:$stock_critico unidad_medida:$unidad_medida familia:$familia tipo:$tipo");

			//si se guarda el registro en la base de datos, emite un mensaje OK

			$resultado="<table width='100%'><tr><td bgcolor='green'><font color='white'><b>Se ha creado el articulo exitosamente</b></font></td></tr></table>";

		}
		else
		{
			//si no se guarda el registro en la base de datos emite un mensaje de error

			$resultado="<table width='100%'><tr><td bgcolor='red'><font color='white'><b>Hubo un error al crear el articulo!</b></font></td></tr></table>";
		}

	}

	//envia el resultado a la pantalla del usuario
	return $resultado;
}




function modificaArticulo($id_articulo,$descripcion,$costo_promedio,$stock_critico,$estado,$unidad_medida,$cuenta_contable,$familia,$tipo)
{
	include '../include/conexionSistema.php';

	$sql="select count(id_articulo) existe from articulo where id_articulo='$id_articulo'";

	$result = mysqli_query($mysql,$sql);

	$array = mysqli_fetch_array($result);

	if($array['existe']==0)
	{
		//si el articulo existe en la base de datos indica mensaje de error

		$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>El articulo no existe en la base de datos!</font></b></td></tr></table>";

	}
	else
	{

		$sql="update articulo set 	descripcion='$descripcion',	fecha_modificacion=now(),	usuario_modifica='".$_SESSION['id_usuario']."',	costo_promedio='$costo_promedio',	stock_critico='$stock_critico',	estado='$estado', unidad_medida='$unidad_medida',cuenta_contable='$cuenta_contable',familia='$familia',tipo='$tipo'	where id_articulo='$id_articulo'";

		if(mysqli_query($mysql,$sql))
		{
			logTransaccion2($_SESSION['id_usuario'],"Modifica articulo id_articulo:$id_articulo descripcion:$descripcion costo_promedio:$costo_promedio stock_critico:$stock_critico estado:$estado unidad_medida:$unidad_medida cuenta_contable:$cuenta_contable familia:$familia tipo:$tipo");

			$resultado="<table width='100%'><tr><td bgcolor='green'><b><font color='white'>Se han modificado los datos exitosamente!</font></b></td></tr></table>";
		}
		else
		{
			$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>Hubo un error al modificar el registro!</font></b></td></tr></table>";
		}
	}

	return $resultado;
}



function eliminarArticulo($id_articulo)
{

	include '../include/conexionSistema.php';

	$sql="select count(id_articulo) existe from articulo where id_articulo='".$id_articulo."'";

	$result = mysqli_query($mysql,$sql);

	$array = mysqli_fetch_array($result);

	if($array['existe']>0)
	 {
	    $sql="delete from articulo where id_articulo = '".$id_articulo."'";

		if(mysqli_query($mysql,$sql))
		{
			logTransaccion2($_SESSION['id_usuario'],"Elimina articulo $id_articulo");

			$resultado.= "<script>alert('Se ha eliminado el articulo exitosamente');document.location.href='mantenedorArticulo.php';</script>";
		}
		else
		{
			$resultado.= "<script>alert('No se pudo eliminar el articulo. Intente nuevamente o contacte al administrador');document.location.href='fichaArticulo.php?id_articulo=".$id_articulo."';</script>";
		}
	}


	//al eliminar el articulo tambien elimina los codigos de barra asociados al producto
	$sql="delete from articulo_codigo_barra where id_articulo='$id_articulo'";
	$result = mysqli_query($mysql,$sql);

	return $resultado;
}


function agregarCodigoBarra($codigo_barra,$id_articulo)
{
	include '../include/conexionSistema.php';

	//si el codigo de barra existe para otro articulo el sistema no debe insertarlo
	$sql="select count(*) existe from articulo_codigo_barra where codigo_barra='$codigo_barra'";

	$result = mysqli_query($mysql,$sql);

	$array = mysqli_fetch_array($result);

	if($array['existe']<>0)
	{
		$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>El codigo de barra ya se encuentra en la base de datos</font></b></td></tr></table>";
	}
	else
	{
		//inserta el registro
		 $sql="insert into articulo_codigo_barra values('$codigo_barra','$id_articulo');";

		if(mysqli_query($mysql,$sql))
		{
			logTransaccion2($_SESSION['id_usuario'],"Agrega codigo de barra  $id_articulo $codigo_barra");

			$resultado="<table width='100%'><tr><td bgcolor='green'><b><font color='white'>Se ha generado el registro exitosamente!</font></b></td></tr></table>";

		}
		else
		{
			$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>Hubo un error guardar el registro!</font></b></td></tr></table>";
		}
	}

	//actualiza el articulo e informa quien modifico el registro y la fecha
	$sql="update articulo set usuario_modifica='".$_SESSION['id_usuario']."',fecha_modificacion=now() where id_articulo='$id_articulo'";
	$result = mysqli_query($mysql,$sql);

	return $resultado;
}

function eliminarCodigoBarra($codigo_barra,$id_articulo)
{
	include '../include/conexionSistema.php';

	$sql="delete from articulo_codigo_barra where id_articulo='$id_articulo' and codigo_barra='$codigo_barra'";

	if(mysqli_query($mysql,$sql))
	{
			$resultado="<table width='100%'><tr><td bgcolor='green'><b><font color='white'>Se ha eliminado el registro exitosamente!</font></b></td></tr></table>";

	}
	else
	{
			$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>Hubo un error eliminar el registro!</font></b></td></tr></table>";
	}

	//actualiza el articulo e informa el ultimo usuario que modifico y la fecha

	$sql="update articulo set usuario_modifica='".$_SESSION['id_usuario']."',fecha_modificacion=now() where id_articulo='$id_articulo'";
	$result = mysqli_query($mysql,$sql);

	return $resultado;

}



function agregarBodega($id_bodega,$nombre,$direccion,$telefono,$usuario_a_cargo,$correo_usuario_a_cargo,$orden)
{
	include '../include/conexionSistema.php';

	//consulta si la bodega existe en la base de datos
	$sql="select count(*) existe from bodega where id_bodega='$id_bodega'";

	$result = mysqli_query($mysql,$sql);

	$array = mysqli_fetch_array($result);

	if($array['existe']==1)
	{
		$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>La bodega $id_bodega ya se encuentra en la base de datos!</font></b></td></tr></table>";
	}
	else
	{
		//si la bodega no se encuentra en la base de datos, inserta el registro
		$sql="insert into bodega (id_bodega,nombre,direccion,telefono,usuario_a_cargo,correo_usuario_a_cargo,estado,usuario_creacion,fecha_creacion,orden)values ('".strtoupper($id_bodega)."','".strtoupper($nombre)."','".strtoupper($direccion)."','$telefono','".strtoupper($usuario_a_cargo)."','".strtoupper($correo_usuario_a_cargo)."','ACT','".strtoupper($_SESSION['id_usuario'])."',now(),'$orden');";

		//consulta si se ejecuta la consulta sql
		if(mysqli_query($mysql,$sql))
		{
			$resultado="<table width='100%'><tr><td bgcolor='green'><b><font color='white'>Se ha registrado la bodega exitosamente!</font></b></td></tr></table>";
			logTransaccion2($_SESSION['id_usuario'],"Inserta id_bodega:$id_bodega nombre:$nombre direccion:$direccion usuario_a_cargo:$usuario_a_cargo correo_usuario_a_cargo:$correo_usuario_a_cargo orden:$orden");
		}
		else
		{
			$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>Hubo un error al guardar el registro!</font></b></td></tr></table>";
		}
	}

	return $resultado;
}


function agregarEmpresa($rut_empresa,$nombre_empresa,$direccion_empresa,$ciudad_empresa,$telefono,$fax)
{
	include '../include/conexionSistema.php';

	//consulta si el registro se encuentra en la base de datos
	$sql	="select count(*) existe from empresa where rut_empresa='$rut_empresa'";
	$result = mysqli_query($mysql,$sql);
	$array 	= mysqli_fetch_array($result);

	if($array['existe']>0)
	{
		$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>La empresa $rut_empresa ya se encuentra en la base de datos!</font></b></td></tr></table>";
	}
	else
	{
		//si el registro no se encuentra en la base de datos
		$sql="insert into empresa (rut_empresa,nombre_empresa,direccion_empresa,ciudad_empresa,telefono,fax,estado)values ('$rut_empresa','$nombre_empresa','$direccion_empresa','$ciudad_empresa','$telefono','$fax','ACT');";

		//consulta si se ejecuta la consulta sql
		if(mysqli_query($mysql,$sql))
		{
			$resultado="<table width='100%'><tr><td bgcolor='green'><b><font color='white'>Se ha creado el registro exitosamente!</font></b></td></tr></table>";
			logTransaccion2($_SESSION['id_usuario'],"Inserta Registro Empresa $rut_empresa $nombre_empresa $direccion_empresa $ciudad_empresa $telefono $fax");
		}
		else
		{
			$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>Hubo un error al guardar el registro!</font></b></td></tr></table>";
		}
	}

	return $resultado;
}



//formulario modifica bodega
function modificaBodega($id_bodega,$nombre,$direccion,$telefono,$usuario_a_cargo,$correo_usuario_a_cargo,$estado,$reparacion,$orden,$id_bodega_nuevo)
{
	include '../include/conexionSistema.php';

	$sql		=	"select count(id_bodega) existe from bodega where id_bodega='$id_bodega'";
	$result 	= 	mysqli_query($mysql,$sql);
	$array 		= 	mysqli_fetch_array($result);

	if($array['existe']==0)
	{
		//si la bodega existe en la base de datos indica mensaje de error

		$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>La bodega no existe en la base de datos!</font></b></td></tr></table>";

	}
	else
	{
		$sql="update bodega set id_bodega='".$id_bodega_nuevo."', nombre='".strtoupper($nombre)."',direccion='".strtoupper($direccion)."',telefono='".$telefono."', usuario_a_cargo='".strtoupper($usuario_a_cargo)."', correo_usuario_a_cargo='".strtoupper($correo_usuario_a_cargo)."',estado='$estado',usuario_modifica='".strtoupper($_SESSION['id_usuario'])."',fecha_modifica=now(),reparacion='$reparacion',orden='$orden' where id_bodega='".strtoupper($id_bodega)."'";

		if(mysqli_query($mysql,$sql))
		{
			$resultado="<table width='100%'><tr><td bgcolor='green'><b><font color='white'>Se han modificado los datos exitosamente!</font></b></td></tr></table>";
			logTransaccion2($_SESSION['id_usuario'],"Modifica id_bodega:$id_bodega id_bodega_nuevo:$id_bodega_nuevo nombre:$nombre direccion:$direccion usuario_a_cargo:$usuario_a_cargo correo_usuario_a_cargo:$correo_usuario_a_cargo telefono:$telefono reparacion:$reparacion orden:$orden");

			$sql	=	"update bodega_presupuesto set id_bodega='$id_bodega_nuevo' where id_bodega='$id_bodega'";
			mysqli_query($mysql,$sql);

			$sql	=	"update mecanico_bodega set id_bodega='$id_bodega_nuevo' where id_bodega='$id_bodega'";
			mysqli_query($mysql,$sql);

			$sql	=	"update usuario_bodega set id_bodega='$id_bodega_nuevo' where id_bodega='$id_bodega'";
			mysqli_query($mysql,$sql);

		}
		else
		{
			$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>Hubo un error al modificar el registro!</font></b></td></tr></table>";
		}
	}

	return $resultado;
}



//formulario modifica empresa
function modificaEmpresa($rut_empresa,$nombre_empresa,$direccion_empresa,$ciudad_empresa,$telefono,$fax,$estado,$condicion_compra)
{
	include '../include/conexionSistema.php';

	$sql	=	"select count(rut_empresa) existe from empresa where rut_empresa='$rut_empresa'";
	$result = 	mysqli_query($mysql,$sql);
	$array 	= 	mysqli_fetch_array($result);

	if($array['existe']==0)
	{
		//si el registro existe en la base de datos indica mensaje de error

		$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>El registro no existe en la base de datos!</font></b></td></tr></table>";

	}
	else
	{
		$sql="update empresa set nombre_empresa='".strtoupper($nombre_empresa)."',direccion_empresa='".strtoupper($direccion_empresa)."',ciudad_empresa='".strtoupper($ciudad_empresa)."',telefono='".$telefono."',fax='".$fax."', estado='$estado',usuario_modifica='".strtoupper($_SESSION['id_usuario'])."',fecha_modifica=now(),condicion_compra='$condicion_compra' where rut_empresa='".strtoupper($rut_empresa)."'";

		if(mysqli_query($mysql,$sql))
		{
			$resultado="<table width='100%'><tr><td bgcolor='green'><b><font color='white'>Se han modificado los datos exitosamente!</font></b></td></tr></table>";
			logTransaccion2($_SESSION['id_usuario'],"Modifica la empresa $rut_empresa $nombre_empresa $direccion_empresa $ciudad_empresa $telefono $fax $estado $condicion_compra");

		}
		else
		{
			$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>Hubo un error al modificar el registro!</font></b></td></tr></table>";
		}
	}

	return $resultado;
}




function agregarOperacion($codigo_operacion,$nombre_operacion)
{
	include '../include/conexionSistema.php';

	//consulta si el código de operacion existe en la base de datos
	$sql="select count(*) existe from operacion where id_operacion='$codigo_operacion'";

	$result = mysqli_query($mysql,$sql);

	$array = mysqli_fetch_array($result);

	if($array['existe']>0)
	{
		$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>El código de operación ya se encuentra en la base de datos!</font></b></td></tr></table>";
	}
	else
	{
		//si la bodega no se encuentra en la base de datos, inserta el registro
		$sql="insert into operacion (id_operacion,nombre_operacion,estado) values ('".strtoupper($codigo_operacion)."','".strtoupper($nombre_operacion)."','ACT');";

		//consulta si se ejecuta la consulta sql
		if(mysqli_query($mysql,$sql))
		{
			logTransaccion2($_SESSION['id_usuario'],"Agrega nueva operacion codigo:$codigo_operacion  nombre de operacion:$nombre_operacion");

			$resultado="<table width='100%'><tr><td bgcolor='green'><b><font color='white'>Se ha registrado la unidad de medida exitosamente!</font></b></td></tr></table>";
		}
		else
		{
			$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>Hubo un error al guardar el registro!</font></b></td></tr></table>";
		}
	}

	return $resultado;
}



function modificaOperacion($id_operacion,$nombre_operacion,$estado){

	include '../include/conexionSistema.php';

	$sql	=	"update operacion set nombre_operacion='$nombre_operacion',estado='$estado' where id_operacion='$id_operacion'";
	if(mysqli_query($mysql,$sql)){
		$resultado="<table width='100%'><tr><td bgcolor='green'><b><font color='white'>Se ha registrado los datos exitosamente!</font></b></td></tr></table>";
		logTransaccion2($_SESSION['id_usuario'],"Modifica la operacion $id_operacion $nombre_operacion $estado");

	}else{
		$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>Hubo un error al guardar el registro!</font></b></td></tr></table>";
	}

	return $resultado;
}

function eliminaOperacion($id_operacion){

	include '../include/conexionSistema.php';

	$sql	=	"delete from operacion where id_operacion='$id_operacion'";
	if(mysqli_query($mysql,$sql)){
		$resultado="<script>alert('Se ha eliminado la operacion exitosamente');document.location.href='mantenedorOperaciones.php';</script>";
		logTransaccion2($_SESSION['id_usuario'],"Elimina la operacion $id_operacion");

	}else{
		$resultado="<script>alert('Ocurrio un error al eliminar el registro');</script>";
	}

	return $resultado;
}


function agregarCondicionPago($descripcion){
	include '../include/conexionSistema.php';

	//consulta si la bodega existe en la base de datos
	$sql	=	"select count(*) existe from condicion_pago where descripcion='$descripcion'";
	$result = 	mysqli_query($mysql,$sql);
	$array 	= 	mysqli_fetch_array($result);

	if($array['existe']>0){
		$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>La condcion de pago ya se encuentra en la base de datos!</font></b></td></tr></table>";
	}
	else
	{
		//si la bodega no se encuentra en la base de datos, inserta el registro
		$sql="insert into condicion_pago (id_condicion_pago,descripcion,estado) values (null,'".strtoupper($descripcion)."','ACT');";

		//consulta si se ejecuta la consulta sql
		if(mysqli_query($mysql,$sql))
		{
			logTransaccion2($_SESSION['id_usuario'],"Agrega condicion de pago $descripcion");

			$resultado="<table width='100%'><tr><td bgcolor='green'><b><font color='white'>Se han registrado los datos exitosamente!</font></b></td></tr></table>";
		}
		else
		{
			$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>Ocurrio un error al guardar el registro!</font></b></td></tr></table>";
		}
	}

	return $resultado;
}


function modificaCondicionPago($id_condicion_pago,$descripcion,$estado){
	include '../include/conexionSistema.php';

	$sql="update condicion_pago set estado='$estado',descripcion='$descripcion' where id_condicion_pago='$id_condicion_pago'";
	if(mysqli_query($mysql,$sql)){
		$resultado="<table width='100%'><tr><td bgcolor='green'><font color='white'>Se han registrado los datos exitosamente!</font></td></tr></table>";
	}else{
		$resultado="<table width='100%'><tr><td bgcolor='red'><font color='white'>Ocurrio un error al guardar el registro!</font></td></tr></table>";
	}

	return $resultado;
}

function eliminaCondicionPago($id_condicion_pago){
	include '../include/conexionSistema.php';

	$sql	=	"delete from condicion_pago where id_condicion_pago='$id_condicion_pago'";
	if(mysqli_query($mysql,$sql)){
		$resultado="<script>alert('Se ha eliminado el registro exitosamente!');document.location.href='mantenedorCondicionesPago.php';</script>";
	}else{
		$resultado="<script>alert('Ocurrio un error al eliminar el registro.');document.location.href='mantenedorCondicionesPago.php';</script>";
	}
	return $resultado;
}

function agregarUnidadMedida($id_unidad_medida,$descripcion)
{
	include '../include/conexionSistema.php';

	//consulta si la bodega existe en la base de datos
	$sql="select count(*) existe from unidad_medida where id_unidad_medida='$id_unidad_medida'";

	$result = mysqli_query($mysql,$sql);

	$array = mysqli_fetch_array($result);

	if($array['existe']>0)
	{
		$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>La unidad de medida ya se encuentra en la base de datos!</font></b></td></tr></table>";
	}
	else
	{
		//si la bodega no se encuentra en la base de datos, inserta el registro
		$sql="insert into unidad_medida (id_unidad_medida,descripcion,usuario_creacion,fecha_creacion) values ('".strtoupper($id_unidad_medida)."','".strtoupper($descripcion)."','".strtoupper($_SESSION['id_usuario'])."',now());";

		//consulta si se ejecuta la consulta sql
		if(mysqli_query($mysql,$sql))
		{
			logTransaccion2($_SESSION['id_usuario'],"Agrega Unidad de Medida $id_unidad_medida $descripcion");

			$resultado="<table width='100%'><tr><td bgcolor='green'><b><font color='white'>Se ha registrado la unidad de medida exitosamente!</font></b></td></tr></table>";
		}
		else
		{
			$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>Hubo un error al guardar el registro!</font></b></td></tr></table>";
		}
	}

	return $resultado;
}



function modificaUnidadMedida($id_unidad_medida,$descripcion)
{
	include '../include/conexionSistema.php';

	$sql="select count(descripcion) existe from unidad_medida where id_unidad_medida='$id_unidad_medida'";

	$result = mysqli_query($mysql,$sql);

	$array = mysqli_fetch_array($result);

	if($array['existe']==0)
	{
		//si la bodega existe en la base de datos indica mensaje de error

		$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>La unidad de medida no existe!</font></b></td></tr></table>";

	}
	else
	{
		 $sql="update unidad_medida set descripcion='".strtoupper($descripcion)."',usuario_modifica='".strtoupper($_SESSION['id_usuario'])."',fecha_modifica=now() where id_unidad_medida='".$id_unidad_medida."'";

		if(mysqli_query($mysql,$sql))
		{
			logTransaccion2($_SESSION['id_usuario'],"Modifica Unidad de Medida $id_unidad_medida $descripcion");

			$resultado="<table width='100%'><tr><td bgcolor='green'><b><font color='white'>Se modificado el registro existosamente!</font></b></td></tr></table>";
		}
		else
		{
			$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>Hubo un error al modificar el registro!</font></b></td></tr></table>";
		}
	}

	return $resultado;
}



function eliminaUnidadMedida($id_unidad_medida)
{
	include '../include/conexionSistema.php';

	$sql="select count(id_unidad_medida) existe from unidad_medida where id_unidad_medida='".$id_unidad_medida."'";

	$result = mysqli_query($mysql,$sql);

	$array = mysqli_fetch_array($result);

	if($array['existe']>0)
	{
	   $sql="delete from unidad_medida where id_unidad_medida = '".$id_unidad_medida."'";

	   if(mysqli_query($mysql,$sql))
	   {
		   logTransaccion2($_SESSION['id_usuario'],"Elimina Unidad de Medida $id_unidad_medida");

		  $resultado.= "<script>alert('Se ha eliminado unidad de medida existosamente');document.location.href='mantenedorUnidadesMedida.php';</script>";
	   }
	   else
	   {
		  $resultado.= "<script>alert('No se pudo eliminar la unidad de medida. Intente nuevamente o contacte al administrador');document.location.href='mantenedorUnidadesMedida.php?descripcion=".$descripcion."';</script>";
	   }
	}

	return $resultado;
}





//formulario agregar mecanico
function agregarEmpleado($rut,$nombre,$cargo)
{
	include '../include/conexionSistema.php';

	$sql="select count(rut) existe from empleado where rut='$rut'";

	$result = mysqli_query($mysql,$sql);

	$array = mysqli_fetch_array($result);

	if($array['existe']==1)
	{
		//si el empleado existe en la base de datos indica mensaje de error
		$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>El empleado ya existe en la base de datos!</font></b></td></tr></table>";
	}
	else
	{

		$sql="INSERT INTO `empleado` (`rut`, `nombre`,`cargo`, `estado`,`usuario_creacion`,`fecha_creacion`) VALUES ('".strtoupper($rut)."', '".strtoupper($nombre)."','".strtoupper($cargo)."' ,'ACT','".$_SESSION['id_usuario']."',now());";

		if(mysqli_query($mysql,$sql))
		{

			logTransaccion2($_SESSION['id_usuario'],"Agrega empleado $rut $nombre $cargo ");

			//si se guarda el registro en la base de datos, emite un mensaje OK

			$resultado="<table width='100%'><tr><td bgcolor='green'><font color='white'><b>Se ha creado el empleado exitosamente</b></font></td></tr></table>";

		}
		else
		{
			//si no se guarda el registro en la base de datos emite un mensaje de error

			$resultado="<table width='100%'><tr><td bgcolor='red'><font color='white'><b>Hubo un error al crear el empleado!</b></font></td></tr></table>";
		}

	}

	//envia el resultado a la pantalla del usuario
	return $resultado;
}


function modificaEmpleado($rut,$nombre,$cargo,$estado)
{
	include '../include/conexionSistema.php';

	$sql="select count(rut) existe from empleado where rut='$rut'";

	$result = mysqli_query($mysql,$sql);

	$array = mysqli_fetch_array($result);

	if($array['existe']==0)
	{
		// si el mecanico no existe en la base de datos. entrega el mensaje de error
		$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>El empleado no existe en la base de datos!</font></b></td></tr></table>";

	}
	else
	{
		$sql="update empleado set  nombre='".strtoupper($nombre)."',cargo='".strtoupper($cargo)."',estado='$estado',usuario_modifica='".strtoupper($_SESSION['id_usuario'])."',fecha_modifica=now() where rut='".$rut."'";

		if(mysqli_query($mysql,$sql))
		{

			logTransaccion2($_SESSION['id_usuario'],"Modifica empleado $rut $nombre $cargo $estado");
			$resultado="<table width='100%'><tr><td bgcolor='green'><b><font color='white'>Se modificado el registro existosamente!</font></b></td></tr></table>";
		}
		else
		{
			$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>Hubo un error al modificar el registro!</font></b></td></tr></table>";
		}
	}

	return $resultado;
}



function eliminarEmpleado($rut)
{
	include '../include/conexionSistema.php';


	$sql="select count(rut) existe from empleado where rut='$rut'";

	$result = mysqli_query($mysql,$sql);

	$array = mysqli_fetch_array($result);

	if($array['existe']>0)
	{
	   $sql="delete from empleado where rut = '$rut'";

	   if(mysqli_query($mysql,$sql))
	   {
		  logTransaccion2($_SESSION['id_usuario'],"Elimina empleado $rut");

		  $resultado.= "<script>alert('Se ha eliminado el empleado exitosamente');document.location.href='mantenedorEmpleados.php';</script>";
	   }
	   else
	   {
		  $resultado.= "<script>alert('No se pudo eliminar el empleado. Intente nuevamente o contacte al administrador');document.location.href='fichaEmpleado.php?rut=$rut';</script>";
	   }
	}

	return $resultado;
}


function recalcularTotalesGuia($numero_interno)
{
	include '../include/conexionSistema.php';

	//guarda y recalcula los totales de la guia encabezado
	$sql="update guia set neto=(select sum(costo*cantidad) from guia_detalle where numero_interno='$numero_interno'), iva=(select sum(costo*cantidad)*0.19 from guia_detalle where numero_interno='$numero_interno'), total=(select sum(costo*cantidad)*1.19 from guia_detalle where numero_interno='$numero_interno') where numero_interno='$numero_interno'";

	$result = mysqli_query($mysql,$sql);


	//guarda y recalcula los totales del detalle de la guia
	$sql="select id_articulo from guia_detalle where numero_interno='$numero_interno'";

	$result = mysqli_query($mysql,$sql);

	while($row = mysqli_fetch_array($result))
	{
		$sql="update guia_detalle set iva= ((costo*cantidad)*0.19) , total = ((costo*cantidad)*1.19) where numero_interno='$numero_interno' and id_articulo='".$row['id_articulo']."'";

		mysqli_query($mysql,$sql);
	}
}



function guardarGuia($codigo_movimiento,$fecha_ingreso,$rut_empresa,$rut_proveedor,$oc,$guia_despacho,$numero_boleta,$numero_factura,$nota_credito,$razon_emision,$rut_conductor,$nombre_conducto,$despachar_a)
{
	include '../include/conexionSistema.php';

	//inserta la guia en el programa
	$sql="insert into guia (numero_interno,codigo_movimiento,descripcion_movimiento,fecha_ingreso,rut_empresa,rut_proveedor,nombre_proveedor,digitador,estado,oc,guia_despacho,numero_boleta,numero_factura,nota_credito,razon_emision,rut_conductor,nombre_conductor, despachar_a,fecha_digitacion)values(null,'".strtoupper($codigo_movimiento)."',(select upper(descripcion) from tipo_movimiento where codigo_movimiento='$codigo_movimiento'),'$fecha_ingreso','".strtoupper($rut_empresa)."','$rut_proveedor',(select ifnull(upper(nombre),0) from proveedor where rut_proveedor ='$rut_proveedor'),'".strtoupper($_SESSION['id_usuario'])."','PENDIENTE','$oc','$guia_despacho','$numero_boleta','$numero_factura','$nota_credito','$razon_emision','$rut_conductor','$nombre_conductor','$despachar_a',now());";

	if(mysqli_query($mysql,$sql))
	{
		// trae el id de la ultima guia registrada
		$sql			=	"select max(numero_interno) numero_interno from guia";
		$result 		= 	mysqli_query($mysql,$sql);
		$array  		= 	mysqli_fetch_array($result);
		$numero_interno = 	$array['numero_interno'];

		//asigna los items a la guia
		$sql		="update guia_detalle set numero_interno ='$numero_interno' where numero_interno=0";
		mysqli_query($mysql,$sql);

		//guarda y recalcula los totales
		recalcularTotalesGuia($numero_interno);

		//requiere aprobacion?
		$requiere_aprobacion = requiereAprobacion($codigo_movimiento);

		if($requiere_aprobacion=='SI')
		{
			//consulta el usuario que aprueba el documento
			$cantidad_usuarios_aprueban = cantidadUsuariosAprueban($codigo_movimiento,$numero_interno);

			if($cantidad_usuarios_aprueban>0)
			{
				$estado="PENDIENTE APROBACION";

				logTransaccion2($_SESSION['id_usuario'],"Solicita aprobacion Guia $numero_interno requiere aprobacion de $usuario_aprueba");

			}
			else
			{
				$estado="PENDIENTE";
			}
		}else{
			$estado	=	"PENDIENTE";
		}

		//asigna el estado a la GUIA
		$sql="update guia set estado ='$estado' where numero_interno='$numero_interno'";
		mysqli_query($mysql,$sql);


		//consulta si requiere notificar a los usuarios por correo
		if(requiereNotificacion($codigo_movimiento) == 'SI')
		{
			$sql	=	"select total from guia where numero_interno='$numero_interno'";
			$result = 	mysqli_query($mysql,$sql);
			$array 	= 	mysqli_fetch_array($result);
			$total 	= 	$array['total'];

			//lista todas las reglas del circuito
			$sql	=	"select monto_inicio,monto_termino,id_usuario from workflow_usuario where id_workflow in (select id_workflow from workflow where codigo_movimiento='$codigo_movimiento' and estado='ACT')";
			$result = mysqli_query($mysql,$sql);
			while($row = mysqli_fetch_array($result))
			{
				if($total >= $row['monto_inicio'] and $total <= $row['monto_termino']){
					$row['id_usuario'];
					notificaUsuario($codigo_movimiento,$numero_interno,$row['id_usuario']);
				}
			}
		}
	}
	else
	{
		$numero_interno=0;
	}

	return $numero_interno;
}


function modificarGuia($numero_interno,$codigo_movimiento,$fecha_ingreso,$rut_empresa,$rut_proveedor,$oc,$guia_despacho,$numero_boleta,$numero_factura,$nota_credito,$razon_emision,$rut_conductor,$nombre_conductor,$despachar_a)
{
	include '../include/conexionSistema.php';

	$sql		=		"update guia set codigo_movimiento='$codigo_movimiento',descripcion_movimiento= (select descripcion from tipo_movimiento where codigo_movimiento='$codigo_movimiento'),fecha_ingreso='$fecha_ingreso',	rut_empresa	='$rut_empresa',rut_proveedor='$rut_proveedor',	nombre_proveedor=(select nombre from proveedor where rut_proveedor='$rut_proveedor'),oc='$oc',guia_despacho='$guia_despacho',numero_boleta='$numero_boleta',numero_factura='$numero_factura',nota_credito='$nota_credito',razon_emision='".strtoupper($razon_emision)."',rut_conductor='".strtoupper($rut_conductor)."',	nombre_conductor='".strtoupper($nombre_conductor)."',despachar_a='".strtoupper($despachar_a)."'	where numero_interno='$numero_interno'";
	if(mysqli_query($mysql,$sql)){
				$resultado="<table width='100%'><tr><td bgcolor='green'><b><font color='white'>Se modificado el registro existosamente!</font></b></td></tr></table>";
				logTransaccion2($_SESSION['id_usuario'],"Modifica la guia $numero_interno codigo_movimiento:$codigo_movimiento fecha_ingreso:$fecha_ingreso rut_empresa:$rut_empresa oc:$oc guia_despacho:$guia_despacho numero_boleta:$numero_boleta numero_factura:$numero_factura nota_credito:$nota_credito razon_emision:$razon_emision patente:$patente rut_conductor:$rut_conductor nombre_conducto:$nombre_conductor despachar_a:$despachar_a");
	}else{
				$resultado="<table width='100%'><tr><td bgcolor='green'><b><font color='white'>Ocurrio un error al modificar los datos!</font></b></td></tr></table>";
	}

	recalcularTotalesGuia($numero_interno);

	return $resultado;
}


function anularGuia($numero_interno)
{
	include '../include/conexionSistema.php';

	$resultado="";

	$sql="update guia set estado='NULA' where numero_interno='$numero_interno'";

	if(mysqli_query($mysql,$sql))
	{
		$resultado.="Se ha anulado la guia $numero_interno exitosamente";
	}
	else
	{
		$resultado.="No se han realizado cambios";
	}

	return $resultado;
}



function insertaDetalleGuia($numero_interno,$descripcion_articulo,$id_bodega_origen,$id_bodega_destino,$cantidad)
{
	include '../include/conexionSistema.php';

	//consulta si el detalle ya se encuentra pendiente por ingresar
	$sql		=	"select count(id_articulo) existe from guia_detalle where id_articulo in (select id_articulo from articulo where descripcion ='$descripcion_articulo') and numero_interno=0";
	$result 	= 	mysqli_query($mysql,$sql);
	$array 		= 	mysqli_fetch_array($result);

	if($array[0]==0)
	{
		  $sql			=	"select *
								from articulo
								where  descripcion ='$descripcion_articulo'
								or id_articulo in (select id_articulo from articulo_codigo_barra where codigo_barra = '$descripcion_articulo')";
		 $result 		= mysqli_query($mysql,$sql);
		 $array  		= mysqli_fetch_array($result);
		 $id_articulo 	= $array['id_articulo'];
		 $descripcion 	= $array['descripcion'];

		  $sql="insert into guia_detalle (id_detalle,numero_interno,id_articulo,descripcion_articulo,id_bodega_origen,id_bodega_destino,cantidad,costo,iva,total)values(null,'$numero_interno','$id_articulo', '$descripcion','".strtoupper($id_bodega_origen)."','".strtoupper($id_bodega_destino)."','$cantidad',(select costo_promedio from articulo where id_articulo='$id_articulo'),(select (costo_promedio*$cantidad)*0.19 from articulo where id_articulo='$id_articulo'),(select costo_promedio*$cantidad from articulo where id_articulo='$id_articulo'))";
		  mysqli_query($mysql,$sql);

		  recalcularTotalesGuia($numero_interno);
	}
	else
	{
		echo "<script>alert('El codigo $id_articulo ya se encuentra ingresado en el detalle');</script>";

	}

	return $resultado;
}


function eliminaDetalleGuia($numero_interno,$id_detalle)
{
	include '../include/conexionSistema.php';

	$sql="delete from guia_detalle where id_detalle='$id_detalle'";

	mysqli_query($mysql,$sql);


	recalcularTotalesGuia($numero_interno);
}


function procesarGuia($numero_interno,$id_articulo,$cantidad)
{
	include '../include/conexionSistema.php';

	//existe stock en la bodega de destino?
	//obtiene el tipo de movimiento
	//indica la bodega de origen
	//indica la bodega de destino

       $sql						="select
									(select count(*) from existencia where id_articulo ='$id_articulo' and id_bodega in (select id_bodega_origen from guia_detalle where numero_interno='$numero_interno' and id_articulo='$id_articulo')) existencia_bodega_origen,
									(select count(*) from existencia where id_articulo ='$id_articulo' and id_bodega in (select id_bodega_destino from guia_detalle where numero_interno='$numero_interno' and id_articulo='$id_articulo')) existencia_bodega_destino,
									(select tipo from tipo_movimiento where codigo_movimiento in (select codigo_movimiento from guia where numero_interno='$numero_interno'))tipo,
									(select id_bodega_origen from guia_detalle where numero_interno='$numero_interno' and id_articulo='$id_articulo') id_bodega_origen,
									(select id_bodega_destino from guia_detalle where numero_interno='$numero_interno' and id_articulo='$id_articulo') id_bodega_destino";

	$result 					= mysqli_query($mysql,$sql);
	$array 						= mysqli_fetch_array($result);
	$existencia_bodega_origen 	= $array['existencia_bodega_origen'];
	$existencia_bodega_destino 	= $array['existencia_bodega_destino'];
	$tipo_movimiento 			= $array['tipo'];
	$id_bodega_origen 			= $array['id_bodega_origen'];
	$id_bodega_destino 			= $array['id_bodega_destino'];

	if($tipo_movimiento=='INGRESO')
	{
		if($existencia_bodega_destino==0)
		{
			//si es un ingreso de articulo y no hay stock en la bodega de origen, crea el registro
			$sql="insert into existencia (id,id_articulo,descripcion,id_bodega,cantidad)values(null,'".$id_articulo."',(select descripcion from articulo where id_articulo='$id_articulo'),'$id_bodega_destino','$cantidad')";
			if(mysqli_query($mysql,$sql))
			{
				logTransaccion2($_SESSION['id_usuario'],"Procesa la guia $numero_interno articulo:$id_articulo cantidad:$cantidad INGRESO UNIDADES");
				$x++;
			}
		}
		else
		{
			//si es un ingreso de articulo y hay stock en la bodega de origen, actualiza el registro
			$sql="update existencia set cantidad=cantidad + $cantidad where id_bodega='$id_bodega_destino'  and id_articulo='$id_articulo' ;";
			if(mysqli_query($mysql,$sql))
			{
				logTransaccion2($_SESSION['id_usuario'],"Procesa la guia $numero_interno articulo:$id_articulo cantidad:$cantidad INGRESO UNIDADES");
				$x++;
			}
		}
	}


	if($tipo_movimiento=='TRASPASO')
	{
		if($existencia_bodega_destino==0)
		{
			//si es un traspaso entre bodega, afecta la bodega de origen y destino
			//descuenta las unidades de la bodega de origen y del articulo especificado
			$sql="update existencia set cantidad=cantidad - $cantidad where id_bodega='$id_bodega_origen'  and id_articulo='$id_articulo' ;";
			if(mysqli_query($mysql,$sql))
			{
				$x++;
			}

			//suma las unidades a la bodega destino
			$sql="insert into existencia (id,id_articulo,descripcion,id_bodega,cantidad)values(null,'".$id_articulo."',(select descripcion from articulo where id_articulo='$id_articulo'),'$id_bodega_destino','$cantidad')";
			if(mysqli_query($mysql,$sql))
			{
				$x++;
			}

			//si la bodega de origen queda sin stock, debe eliminar el registro de la tabla existencia de la bodega
			$sql="delete from existencia where id_bodega='$id_bodega_origen'  and id_articulo='$id_articulo' and cantidad=0";
			if(mysqli_query($mysql,$sql))
			{
				$x++;
			}

			logTransaccion2($_SESSION['id_usuario'],"Procesa la guia $numero_interno articulo:$id_articulo cantidad:$cantidad TRASPASO UNIDADES");

		}
		else
		{
			//descuenta las unidades de la bodega de origen y del articulo especificado
			$sql="update existencia set cantidad=cantidad - $cantidad where id_bodega='$id_bodega_origen'  and id_articulo='$id_articulo' ;";
			if(mysqli_query($mysql,$sql))
			{
				$x++;
			}

			//aumenta las unidades de la bodega de origen y del articulo especificado
			$sql="update existencia set cantidad=cantidad + $cantidad where id_bodega='$id_bodega_destino'  and id_articulo='$id_articulo' ;";
			if(mysqli_query($mysql,$sql))
			{
				$x++;
			}

			//si la bodega de origen queda sin stock, debe eliminar el registro de la tabla existencia de la bodega
			$sql="delete from existencia where id_bodega='$id_bodega_origen'  and id_articulo='$id_articulo' and cantidad=0";
			if(mysqli_query($mysql,$sql))
			{
				$x++;
			}

			logTransaccion2($_SESSION['id_usuario'],"Procesa la guia $numero_interno articulo:$id_articulo cantidad:$cantidad TRASPASO UNIDADES");

		}
	}



	if($tipo_movimiento=='EGRESO')
	{
		//si es un traspaso entre bodega, afecta la bodega de origen y destino
		//descuenta las unidades de la bodega de origen y del articulo especificado
	    $sql="update existencia set cantidad=cantidad - $cantidad where id_bodega='$id_bodega_destino'  and id_articulo='$id_articulo' ;";
		if(mysqli_query($mysql,$sql))
		{
			$x++;
		}

		//si la bodega de origen queda sin stock, debe eliminar el registro de la tabla existencia de la bodega
		$sql="delete from existencia where id_bodega='$id_bodega_destino'  and id_articulo='$id_articulo' and cantidad=0";
		if(mysqli_query($mysql,$sql))
		{
			$x++;
		}

		logTransaccion2($_SESSION['id_usuario'],"Procesa la guia $numero_interno articulo:$id_articulo cantidad:$cantidad SALIDA UNIDADES");

	}


	if($tipo_movimiento=='AJUSTE')
	{

	    $sql="update existencia set cantidad=cantidad + $cantidad where id_bodega='$id_bodega_destino'  and id_articulo='$id_articulo' ;";
		if(mysqli_query($mysql,$sql))
		{
			$x++;
		}

		//si la bodega de origen queda sin stock, debe eliminar el registro de la tabla existencia de la bodega
		$sql="delete from existencia where id_bodega='$id_bodega_destino'  and id_articulo='$id_articulo' and cantidad<=0";
		if(mysqli_query($mysql,$sql))
		{
			$x++;
		}

		logTransaccion2($_SESSION['id_usuario'],"Procesa la guia $numero_interno articulo:$id_articulo cantidad:$cantidad AJUSTE INVENTARIO");
	 }


	//si se ejecutaron las transacciones registra el proceso de la guia
	if($x>0)
	{
		//registra lo que se procesa en unidades

		registrarKardex($numero_interno,$id_articulo,$cantidad,$tipo_movimiento,'GUIA',$id_bodega_destino);
	}


	// si se recepciono la guia completa debe cambiar el estado de la guia a recepcionado
	$sql	="select
				(select sum(cantidad) from guia_detalle where numero_interno='$numero_interno') cantidad_solicitada,
				(select sum(cantidad) from kardex 		where numero_interno='$numero_interno') cantidad_procesada";

	$result 				= mysqli_query($mysql,$sql);
	$array 					= mysqli_fetch_array($result);
	$cantidad_solicitada 	= $array['cantidad_solicitada'];
	$cantidad_recepcionda 	= $array['cantidad_procesada'];

	if($cantidad_solicitada==$cantidad_recepcionda)
	{
		$sql="update guia set estado='PROCESADA' where numero_interno='$numero_interno'";
		mysqli_query($mysql,$sql);
	}

	return $x;
}





function agregarTipoVehiculo($id_tipo,$descripcion)
{
	include '../include/conexionSistema.php';

	$sql="select count(*) existe from vehiculo_tipo where id_tipo='$id_tipo'";
	$result = mysqli_query($mysql,$sql);
	$array  = mysqli_fetch_array($result);
	$existe = $array['existe'];

	if($existe==0)
	{
		$sql="insert into vehiculo_tipo (id_tipo,tipo,estado) values('".strtoupper($id_tipo)."','".strtoupper($descripcion)."','ACT')";

		if(mysqli_query($mysql,$sql))
		{
			logTransaccion2($_SESSION['id_usuario'],"Agrega Tipo de Vehiculo id_tipo:$id_tipo descripcion:$descripcion");

			$resultado="<table width='100%'><tr><td bgcolor='green'><font color='white'>Se han guardado los datos exitosamente!</font></td></tr></table>";
		}
		else
		{
			$resultado="<table width='100%'><tr><td bgcolor='red'><font color='white'>Ocurrio un error al guardar los datos.</font></td></tr></table>";
		}
	}
	else
	{
			$resultado="<table width='100%'><tr><td bgcolor='red'><font color='white'>El codigo ya se encuentra en la base de datos!</font></td></tr></table>";
	}

	return $resultado;
}


function agregarTipoMovimiento($codigo_movimiento,$descripcion,$tipo,$referencia,$estado,$pauta_mantencion,$valor1)
{
	include '../include/conexionSistema.php';

	$sql="select count(*) existe from tipo_movimiento where codigo_movimiento='$codigo_movimiento'";
	$result = mysqli_query($mysql,$sql);
	$array  = mysqli_fetch_array($result);
	$existe = $array['existe'];

	if($existe==0)
	{
		 $sql="insert into tipo_movimiento (codigo_movimiento,descripcion,tipo,referencia,estado,usuario_creacion,fecha_creacion,pauta_mantencion,valor)
					values('".strtoupper($codigo_movimiento)."','".strtoupper($descripcion)."','".strtoupper($tipo)."','".strtoupper($referencia)."','".strtoupper($estado)."','".$_SESSION['id_usuario']."',now(),'$pauta_mantencion','$valor1')";

		if(mysqli_query($mysql,$sql))
		{
			logTransaccion2($_SESSION['id_usuario'],"Agrega Tipo de Movimiento codigo_movimiento:$codigo_movimiento descripcion:$descripcion tipo:$tipo referencia:$referencia estado:$estado pauta_mantencion:$pauta_mantencionvalor:$valor");

			$resultado="<table width='100%'><tr><td bgcolor='green'><font color='white'>Se han guardado los datos exitosamente!</font></td></tr></table>";
		}
		else
		{
			$resultado="<table width='100%'><tr><td bgcolor='red'><font color='white'>Ocurrio un error al guardar los datos.</font></td></tr></table>";
		}
	}
	else
	{
			$resultado="<table width='100%'><tr><td bgcolor='red'><font color='white'>El codigo de movimiento ya se encuentra en la base de datos</font></td></tr></table>";
	}

	return $resultado;
}

function modificarTipoMovimiento($codigo_movimiento,$descripcion,$estado,$tipo,$referencia,$pauta_mantencion,$valor1)
{
	include '../include/conexionSistema.php';

	//modifica el registro de tipo de movimiento
	$sql="update tipo_movimiento
			set descripcion='".strtoupper($descripcion)."',
				estado='".strtoupper($estado)."',
				tipo='".strtoupper($tipo)."',
				referencia='".strtoupper($referencia)."',
				usuario_modifica='".strtoupper($_SESSION['id_usuario'])."',
				fecha_modifica=now(),
				pauta_mantencion='$pauta_mantencion',
				valor='$valor1'
				where codigo_movimiento='$codigo_movimiento'";

	if(mysqli_query($mysql,$sql))
	{
		logTransaccion2($_SESSION['id_usuario'],"Modificar tipo de movimiento codigo_movimiento:$codigo_movimiento descripcion:$descripcion estado:$estado tipo:$tipo referencia:$referencia pauta_mantencion:$pauta_mantencion valor:$valor");
		$resultado.="<table width='100%'><tr><td bgcolor='green'><font color='white'>Se ha actualizado el registro existosamente</font></td></tr></table>";
	}
	else
	{
		$resultado.="<table width='100%'><tr><td bgcolor='red'><font color='white'>Ocurrio un error al guardar el registro</font></td></tr></table>";
	}

	return $resultado;
}

function eliminarTipoMovimiento($codigo_movimiento)
{
	include '../include/conexionSistema.php';

	$sql="delete from tipo_movimiento where codigo_movimiento = '".$codigo_movimiento."'";

	if(mysqli_query($mysql,$sql))
	{
		logTransaccion2($_SESSION['id_usuario'],"Eliminar tipo de movimiento $codigo_movimiento");

		$resultado.= "<script>alert('Se ha eliminado el tipo de movimiento exitosamente');document.location.href='mantenedorTipoMovimiento.php';</script>";
	}
	else
	{
		$resultado.= "<script>alert('No se pudo eliminar el tipo de movimiento. Intente nuevamente o contacte al administrador');document.location.href='fichaTipoMovimiento.php?codigo_movimiento=".$codigo_movimiento."';</script>";
	}

	return $resultado;
}


function calcularTotalesOrdenTrabajo($folio)
{
	include '../include/conexionSistema.php';

	$sql="select
			(select sum(cantidad * costo_unitario)  from orden_trabajo_articulo where numero_interno='$folio') neto_articulo,
			(select sum(cantidad * costo_unitario) from orden_trabajo_servicio where numero_interno='$folio') neto_servicio,
			(select sum(cantidad * costo_unitario)*0.19 from orden_trabajo_articulo where numero_interno='$folio')iva_articulo,
			(select sum(cantidad * costo_unitario)*0.19 from orden_trabajo_servicio where numero_interno='$folio') iva_servicio,
			(select sum(cantidad * costo_unitario)*1.19 from orden_trabajo_articulo where numero_interno='$folio') total_articulo,
			(select sum(cantidad * costo_unitario)*1.19 from orden_trabajo_servicio where numero_interno='$folio') total_servicio";
	$result = mysqli_query($mysql,$sql);
	$array = mysqli_fetch_array($result);
	$neto_articulo = number_format($array['neto_articulo'],0,'','');
	$neto_servicio = number_format($array['neto_servicio'],0,'','');
	$iva_articulo  = number_format($array['iva_articulo'],0,'','');
	$iva_servicio  = number_format($array['iva_servicio'],0,'','');
	$total_articulo= number_format($array['total_articulo'],0,'','');
	$total_servicio= number_format($array['total_servicio'],0,'','');

	$sql="update orden_trabajo set neto=$neto_articulo + $neto_servicio, iva=$iva_articulo + $iva_servicio, total=$total_articulo + $total_servicio where numero_interno='$folio'";
	mysqli_query($mysql,$sql);
}

function agregarDefectoOrdenTrabajo($id_tipo_defecto,$comentario_defecto,$numero_interno,$rut_defecto,$observacion_defecto)
{
	include '../include/conexionSistema.php';

	if($rut_defecto<>''){
		    $sql	=	"insert into orden_trabajo_defecto (id_orden_trabajo_defecto,numero_interno,id_tipo_defecto,descripcion_defecto,comentario_defecto,rut_mecanico,nombre_mecanico,observacion) values(null,'$numero_interno','$id_tipo_defecto',(select descripcion from tipo_defecto where id_tipo_defecto='$id_tipo_defecto'),'".strtoupper($comentario_defecto)."','$rut_defecto',(select nombre from empleado where rut='$rut_defecto'),'".strtoupper($observacion_defecto)."')";

	}else{
		    $sql	=	"insert into orden_trabajo_defecto (id_orden_trabajo_defecto,numero_interno,id_tipo_defecto,descripcion_defecto,comentario_defecto,observacion) values(null,'$numero_interno','$id_tipo_defecto',(select descripcion from tipo_defecto where id_tipo_defecto='$id_tipo_defecto'),'".strtoupper($comentario_defecto)."','".strtoupper($observacion_defecto)."')";
	}

	mysqli_query($mysql,$sql);

	logTransaccion2($_SESSION['id_usuario'],"Agrega defecto OT:$numero_interno descripcion:$comentario_defecto id tipo defecto:$id_tipo_defecto rut_empleado:$rut_defecto observacion_defecto:$observacion_defecto");

	calcularTotalesOrdenTrabajo($numero_interno);

}


function modificarDefectoOrdenTrabajo($id_orden_trabajo_defecto,$id_tipo_defecto,$comentario_defecto,$numero_interno,$rut_defecto,$observacion_defecto){

	include '../include/conexionSistema.php';

	 $sql="UPDATE orden_trabajo_defecto
			SET id_tipo_defecto 			= '$id_tipo_defecto',
			descripcion_defecto 			= (select descripcion from tipo_defecto where id_tipo_defecto='$id_tipo_defecto'),
			comentario_defecto 				= '$comentario_defecto',
			rut_mecanico 					= '$rut_defecto',
			nombre_mecanico 				= (select nombre from empleado where rut='$rut_defecto'),
			observacion 					= '".strtoupper($observacion_defecto)."'
			WHERE id_orden_trabajo_defecto	= '$id_orden_trabajo_defecto';";
	if(mysqli_query($mysql,$sql)){
				$resultado="<table width='100%'><tr><td bgcolor='green'><font color='white'><b>Se han registrado los datos exitosamente!</b></font></td></tr></table>";
				logTransaccion2($_SESSION['id_usuario'],"Modifica defecto OT:$numero_interno descripcion:$comentario_defecto id tipo defecto:$id_tipo_defecto rut_mecanico:$rut_mecanico observacion_defecto:$observacion_defecto");

	}else{
				$resultado="<table width='100%'><tr><td bgcolor='green'><font color='white'><b>Ocurrio un error al guardar los datos!</b></font></td></tr></table>";
	}


}



function eliminarDefectoOrdenTrabajo($id_orden_trabajo_defecto)
{
	include '../include/conexionSistema.php';

	$sql="delete from orden_trabajo_defecto where id_orden_trabajo_defecto='$id_orden_trabajo_defecto'";

	mysqli_query($mysql,$sql);

	logTransaccion2($_SESSION['id_usuario'],"Elimina defecto $id_orden_trabajo_defecto");

}


function ingresoTaller($numero_interno){

	include '../include/conexionSistema.php';

	$sql	=	"update orden_trabajo set estado='PROCESO' where numero_interno='$numero_interno'";
	if(mysqli_query($mysql,$sql)){
		logTransaccion2($_SESSION['id_usuario'],"Pasa la OT $numero_interno de PENDIENTE a EN PROCESO");

		$resultado.="<table width='100%'><tr><td bgcolor='green'><font color='white'>Se han realizado los cambios exitosamente!</font></td></tr></table>";
	}else{
	$resultado.="<table width='100%'><tr><td bgcolor='red'><font color='white'>Ocurrio un error al intentar modificar el estado de la OT!</font></td></tr></table>";
	}

	return $resultado;
}


function enviarOT($numero_interno){
	include '../include/conexionSistema.php';

	$resultado="Estimado Usuario,<br><br>";
	$resultado.="Se ha generado una orden de trabajo. A continuación se encuentra el detalle del documento:<br><br>";

	$sql	=	"select * from v_solicitudes_taller where numero_interno='".$numero_interno."'";
	$result	= 	mysqli_query($mysql,$sql);
	$array	= 	mysqli_fetch_array($result);
	$patente=	$array['patente'];

	$resultado.="<table border='1' width='100%'>
					<tr><td colspan='4' align='center'><h3>OT $numero_interno</h3></td></tr>
					<tr>
						<td>PATENTE:</td><td>".$array['patente']."</td>
						<td>KILOMENTRAJE:</td><td>".$array['kilometraje']."</td>
					</tr>
					<tr>
						<td>DIGITADOR:</td><td>".$array['digitador']."</td>
						<td>FECHA SOLICITUD:</td><td>".date('d-m-Y H:i',strtotime($array['fecha_digitacion']))."</td>
					</tr>
					<tr>
						<td>MARCA:</td><td>".$array['marca']."</td>
						<td>MODELO:</td><td>".$array['modelo']."</td>
					</tr>
					<tr>
						<td>VIN:</td><td>".$array['vin']."</td>
					</tr>
					</table><br><br>";

	$resultado .="<table width='100%' border='1'>
					<tr>
						<td bgcolor='#D8D8D8'>DEFECTO</td>
						<td bgcolor='#D8D8D8'>DESCRIPCION</td>
						<td bgcolor='#D8D8D8'>MECANICO</td>
						<td bgcolor='#D8D8D8'>OBSERVACION</td>
					</tr>";

	$sql		="select * from orden_trabajo_defecto where numero_interno='$numero_interno'";
	$result		=mysqli_query($mysql,$sql);
	while($row	=mysqli_fetch_array($result)){
		$resultado.="<tr>
						<td>".$row['descripcion_defecto']."</td>
						<td>".$row['comentario_defecto']."</td>
						<td>".$row['nombre_mecanico']."</td>
						<td>".$row['observacion']."</td>
					</tr>";
	}
	$resultado.="</table>";


	$resultado .="<br><br>
					<table width='100%' border='1'>
					<tr>
						<td bgcolor='#D8D8D8'>REPUESTO</td>
						<td bgcolor='#D8D8D8'>CANTIDAD</td>
					</tr>";

	$sql		="select * from orden_trabajo_articulo where numero_interno='$numero_interno'";
	$result		=mysqli_query($mysql,$sql);
	while($row	=mysqli_fetch_array($result)){
		$resultado.="<tr>
						<td>".$row['descripcion']."</td>
						<td>".$row['cantidad']."</td>
					</tr>";
	}
	$resultado.="</table>";


	$resultado .="<br><br>
					<table width='100%' border='1'>
					<tr>
						<td bgcolor='#D8D8D8'>SERVICIO</td>
						<td bgcolor='#D8D8D8'>CANTIDAD</td>
					</tr>";

	$sql		="select * from orden_trabajo_servicio where numero_interno='$numero_interno'";
	$result		=mysqli_query($mysql,$sql);
	while($row	=mysqli_fetch_array($result)){
		$resultado.="<tr>
						<td>".$row['descripcion_servicio']."</td>
						<td>".$row['cantidad']."</td>
					</tr>";
	}
	$resultado.="</table>";

	$resultado.="<br><br><img src='http://www.sucontrol.cl/suControl/images/logo_empresa/".$_SESSION['id_empresa'].".jpg'>";


	$sql						=	"select
										(select correo from usuario where id_usuario='".$_SESSION['id_usuario']."') correo_digitador,
										(select correo_usuario_a_cargo from bodega where id_bodega in (select id_bodega from vehiculo where patente='$patente'))correo_usuario_a_cargo";
	$result						= 	mysqli_query($mysql,$sql);
	$array						= 	mysqli_fetch_array($result);
	$correo_digitador			=	$array['correo_digitador'];
	$correo_usuario_a_cargo		=	$array['correo_usuario_a_cargo'];

	mail("$correo_digitador , $correo_usuario_a_cargo","OT $numero_interno",$resultado,'Content-type: text/html; charset=utf-8');

	return "<script>alert('Se ha notificado a las casillas de correo $correo_digitador y $correo_usuario_a_cargo');</script>";
}

function agregarMotivoRechazo($id_motivo_rechazo,$descripcion)
{
	include '../include/conexionSistema.php';

	$sql	=	"insert into motivos_rechazo (id_motivo_rechazo,descripcion,estado) values('$id_motivo_rechazo','$descripcion','ACT')";

	if(mysqli_query($mysql,$sql)){
				$resultado="<table width='100%'><tr><td bgcolor='green'><font color='white'><b>Se han registrado los datos exitosamente!</b></font></td></tr></table>";
				logTransaccion2($_SESSION['id_usuario'],"Agrega motivo de rechazo id: $id_motivo_rechazo descripcion: $descripcion ");
	}else{
				$resultado="<table width='100%'><tr><td bgcolor='green'><font color='white'><b>Ocurrio un error al guardar los datos!</b></font></td></tr></table>";
	}

	return $resultado;
}

function modificaMotivoRechazo($id_motivo_rechazo,$descripcion,$estado)
{
	include '../include/conexionSistema.php';

	$sql="update motivos_rechazo set descripcion='".strtoupper($descripcion)."', estado='".strtoupper($estado)."'  where id_motivo_rechazo='$id_motivo_rechazo'";

	if(mysqli_query($mysql,$sql))
	{
		logTransaccion2($_SESSION['id_usuario'],"Modifica motivo de rechazo descripcion:$descripcion id:$id_motivo_rechazo");

		$resultado="<table width='100%'><tr><td bgcolor='green'><b><font color='white'>Se han modificado los datos exitosamente!</font></b></td></tr></table>";
	}
	else
	{
		$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>Hubo un error al modificar el registro!</font></b></td></tr></table>";
	}

	return $resultado;
}

function eliminarMotivoRechazo($id_motivo_rechazo){

	include '../include/conexionSistema.php';

	$sql="delete from motivos_rechazo where id_motivo_rechazo='$id_motivo_rechazo'";

	if(mysqli_query($mysql,$sql))
	{
		logTransaccion2($_SESSION['id_usuario'],"Elimina motivo de rechazo id:$id_motivo_rechazo");

		$resultado="<script>alert('Se ha eliminado el registro exitosamente!');document.location.href='mantenedorMotivosRechazo.php';</script>";
	}
	else
	{
		$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>Hubo un error al eliminar el registro!</font></b></td></tr></table>";
	}

	return $resultado;
}


function agregarRepuestoOrdenTrabajo($descripcion_articulo,$cantidad,$numero_interno)
{
	include '../include/conexionSistema.php';

	$sql			= 	"select id_articulo from articulo where descripcion='$descripcion_articulo'";
	$result 		= 	mysqli_query($mysql,$sql);
	$array			= 	mysqli_fetch_array($result);
	$id_articulo	= 	$array['id_articulo'];

	$sql			=	"insert into orden_trabajo_articulo (id_orden_trabajo_articulo,numero_interno,id_articulo,descripcion,cantidad,costo_unitario,total_neto,iva,total) values(null,'$numero_interno','$id_articulo','$descripcion_articulo','$cantidad',(select costo_promedio from articulo where id_articulo='$id_articulo'),(select costo_promedio * $cantidad from articulo where id_articulo='$id_articulo'),(select (costo_promedio * $cantidad)*0.19 from articulo where id_articulo='$id_articulo'),(select (costo_promedio * $cantidad)*1.19 from articulo where id_articulo='$id_articulo'))";
	mysqli_query($mysql,$sql);


	logTransaccion2($_SESSION['id_usuario'],"Agrega repuesto OT:$numero_interno descripcion_articulo:$descripcion_articulo cantidad:$cantidad");

	calcularTotalesOrdenTrabajo($numero_interno);

}


function eliminarArticuloOrdenTrabajo($id_orden_trabajo_articulo,$numero_interno)
{
	include '../include/conexionSistema.php';

	$sql="delete from orden_trabajo_articulo where id_orden_trabajo_articulo='$id_orden_trabajo_articulo'";

	mysqli_query($mysql,$sql);

	logTransaccion2($_SESSION['id_usuario'],"Elimina repuesto OT:$numero_interno id orden trabajo articulo:$id_orden_trabajo_articulo");

	calcularTotalesOrdenTrabajo($numero_interno);

}

function agregarServicioOrdenTrabajo($descripcion_servicio,$cantidad_servicio,$costo_unitario,$numero_interno)
{
	include '../include/conexionSistema.php';

	$sql="insert into orden_trabajo_servicio (id_orden_trabajo_servicio,numero_interno,descripcion_servicio,cantidad,costo_unitario,valor_neto,iva,total) values(null,'$numero_interno','$descripcion_servicio',$cantidad_servicio,'$costo_unitario',($cantidad_servicio * $costo_unitario),($cantidad_servicio * $costo_unitario)*0.19,(($cantidad_servicio * $costo_unitario)*1.19))";

	mysqli_query($mysql,$sql);

	logTransaccion2($_SESSION['id_usuario'],"Agrega servicio OT:$numero_interno descripcion:$descripcion_servicio cantidad:$cantidad_servicio costo unitario:$costo_unitario");

	calcularTotalesOrdenTrabajo($numero_interno);

}


function eliminarServicioOrdenTrabajo($id_orden_trabajo_servicio,$numero_interno)
{
	include '../include/conexionSistema.php';

	$sql="delete from orden_trabajo_servicio where id_orden_trabajo_servicio='$id_orden_trabajo_servicio'";

	mysqli_query($mysql,$sql);

	logTransaccion2($_SESSION['id_usuario'],"Elimina servicio OT:$numero_interno id orden trabajo servicio:$id_orden_trabajo_servicio");

	calcularTotalesOrdenTrabajo($numero_interno);

}


function agregarMecanicoOrdenTrabajo($rut,$numero_interno)
{
	include '../include/conexionSistema.php';

	$sql="insert into orden_trabajo_mecanico (id_orden_trabajo_mecanico,numero_interno,rut,nombre,cargo) values(null,'$numero_interno',rut,(select nombre from empleado where rut='$rut'),(select upper(cargo) from empleado where rut='$rut'))";

	mysqli_query($mysql,$sql);

	logTransaccion2($_SESSION['id_usuario'],"Agrega mecanico OT:$numero_interno $rut");

}


function eliminarMecanicoOrdenTrabajo($id_orden_trabajo_mecanico,$numero_interno)
{
	include '../include/conexionSistema.php';

	$sql="delete from orden_trabajo_mecanico where id_orden_trabajo_mecanico='$id_orden_trabajo_mecanico'";

	mysqli_query($mysql,$sql);

	logTransaccion2($_SESSION['id_usuario'],"Elimina mecanico OT:$numero_interno id orden de trabajo mecanico:$id_orden_trabajo_mecanico");

}


 function liberarOrdenTrabajo($id_orden_trabajo)
 {
	 include '../include/conexionSistema.php';



	 $sql="update orden_trabajo set estado='LIBERADA', fecha_liberacion=now() where id_orden_trabajo='$id_orden_trabajo'";
	 mysqli_query($mysql,$sql);

	 logTransaccion2($_SESSION['id_usuario'],"Libera OT:$id_orden_trabajo");
 }

function guardarOT($rut_empresa,$patente,$kilometraje,$fecha_solicitud,$rut_conductor,$id_bodega,$supervisor,$id_prioridad,$codigo_movimiento,$patente_visita,$gasto_implementacion,$comentario_final_orden_trabajo,$solicitado_por)
{
	include '../include/conexionSistema.php';

	//si la patente es visita en la base
	if($patente_visita=='si')
	{
		//consulta si debe notificar al supervisor a cargo de la patente
		$sql						=	"select notifica_patente_visita,estado from workflow where codigo_movimiento='$codigo_movimiento'";
		$result 					= 	mysqli_query($mysql,$sql);
		$array  					= 	mysqli_fetch_array($result);
		$notifica_patente_visita 	= 	$array['notifica_patente_visita'];
		$estado						=	$array['estado'];
	}


	if($notifica_patente_visita=='ACT' and $estado=='ACT')
	{
		  $sql="insert into orden_trabajo
					(numero_interno,
					rut_empresa,
					nombre_empresa,
					patente,
					kilometraje,
					digitador,
					id_bodega,
					id_operacion,nombre_bodega, rut_conductor, nombre_conductor,id_marca, id_modelo,
					id_tipo, supervisor, fecha_solicitud, estado,id_prioridad,codigo_movimiento,
					patente_visita,fecha_digitacion,gasto_implementacion,comentario_final_orden_trabajo,solicitado_por)values
					(null,
					'$rut_empresa',
					(select nombre_empresa from empresa where rut_empresa='$rut_empresa'),
					'$patente', '$kilometraje', '".strtoupper($_SESSION['id_usuario'])."', '$id_bodega',(select id_operacion from vehiculo where patente='$patente'), (select nombre from bodega where id_bodega='$id_bodega'), '$rut_conductor', (select nombre from empleado where rut='$rut_conductor'), (select id_marca from vehiculo where patente='$patente'), (select id_modelo from vehiculo where patente='$patente'), (select id_tipo from vehiculo where patente='$patente'),'".strtoupper($supervisor)."','$fecha_solicitud', 'PENDIENTE APROBACION','$id_prioridad','$codigo_movimiento','$patente_visita',now(),'$gasto_implementacion','".strtoupper($comentario_final_orden_trabajo)."','".$solicitado_por."')";
		mysqli_query($mysql,$sql);
	}
	else
	{
		  $sql="insert into orden_trabajo
					(numero_interno,
					rut_empresa,
					nombre_empresa,
					patente,
					kilometraje,
					digitador,
					id_bodega,
					id_operacion,
					nombre_bodega, rut_conductor, nombre_conductor,id_marca, id_modelo,
					id_tipo, supervisor,
					fecha_solicitud,
					estado,
					id_prioridad,
					codigo_movimiento,
					patente_visita,
					fecha_digitacion,
					gasto_implementacion,
					comentario_final_orden_trabajo,
					solicitado_por)
					values
					(null,
					'$rut_empresa',
					(select nombre_empresa from empresa where rut_empresa='$rut_empresa'),
					'$patente',
					'$kilometraje',
					'".strtoupper($_SESSION['id_usuario'])."',
					'$id_bodega',
					(select id_operacion from vehiculo where patente='$patente'),
					(select nombre from bodega where id_bodega='$id_bodega'), '$rut_conductor',
					(select nombre from empleado where rut='$rut_conductor'),
					(select id_marca from vehiculo where patente='$patente'),
					(select id_modelo from vehiculo where patente='$patente'),
					(select id_tipo from vehiculo where patente='$patente'),
					'".strtoupper($supervisor)."',
					'$fecha_solicitud',
					'PENDIENTE','$id_prioridad','$codigo_movimiento','$patente_visita',now(),'$gasto_implementacion','".strtoupper($comentario_final_orden_trabajo)."','$solicitado_por')";
		mysqli_query($mysql,$sql);
	}


	//obtiene el ID del folio de la OT
	$sql	=	"select max(numero_interno) from orden_trabajo ";
	$result = 	mysqli_query($mysql,$sql);
	$array  = 	mysqli_fetch_array($result);
	$folio 	= 	$array[0];

	$sql	=	"update vehiculo set kilometraje_actual ='$kilometraje' where patente='$patente'";
	mysqli_query($mysql,$sql);

	logTransaccion2($_SESSION['id_usuario'],"Genera folio OT $numero_interno rut_empresa:$rut_empresa patente:$patente kilometraje:$kilometraje fecha solicitud:$fecha_solicitud rut conductor:$rut_conductor id bodega:$id_bodega supervisor:$supervisor prioridad:$prioridad id tipo:$id_tipo codigo_movimiento:$codigo_movimiento patente_visita:$patente_visita gasto_implementacion:$gasto_implementacion comentario_final: $comentario_final_orden_trabajo");

	return  $folio;
}


function solicitudRepuestoBodega($numero_interno){

	include '../include/conexionSistema.php';

	$sql="update orden_trabajo set fecha_inicio_espera=now(),estado='SOLICITUD REPUESTO',fecha_solicitud_repuesto=now(),usuario_solicitud_repuesto='".strtoupper($_SESSION['id_usuario'])."' where numero_interno='$numero_interno'";

	if(mysqli_query($mysql,$sql)){

			$resultado="<table width='100%'><tr><td bgcolor='green'><font color='white'><b>Se ha modificado el registro exitosamente!</b></font></td></tr></table>";

	}
	else{
			$resultado="<table width='100%'><tr><td bgcolor='red'><font color='white'><b>Ocurrio un error al grabar el registro!</b></font></td></tr></table>";
	}

	logTransaccion2($_SESSION['id_usuario'],"Solicita repuestos a bodega OT:$numero_interno");


	return $resultado;
}



function modificaOT($numero_interno,$rut_empresa,$kilometraje,$fecha_solicitud,$rut_conductor,$id_bodega,$supervisor,$id_prioridad,$gasto_implementacion,$comentario_final_orden_trabajo,$solicitado_por,$codigo_movimiento)
{
	include '../include/conexionSistema.php';

	//si la patente es visita en la base

	$sql	=	"update
					orden_trabajo
				set
					rut_empresa						=	'$rut_empresa',
					nombre_empresa					=	(select nombre_empresa from empresa where rut_empresa='$rut_empresa'),
					kilometraje						=	'$kilometraje',
					fecha_solicitud					=	'$fecha_solicitud',
					rut_conductor					=	'$rut_conductor',
					nombre_conductor				=	(select nombre from empleado where rut='$rut_conductor'),
					id_bodega						=	'$id_bodega',
					supervisor						=	'$supervisor',
					id_prioridad					=	'$id_prioridad',
					gasto_implementacion			=	'$gasto_implementacion',
					comentario_final_orden_trabajo	=	'".strtoupper($comentario_final_orden_trabajo)."',
					solicitado_por					=	'$solicitado_por',
					codigo_movimiento				=	'$codigo_movimiento'
				where
					numero_interno	=	'$numero_interno'";

	if(mysqli_query($mysql,$sql))
	{
		logTransaccion2($_SESSION['id_usuario'],"Modifica folio OT $id_orden_trabajo rut_empresa:$rut_empresa kilometraje:$kilometraje fecha solicitud:$fecha_solicitud rut conductor:$rut_conductor id bodega:$id_bodega supervisor:$supervisor prioridad:$prioridad id tipo:$id_tipo codigo_movimiento:$codigo_movimiento gasto_implementacion:$gasto_implementacion solicitado_por:$solicitado_por");

		$resultado="<table width='100%'><tr><td bgcolor='green'><font color='white'><b>Se ha modificado el registro exitosamente!</b></font></td></tr></table>";
	}
	else
	{
		$resultado="<table width='100%'><tr><td bgcolor='red'><font color='white'><b>Ocurrio un error al modificar el registro</b></font></td></tr></table>";
	}

	$sql	=	"select patente from orden_trabajo where numero_interno='$numero_interno'";
	$result =	mysqli_query($mysql,$sql);
	$array	=	mysqli_fetch_array($result);
	$patente=	$array['patente'];

	$sql	=	"update vehiculo set kilometraje_actual='$kilometraje' where patente='$patente';";
	mysqli_query($mysql,$sql);

	return $resultado;
}


function guardarPresupuestoManteneciones($ano)
{
	include '../include/conexionSistema.php';

	$sql	="select id_bodega, nombre from bodega where estado='ACT' order by nombre asc";
	$result = mysqli_query($mysql,$sql);

	while($row = mysqli_fetch_array($result))
	{
		$sql		="select id_tipo_defecto, descripcion from tipo_defecto where estado='ACT'";
		$result2 	=mysqli_query($mysql,$sql);
		while($row2 =mysqli_fetch_array($result2)){

			$sql	 	="select count(*) existe_presupuesto from bodega_presupuesto where id_bodega='".$row['id_bodega']."' and id_tipo_defecto='".$row2['id_tipo_defecto']."' and ano='$ano'";
			$result3 	= mysqli_query($mysql,$sql);
			$array3  	= mysqli_fetch_array($result3);

			//$total 		= ($_REQUEST[$row['id_bodega'].'_enero'] + $_REQUEST[$row['id_bodega'].'_febrero'] + $_REQUEST[$row['id_bodega'].'_marzo'] + $_REQUEST[$row['id_bodega'].'_abril'] + $_REQUEST[$row['id_bodega'].'_mayo'] + $_REQUEST[$row['id_bodega'].'_junio'] + $_REQUEST[$row['id_bodega'].'_julio'] + $_REQUEST[$row['id_bodega'].'_agosto'] + $_REQUEST[$row['id_bodega'].'_septiembre'] + $_REQUEST[$row['id_bodega'].'_octubre'] + $_REQUEST[$row['id_bodega'].'_noviembre'] + $_REQUEST[$row['id_bodega'].'_diciembre']);

			if($array3['existe_presupuesto']==0)
			{
				//si no existe un registro de presupuesto, inserta el registro
				$sql="insert into bodega_presupuesto (id_bodega_presupuesto,id_bodega,nombre_bodega,id_tipo_defecto,descripcion,ano,enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre,total_anual)
						values(null,'".$row['id_bodega']."','".$row['nombre']."','".$row2['id_tipo_defecto']."','".$row2['descripcion']."','".$ano."','".$_REQUEST[$row['id_bodega'].'_'.$row2['id_tipo_defecto'].'_1']."','".$_REQUEST[$row['id_bodega'].'_'.$row2['id_tipo_defecto'].'_2']."', '".$_REQUEST[$row['id_bodega'].'_'.$row2['id_tipo_defecto'].'_3']."','".$_REQUEST[$row['id_bodega'].'_'.$row2['id_tipo_defecto'].'_4']."','".$_REQUEST[$row['id_bodega'].'_'.$row2['id_tipo_defecto'].'_5']."','".$_REQUEST[$row['id_bodega'].'_'.$row2['id_tipo_defecto'].'_6']."','".$_REQUEST[$row['id_bodega'].'_'.$row2['id_tipo_defecto'].'_7']."','".$_REQUEST[$row['id_bodega'].'_'.$row2['id_tipo_defecto'].'_8']."','".$_REQUEST[$row['id_bodega'].'_'.$row2['id_tipo_defecto'].'_9']."','".$_REQUEST[$row['id_bodega'].'_'.$row2['id_tipo_defecto'].'_10']."','".$_REQUEST[$row['id_bodega'].'_'.$row2['id_tipo_defecto'].'_11']."','".$_REQUEST[$row['id_bodega'].'_'.$row2['id_tipo_defecto'].'_12']."', '".$total."')";
			}
			else
			{
				//si existe el registro de presupuesto, modifica el registro
				$sql="UPDATE bodega_presupuesto SET enero = '".$_REQUEST[$row['id_bodega'].'_'.$row2['id_tipo_defecto'].'_1']."', febrero = '".$_REQUEST[$row['id_bodega'].'_'.$row2['id_tipo_defecto'].'_2']."', marzo = '".$_REQUEST[$row['id_bodega'].'_'.$row2['id_tipo_defecto'].'_3']."', abril = '".$_REQUEST[$row['id_bodega'].'_'.$row2['id_tipo_defecto'].'_4']."', mayo = '".$_REQUEST[$row['id_bodega'].'_'.$row2['id_tipo_defecto'].'_5']."', junio = '".$_REQUEST[$row['id_bodega'].'_'.$row2['id_tipo_defecto'].'_6']."', julio = '".$_REQUEST[$row['id_bodega'].'_'.$row2['id_tipo_defecto'].'_7']."', agosto = '".$_REQUEST[$row['id_bodega'].'_'.$row2['id_tipo_defecto'].'_8']."', septiembre = '".$_REQUEST[$row['id_bodega'].'_'.$row2['id_tipo_defecto'].'_9']."', octubre = '".$_REQUEST[$row['id_bodega'].'_'.$row2['id_tipo_defecto'].'_10']."', noviembre = '".$_REQUEST[$row['id_bodega'].'_'.$row2['id_tipo_defecto'].'_11']."', diciembre = '".$_REQUEST[$row['id_bodega'].'_'.$row2['id_tipo_defecto'].'_12']."', total_anual = '".$total."' WHERE id_bodega ='".$row['id_bodega']."' and id_tipo_defecto='".$row2['id_tipo_defecto']."' and ano='$ano';";
			}

			if(mysqli_query($mysql,$sql))
			{
				$c++;
			}

		}
	}

	logTransaccion2($_SESSION['id_usuario'],"Actualiza presupuesto $ano");


	return $c;
}


function entregarRepuesto($numero_interno,$id_articulo,$cantidad)
{
	include '../include/conexionSistema.php';

	$sql					=	"select id_bodega from orden_trabajo where numero_interno='$numero_interno'";
	$result					=	mysqli_query($mysql,$sql);
	$array					=	mysqli_fetch_array($result);
	$id_bodega				=	$array['id_bodega'];

	//obtiene la cantidad disponible en bodega de OT
	$sql					=	"select cantidad from existencia where id_bodega in (select id_bodega from orden_trabajo where numero_interno='$numero_interno') and id_articulo='$id_articulo'";
	$result 				= 	mysqli_query($mysql,$sql);
	$array					= 	mysqli_fetch_array($result);
	$cantidad_disponible	=   $array['cantidad'];

	if($cantidad_disponible>= $cantidad){


				$c++;

				// cuando se entregan los repuestos debe rebajar el stock de la bodega de origen
				$sql="update existencia set cantidad = cantidad - $cantidad where id_bodega in (select id_bodega from orden_trabajo where numero_interno='$numero_interno') and id_articulo='$id_articulo'";
				mysqli_query($mysql,$sql);

				//consulta cuantas unidades quedan en la bodega
				$sql	=	"select sum(cantidad) from existencia where id_bodega in (select id_bodega from orden_trabajo where numero_interno='$numero_interno') and id_articulo='$id_articulo'";
				$result =  	mysqli_query($mysql,$sql);
				$array	=	mysqli_fetch_array($result);

				if($array[0]==0 or $array[0]==null){
					//si no queda ninguna unidad, debe eliminar el registro de la tabla existencia
					$sql="delete from existencia where id_bodega in (select id_bodega from orden_trabajo where numero_interno='$numero_interno') and id_articulo='$id_articulo'";
					mysqli_query($mysql,$sql);
				}

				registrarKardex($numero_interno,$id_articulo,$cantidad,'EGRESO','OT',$id_bodega);

				logTransaccion2($_SESSION['id_usuario'],"Entrega Unidades OT ".$_REQUEST['numero_interno']." articulo:".$row['id_articulo']." cantidad:$cantidad id_bodega:$id_bodega");


				$resultado="<table width='100%'><tr><td bgcolor='green'><font color='white'>Se han entregado las unidades exitosamente!</font></td></tr></table>";

				// si se entregan todos los repuestos debe cambiar el estado de la OT a entregada
				$sql="select
						(select sum(cantidad) from orden_trabajo_articulo where numero_interno='$numero_interno') cantidad_solicitada,
						(select sum(cantidad) from kardex where numero_interno	='$numero_interno' and referencia='OT') cantidad_entregada";

				$result 				= mysqli_query($mysql,$sql);
				$array 		 			= mysqli_fetch_array($result);
				$cantidad_solicitada 	= $array['cantidad_solicitada'];
				$cantidad_entregada 	= $array['cantidad_entregada'];

				if($cantidad_solicitada==$cantidad_entregada)
				{
					//cuando se entregan todos los repuestos el vehiculo queda en proceso para entrar a taller. eso quiere decir que esta en estacionamiento
					$sql="update orden_trabajo set estado='PROCESO',fecha_inicio_espera=now() where numero_interno='$numero_interno'";
					mysqli_query($mysql,$sql);
				}

	}
	else{
		$resultado="<table width='100%'><tr><td bgcolor='red'><font color='white'>No hay unidades disponibles en la bodega para el codigo $id_articulo!</font></td></tr></table>";
	}



	return $resultado;
}






function agregarProveedor($rut_proveedor,$nombre,$tipo,$direccion,$telefono,$contacto,$id_condicion_pago)
{
	include '../include/conexionSistema.php';

	$sql="select count(rut_proveedor) existe from proveedor where rut_proveedor='$rut_proveedor'";

	$result = mysqli_query($mysql,$sql);

	$array = mysqli_fetch_array($result);

	if($array['existe']==1)
	{
		//si el proveedor existe en la base de datos indica mensaje de error
		$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>El proveedor ya existe en la base de datos!</font></b></td></tr></table>";
	}
	else
	{
		 //inserta el registro en la base de datos

		$sql="INSERT INTO `proveedor` (`rut_proveedor`, `nombre`, `usuario_creacion`, `fecha_creacion`, `estado`,`tipo`,`id_condicion_pago`,`direccion`,`telefono`,`contacto`) VALUES ('$rut_proveedor', '".strtoupper($nombre)."', '".$_SESSION['id_usuario']."', now(), 'ACT','$tipo','$id_condicion_pago','$direccion','$telefono','$contacto');";

		if(mysqli_query($mysql,$sql))
		{
			logTransaccion2($_SESSION['id_usuario'],"Agrega proveedor $rut_proveedor $nombre $tipo $direccion $telefono $contacto $id_condicion_pago");
			//si se guarda el registro en la base de datos, emite un mensaje OK

			$resultado="<table width='100%'><tr><td bgcolor='green'><font color='white'><b>Se ha creado el proveedor exitosamente</b></font></td></tr></table>";

		}
		else
		{
			//si no se guarda el registro en la base de datos emite un mensaje de error

			$resultado="<table width='100%'><tr><td bgcolor='red'><font color='white'><b>Hubo un error al crear el proveedor!</b></font></td></tr></table>";
		}

	}

	//envia el resultado a la pantalla del usuario
	return $resultado;
}




function modificaProveedor($rut_proveedor,$nombre,$estado,$tipo,$direccion,$telefono,$contacto,$id_condicion_pago)
{
	include '../include/conexionSistema.php';

	$sql		=	"select count(rut_proveedor) existe from proveedor where rut_proveedor='$rut_proveedor'";
	$result 	= 	mysqli_query($mysql,$sql);
	$array 		= 	mysqli_fetch_array($result);

	if($array['existe']==0)
	{
		$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>El proveedor no existe en la base de datos!</font></b></td></tr></table>";
	}
	else
	{

	    $sql="update proveedor set nombre='".strtoupper($nombre)."', fecha_modifica=now(), usuario_modifica='".strtoupper($_SESSION['id_usuario'])."',  estado='".strtoupper($estado)."',tipo='$tipo',direccion='".strtoupper($direccion)."',telefono='".strtoupper($telefono)."',contacto='".strtoupper($contacto)."',id_condicion_pago='$id_condicion_pago'  where rut_proveedor='$rut_proveedor'";

		if(mysqli_query($mysql,$sql))
		{
			logTransaccion2($_SESSION['id_usuario'],"Modifica proveedor $rut_proveedor $nombre $tipo $direccion $telefono $contacto $id_condicion_pago");

			$resultado="<table width='100%'><tr><td bgcolor='green'><b><font color='white'>Se han modificado los datos exitosamente!</font></b></td></tr></table>";
		}
		else
		{
			$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>Hubo un error al modificar el registro!</font></b></td></tr></table>";
		}
	}

	return $resultado;
}


function eliminaProveedor($rut_proveedor)
{
	include '../include/conexionSistema.php';

	$sql		=		"delete from proveedor where rut_proveedor='$rut_proveedor'";
	if(mysqli_query($mysql,$sql))
	{
		logTransaccion2($_SESSION['id_usuario'],"Elimina proveedor $rut_proveedor");

		$resultado="<script>alert('Se ha eliminado el proveedor exitosamente');document.location.href='mantenedorProveedores.php';</script>";
	}
	return $resultado;
}


//ordenes de compra


function actualizaItemOC($numero_interno,$id_articulo,$cantidad){

	include '../include/conexionSistema.php';

	$sql="update orden_compra_detalle set cantidad='$cantidad' where id_articulo='$id_articulo' and numero_interno='$numero_interno'";
	if(mysqli_query($mysql,$sql)){
		logTransaccion2($_SESSION['id_usuario'],"Actualiza Item de OC $numero_interno id_articulo:$id_articulo cantidad:$cantidad");
	}
}

function recalcularTotalesOrdenCompra($numero_interno)
{
	include '../include/conexionSistema.php';

	$sql					=	"select valor,codigo_movimiento from tipo_movimiento where codigo_movimiento in (select tipo_documento from orden_compra where numero_interno='$numero_interno');";
	$result 				= 	mysqli_query($mysql,$sql);
	$array					=	mysqli_fetch_array($result);
	$valor					=	str_replace(".","",$array['valor']);
	$codigo_movimiento		=	$array['codigo_movimiento'];

	//guarda y recalcula los totales de la guia encabezado

	if($codigo_movimiento	=='BHE')
	{
		//si es una boleta electronica debe descontar el impuesto del total del documento
		   $sql="update orden_compra
				set
				neto	=(select sum(costo*cantidad) from orden_compra_detalle where numero_interno='$numero_interno'),
				iva		=(select sum(costo*cantidad)*0.".$valor." from orden_compra_detalle where numero_interno='$numero_interno'),
				total	=(select sum(costo*cantidad) from orden_compra_detalle where numero_interno='$numero_interno') - (select sum(costo*cantidad)*0.".$valor." from orden_compra_detalle where numero_interno='$numero_interno')
			where numero_interno='$numero_interno'";

	}
	else
	{
		   $sql="update orden_compra
				set
				neto	=(select sum(costo*cantidad) from orden_compra_detalle where numero_interno='$numero_interno'),
			    iva		=(select sum(costo*cantidad)*0.".$valor." from orden_compra_detalle where numero_interno='$numero_interno'),
			    total	=(select sum(costo*cantidad)*1.".$valor." from orden_compra_detalle where numero_interno='$numero_interno')
			where numero_interno='$numero_interno'";

	}



	$result = mysqli_query($mysql,$sql);


	//guarda y recalcula los totales del detalle de la guia
	$sql="select id_articulo from orden_compra_detalle where numero_interno='$numero_interno'";

	$result = mysqli_query($mysql,$sql);

	while($row = mysqli_fetch_array($result))
	{
		$sql="update orden_compra_detalle
				set iva= ((costo*cantidad)*0.".$valor.") ,
				    total = ((costo*cantidad)*1.".$valor.")
			 where numero_interno='$numero_interno'
			 and id_articulo='".$row['id_articulo']."'";

		mysqli_query($mysql,$sql);
	}
}



function insertaDetalleOrdenCompra($numero_interno,$descripcion_articulo,$id_bodega_destino,$cantidad,$costo_promedio)
{
	include '../include/conexionSistema.php';

	//consulta si el detalle ya se encuentra pendiente por ingresar
	$sql	=	"select count(id_articulo) existe from orden_compra_detalle where id_articulo in (select id_articulo from articulo where descripcion='$descripcion_articulo') and numero_interno='$numero_interno'";
	$result = 	mysqli_query($mysql,$sql);
	$array 	= 	mysqli_fetch_array($result);

	if($array[0]==0)
	{
		 //trae los datos del articulo de acuerdo a la descripcion o uno de sus codigos de barra
		 $sql			=	"select * from articulo where  descripcion ='$descripcion_articulo' or id_articulo in (select id_articulo from articulo_codigo_barra where codigo_barra = '$descripcion_articulo') or id_articulo like '%$descripcion_articulo%'";
		 $result 		= 	mysqli_query($mysql,$sql);
		 $array  		= 	mysqli_fetch_array($result);
		 $id_articulo 	= 	$array['id_articulo'];
		 $descripcion 	= 	$array['descripcion'];


			//si el articulo se encuentra en la base de datos inserta el registro con codigo de articulo
			if($id_articulo<>''){
				echo $sql="insert into orden_compra_detalle(id_detalle,numero_interno,id_articulo,	descripcion_articulo, id_bodega_destino,cantidad,costo,iva,total)values(null,'$numero_interno','$id_articulo','$descripcion','".strtoupper($id_bodega_destino)."','$cantidad','$costo_promedio', '".(($costo_promedio * $cantidad) * 0.19)."','".($costo_promedio*$cantidad)."')";
			}else{
				echo $sql="insert into orden_compra_detalle(id_detalle,numero_interno,descripcion_articulo, id_bodega_destino,cantidad,costo,iva,total)values(null,'$numero_interno','$descripcion_articulo','".strtoupper($id_bodega_destino)."','$cantidad','$costo_promedio', '".(($costo_promedio * $cantidad) * 0.19)."','".($costo_promedio*$cantidad)."')";
			}

			logTransaccion2($_SESSION['id_usuario'],"Inserta item en OC $numero_interno $id_articulo $descripcion bodega destino:$id_bodega_destino cantidad:$cantidad costo promedio:$costo_promedio");

			recalcularTotalesOrdenCompra($numero_interno);

			mysqli_query($mysql,$sql);

	}
	else
	{

		echo "<script>alert('El codigo $id_articulo ya se encuentra ingresado en el detalle');</script>";

	}

	return $resultado;
}


function modificarDetalleOrdenCompra($numero_interno,$id_detalle,$descripcion_articulo,$id_bodega_destino,$cantidad,$costo_promedio){

		include '../include/conexionSistema.php';

		$sql			=	"select
								(select valor from tipo_movimiento where codigo_movimiento in (select tipo_documento from orden_compra where numero_interno='$numero_interno'))valor,
								(select id_articulo from articulo where descripcion ='$descripcion_articulo' or id_articulo in (select id_articulo from articulo_codigo_barra where codigo_barra = '$descripcion_articulo') or id_articulo like '%$descripcion_articulo%')id_articulo";
		$result			=	mysqli_query($mysql,$sql);
		$array			=	mysqli_fetch_array($result);
		$impuesto		=	$array['valor'];
		$id_articulo 	= 	$array['id_articulo'];


		$sql="update
					orden_compra_detalle
				set id_articulo='$id_articulo',
				descripcion_articulo='$descripcion_articulo',
				id_bodega_destino='$id_bodega_destino',
				cantidad='$cantidad',
				costo='$costo_promedio',
				iva='".(($costo_promedio * $cantidad) * ($impuesto/100))."',
				total='".($costo_promedio*$cantidad)."'
			where id_detalle='$id_detalle'";

		mysqli_query($mysql,$sql);
		logTransaccion2($_SESSION['id_usuario'],"Modifica item en OC $numero_interno $id_articulo $descripcion bodega destino:$id_bodega_destino cantidad:$cantidad costo promedio:$costo_promedio");
		recalcularTotalesOrdenCompra($numero_interno);
		echo "<script>alert('Se han registrado los datos exitosamente!');</script>";
		return $resultado;
}


function eliminaDetalleOrdenCompra($numero_interno,$id_detalle)
{
	include '../include/conexionSistema.php';

	$sql="delete from orden_compra_detalle where id_detalle='$id_detalle'";

	mysqli_query($mysql,$sql);

	logTransaccion2($_SESSION['id_usuario'],"Elimina item en OC $numero_interno $id_detalle");

	recalcularTotalesOrdenCompra($numero_interno);
}




function anularOrdenCompra($numero_interno,$comentario_anulacion)
{
	include '../include/conexionSistema.php';

	$resultado="";

	$sql="update orden_compra set estado='NULA',comentario_anulacion='".strtoupper($comentario_anulacion)."',fecha_anulacion=now(),usuario_anulacion='".strtoupper($_SESSION['id_usuario'])."' where numero_interno='$numero_interno'";

	if(mysqli_query($mysql,$sql))
	{
		logTransaccion2($_SESSION['id_usuario'],"Anular OC $numero_interno comentario_anulacion:$comentario_anulacion");

		$resultado.="Se ha anulado la orden de compra $numero_interno exitosamente";
	}
	else
	{
		$resultado.="No se han realizado cambios";
	}

	return $resultado;
}


function buscaNumeroInternoAnteriorDocumento($numero_interno,$nombre_tabla){
	include '../include/conexionSistema.php';
	$nombre_tabla	=	strtolower($nombre_tabla);
	$sql			=	"select numero_interno from $nombre_tabla where numero_interno = $numero_interno -1 ";
	$result			=   mysqli_query($mysql,$sql);
	$array			=   mysqli_fetch_array($result);

	if($array['numero_interno']){
		$numero_interno_anterior	= $array['numero_interno'];
	}else{
		$numero_interno_anterior	= null;
	}

	return $numero_interno_anterior;
}


function buscaNumeroInternoSiguienteDocumento($numero_interno,$nombre_tabla){
	include '../include/conexionSistema.php';

	$nombre_tabla	=	strtolower($nombre_tabla);
	$sql			=	"select numero_interno from $nombre_tabla where numero_interno = $numero_interno +1 ";
	$result			=   mysqli_query($mysql,$sql);
	$array			=   mysqli_fetch_array($result);

	if($array['numero_interno']){
		$numero_interno_anterior	= $array['numero_interno'];
	}else{
		$numero_interno_anterior	= null;
	}

	return $numero_interno_anterior;
}

function cerrarGuia($numero_interno){
	include '../include/conexionSistema.php';

	$sql="update guia set estado='CERRADA' where numero_interno='$numero_interno'";
	if(mysqli_query($mysql,$sql)){
		$resultado="<table width='100%'><tr><td bgcolor='green'><b><font color='white'>Se han modificado los datos exitosamente!</font></b></td></tr></table>";
		logTransaccion2($_SESSION['id_usuario'],"Modifica Guia $numero_interno");
	}else{
		$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>Ocurrio un error al modificar el registro!</font></b></td></tr></table>";
	}

	return $resultado;
}

function modificarOrdenCompra($numero_interno,$codigo_movimiento,$fecha_ingreso,$rut_empresa,$nombre_proveedor,$contacto_proveedor,$razon_emision,$id_condicion_pago,$solicitado_por,$numero_boleta,$boleta_honorario,$numero_factura,$fecha_factura,$factura_pagada,$comentario_factura,$folio_memo,$fecha_memo,$numero_cotizacion,$observaciones,$despachar_a,$ot,$tipo_gasto,$tipo_documento,$check_supervisor,$numero_nota_credito,$fecha_nota_credito,$monto_nota_credito)
{
	include '../include/conexionSistema.php';

	if($nombre_proveedor==''){

		echo "<script>alert('Debe completar el campo proveedor');</script>";

	}else{

			$sql			=	"select
								(select rut_proveedor 	from proveedor where nombre='$nombre_proveedor')rut_proveedor,
								(select count(*) 		from orden_compra_detalle where numero_interno='".$numero_interno."')cantidad_items,
								(select patente 		from orden_trabajo where numero_interno='$ot')patente";
			$result 		= 	mysqli_query($mysql,$sql);
			$array			=	mysqli_fetch_array($result);
			$rut_proveedor	=	$array['rut_proveedor'];
			$cantidad_items	=	$array['cantidad_items'];
			$patente		=	$array['patente'];

			//valida que la oc lleve items
			if($cantidad_items==0){
				echo "<script>alert('OC no tiene items asociados. Debe ingresar al menos 1 item en el detalle del documento');</script>";
			}else{

					    $sql		=	"update orden_compra
									set
									codigo_movimiento       = '".$codigo_movimiento."',
									descripcion_movimiento  = (select descripcion from tipo_movimiento where codigo_movimiento='".$_REQUEST['codigo_movimiento']."'),
									fecha_ingreso	 		= '".$fecha_ingreso."',
									digitador		 		= '".$_SESSION['id_usuario']."',
									rut_empresa		 		= '$rut_empresa',
									nombre_empresa			= (select nombre_empresa from empresa where rut_empresa='$rut_empresa'),
									rut_proveedor	 		= '$rut_proveedor',
									nombre_proveedor 		= '$nombre_proveedor',
									contacto_proveedor		= '$contacto_proveedor',
									numero_boleta			= '$numero_boleta',
									boleta_honorario		= '$boleta_honorario',
									numero_factura			= '$numero_factura',
									fecha_factura			= '$fecha_factura',
									factura_pagada			= '$factura_pagada',
									comentario_factura		= '$comentario_factura',
									razon_emision			= '$razon_emision',
									id_condicion_pago		= '$id_condicion_pago',
									solicitado_por			= '$solicitado_por',
									folio_memo				= '$folio_memo',
									fecha_memo				= '$fecha_memo',
									numero_cotizacion		= '$numero_cotizacion',
									observaciones			= '".strtoupper($observaciones)."',
									ot						= '$ot',
									despachar_a				= '$despachar_a',
									patente					= '$patente',
									tipo_gasto				= '$tipo_gasto',
									tipo_documento			= '$tipo_documento',
									check_supervisor		= '$check_supervisor',
									numero_nota_credito		= '$numero_nota_credito',
									fecha_nota_credito		= '$fecha_nota_credito',
									monto_nota_credito		= '$monto_nota_credito'
									where numero_interno	='$numero_interno'";
						if(mysqli_query($mysql,$sql))
						{
							recalcularTotalesOrdenCompra($numero_interno);
							$resultado="<table width='100%'><tr><td bgcolor='green'><b><font color='white'>Se han modificado los datos exitosamente!</font></b></td></tr></table>";
							logTransaccion2($_SESSION['id_usuario'],"Modifica OC $numero_interno codigo_movimiento:$codigo_movimiento fecha_ingreso:$fecha_ingreso digitador:".$_SESSION['id_usuario']." rut_empresa:$rut_empresa rut_proveedor:$rut_proveedor numero_boleta:$numero_boleta numero_factura:$numero_factura fecha_factura:$fecha_factura factura_pagada:$factura_pagada comentario_factura:$comentario_factura razon_emision:$razon_emision id_condicion_pago:$id_condicion_pago ot:$ot boleta_honorario:$boleta_honorario check_supervisor:$check_supervisor");

						}else{

							$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>Ocurrio un error al modificar el registro!</font></b></td></tr></table>";
						}
				}
		}
	return $resultado;
}





function proximoFolioOrdenCompra()
{
	include '../include/conexionSistema.php';

	$sql			="select opcion from parametro where proceso='orden_compra' and parametro='proximo_folio'";
	$result 		= mysqli_query($mysql,$sql);
	$array  		= mysqli_fetch_array($result);
	return 			$array['opcion'];
}


function condicionesCompra($rut_empresa){
	include '../include/conexionSistema.php';

	$sql			= "select condicion_compra from empresa where rut_empresa='$rut_empresa'";
	$result 		= mysqli_query($mysql,$sql);
	$array  		= mysqli_fetch_array($result);
	return 			  $array['condicion_compra'];
}

function guardar_proximo_folio_oc($proximo_folio){
	include '../include/conexionSistema.php';

	$sql	=	"update parametro set opcion='".$proximo_folio."' where  proceso='orden_compra' and parametro='proximo_folio'";
	if(mysqli_query($mysql,$sql)){
		$resultado="<table width='100%'><tr><td bgcolor='green'><b><font color='white'>Se han modificado los datos exitosamente!</font></b></td></tr></table>";
	}else{
		$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>Hubo un error al modificar el registro!</font></b></td></tr></table>";
	}

	logTransaccion2($_SESSION['id_usuario'],"Modificacion de parametro proximo_folio de orden_compra nuevo valor:$proximo_folio");

	return $resultado;
}



function actualizaProximoFolioOrdenCompra()
{
	include '../include/conexionSistema.php';
	$sql="update parametro set opcion = opcion + 1 where proceso='orden_compra' and parametro='proximo_folio'";
	mysqli_query($mysql,$sql);
}

function requiereAprobacion($codigo_movimiento)
{
	include '../include/conexionSistema.php';


	$sql					=	"select count(*) requiere_aprobacion from workflow where codigo_movimiento='$codigo_movimiento' and estado='ACT'";
	$result 				= 	mysqli_query($mysql,$sql);
	$array		 			= 	mysqli_fetch_array($result);
	$requiere_aprobacion 	= 	$array['requiere_aprobacion'];

	if($requiere_aprobacion==1)
	{

		$requiere_aprobacion="SI";
	}
	else
	{
		$requiere_aprobacion="NO";
	}

	return $requiere_aprobacion;
}



function requiereNotificacion($codigo_movimiento)
{
	include '../include/conexionSistema.php';


	$sql					= "select count(*) requiere_notificacion from workflow where codigo_movimiento='$codigo_movimiento' and estado='ACT' and notifica_correo='ACT'";
	$result 				= mysqli_query($mysql,$sql);
	$array 					= mysqli_fetch_array($result);
	$requiere_notificacion 	= $array['requiere_notificacion'];

	if($requiere_notificacion==1)
	{
		$requiere_notificacion="SI";
	}
	else
	{
		$requiere_notificacion="NO";
	}

	return $requiere_notificacion;
}



function notificaUsuario($codigo_movimiento,$numero_interno,$correo)
{
	include '../include/conexionSistema.php';

	//obtiene el tipo de documento
	$id_empresa				= "xWrtY".$_SESSION['id_empresa']."xWrtY";
	$sql					= "select descripcion,referencia,tipo from tipo_movimiento where codigo_movimiento='$codigo_movimiento'";
	$result 				= mysqli_query($mysql,$sql);
	$array 					= mysqli_fetch_array($result);
	$descripcion_documento	= $array['descripcion'];
	$nombre_tabla			= strtolower($array['referencia']);
	$tipo_movimiento		= $array['tipo'];

	if($nombre_tabla='orden_compra'){

		$sql 				= 	"select ot,patente from orden_compra where numero_interno='$numero_interno';";
		$result 			=	mysqli_query($mysql,$sql);
		$array				=	mysqli_fetch_array($result);
		$ot					=	$array['ot'];
		$patente			=	$array['patente'];
	}

	//si es un ajuste debe apuntar a la tabla guias
	//los ajustes son tratados como documentos de guias
	if($nombre_tabla=="AJUSTE"){$nombre_tabla="GUIA";}

	//prepara el mensaje para el correo

	$sql					=	"select
								nt.digitador,
								nt.rut_empresa,
								nt.estado,
								nt.rut_proveedor,
								nt.nombre_proveedor,
								nt.razon_emision,
								nt.neto,
								nt.iva,
								nt.total,
								(select nombre_empresa from empresa where rut_empresa=nt.rut_empresa) nombre_empresa
								from $nombre_tabla nt where nt.numero_interno=$numero_interno";
	$result 				=	mysqli_query($mysql,$sql);
	$array 					=	mysqli_fetch_array($result);
	$digitador				= 	$array['digitador'];
	$rut_empresa			=	$array['rut_empresa'];
	$nombre_empresa			= 	$array['nombre_empresa'];
	$estado					= 	$array['estado'];
	$rut					=	$array['rut_proveedor'];
	$nombre					=	$array['nombre_proveedor'];
	$razon_emision			=	$array['razon_emision'];
	$neto					=	$array['neto'];
	$iva					=	$array['iva'];
	$total					=	$array['total'];

	if($tipo_movimiento<>'AJUSTE'){
		$mensaje.="<table border='1' rules='all' width='100%'>";
		$mensaje.="<tr><td colspan='2' align='center'><h2><U>$descripcion_documento:$numero_interno</U></h2></td></tr>";
		$mensaje.="<tr><td>DIGITADOR:</td><td>$digitador</td></tr>";
		$mensaje.="<tr><td>RUT EMPRESA:</td><td>$rut_empresa - $nombre_empresa</td></tr>";
		$mensaje.="<tr><td>ESTADO:</td><td><font color='red'>$estado</font></td></tr>";
		$mensaje.="<tr><td>RUT:</td><td>$rut</td></tr>";
		$mensaje.="<tr><td>NOMBRE:</td><td>$nombre</td></tr>";
		$mensaje.="<tr><td>RAZON EMISION:</td><td>$razon_emision</td></tr>";

		if($nombre_tabla=='orden_compra'){
			$mensaje.="<tr><td>OT:</td><td>$ot</td></tr>";
			$mensaje.="<tr><td>PATENTE:</td><td>$patente</td></tr>";
		}

		$mensaje.="<tr><td>NETO:</td><td> $".number_format($neto,0,'','.')."</td></tr>";
		$mensaje.="<tr><td>IVA:</td><td> $".number_format($iva,0,'','.')."</td></tr>";
		$mensaje.="<tr><td>TOTAL:</td><td> $".number_format($total,0,'','.')."</td></tr>";
		$mensaje.="</table><br><br>";
	}else{
		$mensaje.="<table border='1' rules='all' width='100%'>";
		$mensaje.="<tr><td colspan='2' align='center'><h2><U>$descripcion_documento:$numero_interno</U></h2></td></tr>";
		$mensaje.="<tr><td>DIGITADOR:</td><td>$digitador</td></tr>";
		$mensaje.="<tr><td>ESTADO:</td><td><font color='red'>$estado</font></td></tr>";
		$mensaje.="<tr><td>RAZON EMISION:</td><td>$razon_emision</td></tr>";
		$mensaje.="</table><br><br>";
	}


	$mensaje.="<table border='1' rules='all' width='100%'>";
	$mensaje.="<tr bgcolor='#D8D8D8'>
					<td>DESCRIPCION</td>";

	if($tipo_movimiento=='TRASPASO' or $tipo_movimiento=='AJUSTE'){$mensaje.="<td>BODEGA ORIGEN</td>";}
	if($tipo_movimiento<>'AJUSTE'){$mensaje.="<td>BODEGA DESTINO</td>";}

	$mensaje.="	<td>CANTIDAD</td>
				<td>COSTO</td>
				<td>TOTAL</td>
				</tr>";

	$sql	 ="select ntd.descripcion_articulo,
				(select nombre from bodega where id_bodega=ntd.id_bodega_origen)bodega_origen,
				(select nombre from bodega where id_bodega=ntd.id_bodega_destino)bodega_destino,
				ntd.cantidad,
				ntd.costo,
				ntd.total
				from ".$nombre_tabla."_detalle ntd
				where ntd.numero_interno=$numero_interno";
	$result	 = mysqli_query($mysql,$sql);
	while($row = mysqli_fetch_array($result))
	{
		$mensaje.="<tr>
					<td>".$row['descripcion_articulo']."</td>";
		if($tipo_movimiento=='TRASPASO' OR $tipo_movimiento=='AJUSTE'){$mensaje.="<td>".$row['bodega_origen']."</td>";}
		if($tipo_movimiento<>'AJUSTE'){$mensaje.="<td>".$row['bodega_destino']."</td>";}
		$mensaje.=" <td align='center'>".$row['cantidad']."</td>
					<td align='right'>".number_format($row['costo'],0,'','.')."</td>
					<td align='right'>".number_format($row['total'],0,'','.')."</td>
				  </tr>";

		$bodega_destino=$row['bodega_destino'];
	}

	$mensaje.="</table>";
	$mensaje.="<br>";
	$mensaje.="<table width='100%' align='center'>
					<tr>
						<td align='center'><a href='http://".$_SESSION['servidor']."/suControl/sistema/responderDocumentoWorkFlow.php?nombre_tabla=$nombre_tabla&numero_interno=".($numero_interno*2048)."&e=$id_empresa&r=RECHAZADO'><img src='http://www.sucontrol.cl/suControl/images/rechazar_documento.png' width='60' height='60'><br>Rechazar Documento</a></td>
						<td align='center'><a href='http://".$_SESSION['servidor']."/suControl/sistema/responderDocumentoWorkFlow.php?nombre_tabla=$nombre_tabla&numero_interno=".($numero_interno*2048)."&e=$id_empresa&r=APROBADO'><img src='http://www.sucontrol.cl/suControl/images/aprobar_documento.png' width='60' height='60'><br>Aprobar Documento</a></td>
					</tr>
				</table>";


	mail($correo,"$bodega_destino $descripcion_documento $numero_interno",$mensaje,'Content-type: text/html; charset=utf-8');

	//informa al usuario que se ha notificado por email a los responsables de aprobar

	if($correo<>''){
		echo "<script>alert('Se ha notificado a: $correo por el documento $descripcion_documento numero $numero_interno');</script>";
	}
}




function notificaRespuestaDocumento($nombre_tabla,$numero_interno,$estado,$comentario,$correo_digitador){
	include '../include/conexionSistema.php';

	$nombre_tabla			= strtolower($nombre_tabla);
	//prepara el mensaje para el correo

	$sql					="select nt.*,
								(select nombre_empresa 	from empresa 			where rut_empresa=nt.rut_empresa	  ) nombre_empresa,
								(select id_usuario 		from workflow_documento where numero_interno=nt.numero_interno and referencia='$nombre_tabla') usuario_responde,
								(select fecha_respuesta from workflow_documento where numero_interno=nt.numero_interno and referencia='$nombre_tabla') fecha_respuesta,
								(select comentario 		from workflow_documento where numero_interno=nt.numero_interno and referencia='$nombre_tabla') respuesta
								from $nombre_tabla nt
								where nt.numero_interno=$numero_interno";

	$result 				=mysqli_query($mysql,$sql);
	$array 					=mysqli_fetch_array($result);
	$digitador				=$array['digitador'];
	$rut_empresa			=$array['rut_empresa'];
	$nombre_empresa			=$array['nombre_empresa'];
	$estado					=$array['estado'];
	$rut					=$array['rut_proveedor'];
	$nombre					=$array['nombre_proveedor'];
	$razon_emision			=$array['razon_emision'];
	$neto					=$array['neto'];
	$iva					=$array['iva'];
	$total					=$array['total'];
	$codigo_movimiento		=$array['codigo_movimiento'];
	$usuario_responde		=$array['usuario_responde'];
	$fecha_respuesta		=$array['fecha_respuesta'];
	$respuesta				=$array['respuesta'];


	if($nombre_tabla='orden_compra')
	{
		$sql 					= 	"select ot from orden_compra where numero_interno='$numero_interno';";
		$result 				=	mysqli_query($mysql,$sql);
		$array					=	mysqli_fetch_array($result);
		$ot						=	$array['ot'];

		$sql					=	"select count(*) from workflow_documento where numero_interno='$numero_interno' and referencia='orden_compra'";
		$result					=	mysqli_query($mysql,$sql);
		$array					=	mysqli_fetch_array($result);
		$respuesta				=	$array[0];

		if($respuesta>0){
			$sql				=	"select opcion from parametro where proceso='orden_compra' and parametro='notifica_usuario_respuesta'";
			$result 			=	mysqli_query($mysql,$sql);
			$array				=	mysqli_fetch_array($result);
			$notifica_usuario	=	$array['opcion'];
		}
	}


	//obtiene el tipo de documento
	$sql					="select descripcion,tipo from tipo_movimiento where codigo_movimiento='$codigo_movimiento'";
	$result 				= mysqli_query($mysql,$sql);
	$array 					= mysqli_fetch_array($result);
	$descripcion_documento	= $array['descripcion'];
	$tipo_movimiento		= $array['tipo'];

	if($tipo_movimiento<>'AJUSTE'){
	$mensaje.="<table border='1' rules='all' width='100%'>";
	$mensaje.="<tr><td colspan='2' align='center'><h2><U>$descripcion_documento:$numero_interno</U></h2></td></tr>";
	$mensaje.="<tr><td>RUT EMPRESA:</td><td>$rut_empresa - $nombre_empresa</td></tr>";
	$mensaje.="<tr><td>ESTADO:</td><td><font color='red'>$estado</font></td></tr>";
	$mensaje.="<tr><td>RUT:</td><td>$rut</td></tr>";
	$mensaje.="<tr><td>NOMBRE:</td><td>$nombre</td></tr>";
	$mensaje.="<tr><td>RAZON EMISION:</td><td>$razon_emision</td></tr>";

	if($nombre_tabla=='orden_compra')
	{
			$mensaje.="<tr><td>OT:</td><td>$ot</td></tr>";
	}


	$mensaje.="<tr><td>NETO:</td><td> $".number_format($neto,0,'','.')."</td></tr>";
	$mensaje.="<tr><td>IVA:</td><td> $".number_format($iva,0,'','.')."</td></tr>";
	$mensaje.="<tr><td>TOTAL:</td><td> $".number_format($total,0,'','.')."</td></tr>";
	$mensaje.="<tr><td bgcolor='#D8D8D8' colspan='2'><B><U>RESPUESTA</U></B></td></tr>";
	$mensaje.="<tr><td colspan='2'><b><u>$usuario_responde</u></b> ".date('d/m/Y H:i',strtotime($fecha_respuesta))."<br>$respuesta</td></tr>";
	$mensaje.="</table><br><br>";
	}else{
		$mensaje.="<table border='1' rules='all' width='100%'>";
		$mensaje.="<tr><td colspan='2' align='center'><h2><U>$descripcion_documento:$numero_interno</U></h2></td></tr>";
		$mensaje.="<tr><td>DIGITADOR:</td><td>$digitador</td></tr>";
		$mensaje.="<tr><td>ESTADO:</td><td><font color='red'>$estado</font></td></tr>";
		$mensaje.="<tr><td>RAZON EMISION:</td><td>$razon_emision</td></tr>";
		$mensaje.="</table><br><br>";
	}

	$mensaje.="<table border='1' rules='all' width='100%'>";
	$mensaje.="<tr bgcolor='#D8D8D8'>
				<td>DESCRIPCION</td>";
	if($tipo_movimiento=='TRASPASO' or $tipo_movimiento=='AJUSTE'){$mensaje.="<td>BODEGA ORIGEN</td>";}
	if($tipo_movimiento<>'AJUSTE'){$mensaje.="<td>BODEGA DESTINO</td>";}

	$mensaje.="	<td>CANTIDAD</td>
				<td>COSTO</td>
				<td>TOTAL</td></tr>";

	$sql	 	= "select ntd.descripcion_articulo,
					(select nombre from bodega where id_bodega=ntd.id_bodega_origen)bodega_origen,
					(select nombre from bodega where id_bodega=ntd.id_bodega_destino)bodega_destino, ntd.cantidad,ntd.costo, ntd.total from ".$nombre_tabla."_detalle ntd where ntd.numero_interno=$numero_interno";
	$result	 	= mysqli_query($mysql,$sql);
	while($row  = mysqli_fetch_array($result))
	{
		$mensaje.="<tr>
					<td>".$row['descripcion_articulo']."</td>";
		if($tipo_movimiento=='TRASPASO' OR $tipo_movimiento=='AJUSTE'){$mensaje.="<td>".$row['bodega_origen']."</td>";}
		if($tipo_movimiento<>'AJUSTE'){$mensaje.="<td>".$row['bodega_destino']."</td>";}
		$mensaje.=" <td align='center'>".$row['cantidad']."</td>
					<td align='right'>".number_format($row['costo'],0,'','.')."</td>
					<td align='right'>".number_format($row['total'],0,'','.')."</td>
				  </tr>";
	}
	$mensaje.="</table>
				<br>
				<table width='100%' align='center'>
					<tr>
						<td align='center'><a href='http://".$_SESSION['servidor']."/suControl/'><img src='http://".$_SESSION['servidor']."/suControl/images/ver_en_sistema.jpg' width='60' height='60'><br>Ver en Sistema</a></td>
					</tr>
				</table>";



	mail($correo_digitador .",$notifica_usuario","$descripcion_documento $numero_interno $estado",$mensaje,'Content-type: text/html; charset=utf-8');


	echo "<script>alert('Se ha enviado una notificacion a la casilla $correo_digitador');</script>";
}

function responderDocumento($nombre_tabla,$numero_interno,$estado,$comentario){
	include '../include/conexionSistema.php';


	//firma digital
	$sql					=	"select firmar_digitalmente from usuario where id_usuario ='".$_SESSION['id_usuario']."'";
	$result					=	mysqli_query($mysql,$sql);
	$array					=	mysqli_fetch_array($result);
	$firmado_digitalmente	=	$array[0];

	//asigna el nombre de la tabla con letras minusculas
	$nombre_tabla	= strtolower($nombre_tabla);


	//consulta si el usuario ya respondio la aprobacion
	$sql			= "select *  from workflow_documento where numero_interno='$numero_interno' and referencia = '$nombre_tabla'";
	$result 		= mysqli_query($mysql,$sql);
	$array  		= mysqli_fetch_array($result);


	//si no habia respondido, registra la respuesta si aprueba o rechaza
	if($array['id_workflow_documento']==null){
		//registra la respuesta
		$sql				=	"insert into workflow_documento (id_workflow_documento,id_usuario,numero_interno,referencia,estado,fecha_respuesta,comentario,firmado_digitalmente) values(null,'".$_SESSION['id_usuario']."','$numero_interno','$nombre_tabla','$estado',now(),'$comentario','$firmado_digitalmente')";
		mysqli_query($mysql,$sql);

		//actualiza el estado del documento
		$sql				=	"update $nombre_tabla set estado='$estado' where numero_interno=$numero_interno";
		mysqli_query($mysql,$sql);


		//consulta si el circuito requiere notificar la respuesta
		$sql				=	"select
									(select  correo 			from usuario 	where id_usuario 		in (select digitador from $nombre_tabla where numero_interno='$numero_interno'))correo_digitador,
									(select  notifica_respuesta from workflow 	where codigo_movimiento in (select codigo_movimiento from $nombre_tabla where numero_interno='$numero_interno') and estado='ACT') notifica_respuesta";
		$result 			= 	mysqli_query($mysql,$sql);
		$array  			= 	mysqli_fetch_array($result);
		$notifica_respuesta = 	$array['notifica_respuesta'];
		$correo_digitador  	= 	$array['correo_digitador'];

		if($notifica_respuesta=='ACT' and $correo_digitador<>''){

			notificaRespuestaDocumento($nombre_tabla,$numero_interno,$estado,$comentario,$correo_digitador);
		}

		logTransaccion2($_SESSION['id_usuario'],"Responde aprobacion de documento $nombre_tabla $numero_interno $estado $comentario");

		$resultado=1;
	}else{

		echo "<script>alert('No puede responder este documento. El documento ya fue respondido como ".$array['estado']." por ".$array['id_usuario']." con fecha ".date('d/m/Y H:i',strtotime($array['fecha_respuesta']))."');</script>";
		$resultado=0;
	}

	return $resultado;
}


function cantidadUsuariosAprueban($codigo_movimiento,$numero_interno)
{
	include '../include/conexionSistema.php';

	$result 		= 	mysqli_query($mysql,"select referencia from tipo_movimiento where codigo_movimiento='$codigo_movimiento'");
	$array  		= 	mysqli_fetch_array($result);
	$nombre_tabla	=	strtolower($array['referencia']);

	//trae el valor total de la orden

	//si es un ajuste, debe apuntar a la tabla guia
	//los ajustes son tratados como guias
	if($nombre_tabla=='AJUSTE'){$nombre_tabla="GUIA";}

	$result = 	mysqli_query($mysql,"select total from $nombre_tabla where numero_interno='$numero_interno'");
	$array 	= 	mysqli_fetch_array($result);
	$total 	= 	$array['total'];

	//trae todas las reglas asociadas al workflow
	$sql	=	"select monto_inicio,monto_termino
					from workflow_usuario
					where id_workflow in (select id_workflow
											from workflow
											where codigo_movimiento='$codigo_movimiento'
											and estado='ACT')";
	$result = mysqli_query($mysql,$sql);
	while($row = mysqli_fetch_array($result))
	{
		if($total >= $row['monto_inicio'] and $total <= $row['monto_termino']){
			$cantidad_usuarios_aprueban++;
		}
	}

	//retorna la cantidad de usuarios que aprueban
	return  number_format($cantidad_usuarios_aprueban);
}


function ajax_oc_nombre_proveedor($rut_proveedor){

	include '../include/conexionSistema.php';

	$sql				= 	"select nombre from proveedor where rut_proveedor='$rut_proveedor'";
	$result			 	= 	mysqli_query($mysql,$sql);
	$array				=	mysqli_fetch_array($result);
	$nombre_proveedor	= 	$array[0];

	return "<input type='text' value='$nombre_proveedor' disabled   style='width:230px' >";
}


function actualizarFacturaOrdenCompra($numero_interno,$numero_boleta,$numero_factura,$fecha_factura,$factura_pagada,$comentario_factura,$despachar_a,$ot,$tipo_gasto,$boleta_honorario){
	include '../include/conexionSistema.php';

	$sql		=	"select patente from orden_trabajo where numero_interno='$ot'";
	$result 	=	mysqli_query($mysql,$sql);
	$array		=	mysqli_fetch_array($result);
	$patente	=	$array['patente'];


	$sql		=	"update orden_compra set numero_boleta='$numero_boleta',numero_factura='$numero_factura',fecha_factura='$fecha_factura',factura_pagada='$factura_pagada',comentario_factura='$comentario_factura',despachar_a='".strtoupper($despachar_a)."',ot='$ot',patente='$patente',tipo_gasto='$tipo_gasto',boleta_honorario='$boleta_honorario' where numero_interno='$numero_interno'";
	if(mysqli_query($mysql,$sql)){
		$resultado	=	"<table width='100%'><tr><td bgcolor='green'><font color='white'>Se han actualizado los datos exitosamente!</font></td></tr></table>";
	}else{
		$resultado	=	"<table width='100%'><tr><td bgcolor='red'><font color='white'>Ocurrio un error al actualizar los datos!</font></td></tr></table>";
	}

	logTransaccion2($_SESSION['id_usuario'],"Actualiza OC numero_interno:$numero_interno numero_factura:$numero_factura fecha_factura:$fecha_factura factura_pagada:$factura_pagada comentario_factura:$comentario_factura despachar_a:$despachar_a OT:$ot patente:$patente");

	return $resultado;
}

function guardarOrdenCompra($codigo_movimiento,$fecha_ingreso,$rut_empresa,$nombre_proveedor,$contacto_proveedor,$razon_emision,$id_condicion_pago,$solicitado_por,$numero_boleta,$boleta_honorario,$numero_factura,$fecha_factura,$factura_pagada,$comentario_factura,$folio_memo,$fecha_memo,$numero_cotizacion,$observaciones,$despachar_a,$ot,$tipo_gasto,$tipo_documento,$check_supervisor,$numero_nota_credito,$fecha_nota_credito,$monto_nota_credito){

	include '../include/conexionSistema.php';

	//si el campo proveedor esta vacio debe terminar el proceso
	if($nombre_proveedor==''){
		echo "<script>alert('Debe completar el campo proveedor');</script>";
	}else{

		$numero_interno = proximoFolioOrdenCompra();

		//busca el rut del proveedor
		//busca la cantidad de items que tiene la oc

		$sql			=	"select
								(select rut_proveedor 	from proveedor where nombre='$nombre_proveedor')rut_proveedor,
								(select count(*) 		from orden_compra_detalle where numero_interno='".$_SESSION['id_usuario']."')cantidad_items,
								(select patente 		from orden_trabajo where numero_interno='$ot')patente";
		$result 		= 	mysqli_query($mysql,$sql);
		$array			=	mysqli_fetch_array($result);
		$rut_proveedor	=	$array['rut_proveedor'];
		$cantidad_items	=	$array['cantidad_items'];
		$patente		=	$array['patente'];

		//si no tiene items la oc termina este proceso
		if($cantidad_items==0){

		echo "<script>alert('OC no tiene items asociados. Debe ingresar al menos 1 item en el detalle del documento');</script>";

		}else{

				//inserta la OC en el programa

				$sql	="insert into orden_compra (numero_interno,codigo_movimiento,descripcion_movimiento,fecha_ingreso,rut_empresa,nombre_empresa,rut_proveedor,nombre_proveedor,contacto_proveedor,digitador,razon_emision,fecha_digitacion,id_condicion_pago,solicitado_por,numero_boleta,boleta_honorario,numero_factura,fecha_factura,factura_pagada,comentario_factura,folio_memo,fecha_memo,numero_cotizacion,observaciones,despachar_a,ot,patente,tipo_gasto,tipo_documento,check_supervisor,numero_nota_credito,fecha_nota_credito,monto_nota_credito) values ($numero_interno,'".strtoupper($codigo_movimiento)."',(select upper(descripcion) from tipo_movimiento where codigo_movimiento='$codigo_movimiento'),'$fecha_ingreso','".strtoupper($rut_empresa)."',(select nombre_empresa from empresa where rut_empresa='$rut_empresa'),'$rut_proveedor', '$nombre_proveedor','$contacto_proveedor','".strtoupper($_SESSION['id_usuario'])."', '$razon_emision',now(),'$id_condicion_pago','$solicitado_por','$numero_boleta','$boleta_honorario','$numero_factura','$fecha_factura','$factura_pagada','$comentario_factura','$folio_memo','$fecha_memo','$numero_cotizacion','$observaciones','$despachar_a','$ot','$patente','$tipo_gasto','$tipo_documento','$check_supervisor','$numero_nota_credito','$fecha_nota_credito','$monto_nota_credito');";

				if(mysqli_query($mysql,$sql))
				{
					logTransaccion2($_SESSION['id_usuario'],"Genera OC numero_interno:$numero_interno codigo_movimiento:$codigo_movimiento fecha_ingreso:$fecha_ingreso rut_empresa:$rut_empresa rut_proveedor:$rut_proveedor contacto_proveedor:$contacto_proveedor nombre_proveedor:$nombre_proveedor razon_emision:$razon_emision condicion_pago:$id_condicion_pago solicitado_por:$solicitado_por numero_boleta:$numero_boleta numero_factura:$numero_factura fecha_factura:$fecha_factura comentario_factura:$comentario_factura factura_pagada:$factura_pagada folio_memo:$folio_memo fecha_memo:$fecha_memo numero_cotizacion:$numero_cotizacion obervaciones:$observaciones ot:$ot patente:$patente boleta_honorario:$boleta_honorario tipo_documento:$tipo_documento check_supervisor:$check_supervisor");

					//asigna los items a la guia
					$sql	="update orden_compra_detalle set numero_interno ='$numero_interno' where numero_interno='".$_SESSION['id_usuario']."'";
					mysqli_query($mysql,$sql);

					//actualiza el proximo folio
					actualizaProximoFolioOrdenCompra();

					//guarda y recalcula los totales
					recalcularTotalesOrdenCompra($numero_interno);

					//requiere aprobacion?
					$requiere_aprobacion = requiereAprobacion($codigo_movimiento);

					if($requiere_aprobacion=='SI')
					{
						//consulta el usuario que aprueba el documento
						$cantidad_usuarios_aprueban = cantidadUsuariosAprueban($codigo_movimiento,$numero_interno);

						if($cantidad_usuarios_aprueban>0)
						{

							$estado="PENDIENTE APROBACION";

							logTransaccion2($_SESSION['id_usuario'],"Solicita aprobacion OC $numero_interno requiere aprobacion de $usuario_aprueba");

						}
						else
						{
							$estado="PENDIENTE";
						}
					}else{
						$estado="PENDIENTE";
					}

				//asigna el estado a la OC
				$sql="update orden_compra set estado ='$estado' where numero_interno='$numero_interno'";
				mysqli_query($mysql,$sql);


				//consulta si requiere notificar a los usuarios por correo
				if(requiereNotificacion($codigo_movimiento) == 'SI')
				{

					$result 	= 	mysqli_query($mysql,"select total from orden_compra where numero_interno='$numero_interno'");
					$array 		= 	mysqli_fetch_array($result);
					$total 		= 	$array['total'];


					//lista todas las reglas del circuito
					$sql		=	"select wu.monto_inicio,wu.monto_termino,wu.id_usuario,
										(select correo from usuario where id_usuario=wu.id_usuario)email
										from workflow_usuario wu
									where wu.id_workflow in (select id_workflow from workflow where codigo_movimiento='$codigo_movimiento' and estado='ACT')";
					$result 	= 	mysqli_query($mysql,$sql);
					while($row 	= 	mysqli_fetch_array($result))
					{
						if($total >= $row['monto_inicio'] and $total <= $row['monto_termino'])
						{
							$notificar_correo.=$row['email'].",";
						}
					}

					notificaUsuario($codigo_movimiento,$numero_interno,$notificar_correo);

				}
			}else{

					$numero_interno=0;
				}
			}
		}

	return $numero_interno;

}






function agregarCircuitoAprobacion($codigo_movimiento,$notifica_correo)
{
	include '../include/conexionSistema.php';


	$sql="insert into workflow (id_workflow,codigo_movimiento,descripcion,usuario_creacion,fecha_creacion,estado,notifica_correo) values(null,'$codigo_movimiento',(select descripcion from tipo_movimiento where codigo_movimiento='$codigo_movimiento'),'".strtoupper($_SESSION['id_usuario'])."',now(),'INA','$notifica_correo')";

	if(mysqli_query($mysql,$sql))
	{
		logTransaccion2($_SESSION['id_usuario'],"Agrega circuito de aprobacion $codigo_movimiento");
		$resultado.="<table width='100%'><tr><td bgcolor='green'><font color='white'>Se ha creado el registro existosamente</font></td></tr></table>";
	}
	else
	{
		$resultado.="<table width='100%'><tr><td bgcolor='red'><font color='white'>Ocurrio un error al guardar el registro</font></td></tr></table>";
	}

	return $resultado;
}

function agregarUsuarioAutoriza($id_workflow,$id_usuario,$monto_inicio,$monto_termino)
{
	include '../include/conexionSistema.php';

	if($id_usuario<>'')
	{
		$sql="insert into workflow_usuario (id_workflow_usuario,id_usuario,id_workflow,monto_inicio,monto_termino) values(null,'$id_usuario','$id_workflow','$monto_inicio','$monto_termino')";
		if(mysqli_query($mysql,$sql))
		{
			logTransaccion2($_SESSION['id_usuario'],"Agrega usuario a workflow $id_workflow $id_usuario");

			$resultado.="<table width='100%'><tr><td bgcolor='green'><font color='white'>Se ha actualizado el registro existosamente</font></td></tr></table>";
		}
		else
		{
			$resultado.="<table width='100%'><tr><td bgcolor='red'><font color='white'>Ocurrio un error al guardar el registro</font></td></tr></table>";
		}
	}

	return $resultado;
}



function ultimoWorkFlow()
{
	include '../include/conexionSistema.php';

	$sql			=	"select max(id_workflow) from workflow";
	$result			= 	mysqli_query($mysql,$sql);
	$array 			= 	mysqli_fetch_array($result);
	$id_workflow	=	$array[0];

	return $id_workflow;

}

function validarCircuitoAprobacion($id_workflow)
{
	include '../include/conexionSistema.php';

	//valida que el circuito de aprobacion tenga usuarios que validen el movimiento
	$sql			="select count(*) tiene_usuarios from workflow_usuario where id_workflow='$id_workflow' and id_usuario in (select id_usuario from usuario where estado='ACT')";
	$result 		= mysqli_query($mysql,$sql);
	$array  		= mysqli_fetch_array($result);
	$tiene_usuarios = $array['tiene_usuarios'];

	if($tiene_usuarios==0)
	{
		//si no tiene usuarios para que aprueben el movimiento, el circuito debe quedar inactivo
		$sql="update workflow set estado='INA', usuario_modifica='".$_SESSION['id_usuario']."',fecha_modifica=now() where id_workflow='$id_workflow'";
		mysqli_query($mysql,$sql);
		echo "<script>alert('Este circuito de aprobacion ha quedado inactivo ya que no tiene usuarios para aprobar');</script>";
	}
}

function eliminaUsuarioAutoriza($id_workflow_usuario,$id_workflow)
{
	include '../include/conexionSistema.php';

	$sql="delete from workflow_usuario where id_workflow_usuario='$id_workflow_usuario'";

	if(mysqli_query($mysql,$sql))
	{
		logTransaccion2($_SESSION['id_usuario'],"Elimina usuario de workflow $id_workflow");
		$resultado.="<table width='100%'><tr><td bgcolor='green'><font color='white'>Se ha actualizado el registro existosamente</font></td></tr></table>";
	}
	else
	{
		$resultado.="<table width='100%'><tr><td bgcolor='red'><font color='white'>Ocurrio un error al guardar el registro</font></td></tr></table>";
	}

	validarCircuitoAprobacion($id_workflow);

	return $resultado;
}


function eliminaCircuitoAprobacion($id_workflow)
{
	include '../include/conexionSistema.php';

	//elimina los usuarios asociados al workflow
	$sql="delete from workflow_usuario where id_workflow='$id_workflow'";

	if(mysqli_query($mysql,$sql))
	{
		$c++;
	}

	//elimina el workflow
	$sql="delete from workflow where id_workflow='$id_workflow'";
	if(mysqli_query($mysql,$sql))
	{
		$c++;
	}

	//si se ejecutaron las transacciones muestra el resultado
	if($c>0)
	{
		$resultado.="<script>alert('Se ha eliminado el circuito de aprobacion.');document.location.href='listarCircuitosAprobacion.php';</script>";
		logTransaccion2($_SESSION['id_usuario'],"Elimina idworkflow $id_workflow");
	}
	else
	{
		$resultado.="<table width='100%'><tr><td bgcolor='red'><font color='white'>Ocurrio un error al guardar el registro</font></td></tr></table>";
	}


	return $resultado;
}


function modificarCircuitoAprobacion($id_workflow,$codigo_movimiento,$estado,$notifica_correo,$notifica_patente_visita,$notifica_respuesta)
{
	include '../include/conexionSistema.php';

	if($estado			<>"ACT"){$estado		 ="INA";}
	if($notifica_correo <>"ACT"){$notifica_correo="INA";}

	//modifica el circuito de aprobacion
	$sql="update workflow set codigo_movimiento	='".$codigo_movimiento."', descripcion =(select descripcion from tipo_movimiento where codigo_movimiento='$codigo_movimiento'), estado ='$estado', notifica_correo ='$notifica_correo', usuario_modifica='".strtoupper($_SESSION['id_usuario'])."', fecha_modifica=now(),notifica_patente_visita='$notifica_patente_visita',notifica_respuesta='$notifica_respuesta'  where id_workflow='$id_workflow'";

	if(mysqli_query($mysql,$sql)){
		logTransaccion2($_SESSION['id_usuario'],"Modifica workflow $id_workflow");

		$resultado.="<table width='100%'><tr><td bgcolor='green'><font color='white'>Se ha actualizado el registro existosamente</font></td></tr></table>";
	}
	else{
		$resultado.="<table width='100%'><tr><td bgcolor='red'><font color='white'>Ocurrio un error al guardar el registro</font></td></tr></table>";
	}

	validarCircuitoAprobacion($id_workflow);

	return $resultado;
}



function mostrarImagenArticulo($id_articulo)
{
	include '../include/conexionSistema.php';

	$sql	="select imagen from articulo where id_articulo='$id_articulo'";
	$result = mysqli_query($mysql,$sql);
	$array  = mysqli_fetch_array($result);
	$imagen = $array['imagen'];


	if($imagen=='')
	{
		$resultado.="<img src='../images/articulos/sin_imagen.png' width='100' height='100'>";
	}
	else
	{
		$resultado.="<img src='../images/articulos/".$imagen."' width='100' height='100'>";
	}

	return $resultado;

}



function deshacerAprobacionDocumento($numero_interno,$nombre_tabla)
{
	include '../include/conexionSistema.php';

	$nombre_tabla	= strtolower($nombre_tabla);

	$sql="update $nombre_tabla set estado='PENDIENTE APROBACION' where numero_interno='$numero_interno'";
	mysqli_query($mysql,$sql);

	$sql="delete from workflow_documento where numero_interno='$numero_interno' and referencia='$nombre_tabla'";
	mysqli_query($mysql,$sql);

	logTransaccion2($_SESSION['id_usuario'],"Deshace la respuesta al documento $nombre_tabla $numero_interno");
}




function modificaTipoDefecto($id_tipo_defecto,$descripcion,$cuenta_contable,$estado)
{
	include '../include/conexionSistema.php';

	$sql		="select count(id_tipo_defecto) existe from tipo_defecto where id_tipo_defecto='$id_tipo_defecto'";
	$result 	= mysqli_query($mysql,$sql);
	$array 		= mysqli_fetch_array($result);

	if($array['existe']==0)
	{
		//si el articulo existe en la base de datos indica mensaje de error

		$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>El tipo de defecto no existe en la base de datos!</font></b></td></tr></table>";

	}
	else
	{

		$sql="update tipo_defecto set descripcion='$descripcion', cuenta_contable='$cuenta_contable',estado='$estado'  where id_tipo_defecto='$id_tipo_defecto'";

		if(mysqli_query($mysql,$sql))
		{
			logTransaccion2($_SESSION['id_usuario'],"Modifica tipo defecto $id_tipo_defecto descripcion:$descripcion cuenta_contable:$cuenta_contable");

			$resultado="<table width='100%'><tr><td bgcolor='green'><b><font color='white'>Se han modificado los datos exitosamente!</font></b></td></tr></table>";
		}
		else
		{
			$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>Hubo un error al modificar el registro!</font></b></td></tr></table>";
		}
	}

	return $resultado;
}



function modificaTipoGasto($id,$tipo_gasto)
{
	include '../include/conexionSistema.php';

	$sql		="select count(id) existe from oc_tipo_gasto where id='$id'";
	$result 	= mysqli_query($mysql,$sql);
	$array 		= mysqli_fetch_array($result);

	if($array['existe']==0)
	{
		$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>El tipo de gasto no existe en la base de datos!</font></b></td></tr></table>";
	}
	else
	{

		$sql="update oc_tipo_gasto set tipo_gasto='$tipo_gasto' where id='$id'";

		if(mysqli_query($mysql,$sql))
		{
			logTransaccion2($_SESSION['id_usuario'],"Modifica tipo gasto $id descripcion:$tipo_gasto");

			$resultado="<table width='100%'><tr><td bgcolor='green'><b><font color='white'>Se han modificado los datos exitosamente!</font></b></td></tr></table>";
		}
		else
		{
			$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>Hubo un error al modificar el registro!</font></b></td></tr></table>";
		}
	}

	return $resultado;
}


function modificaTipoVehiculo($id_tipo,$descripcion,$estado)
{
	include '../include/conexionSistema.php';

	$sql		="select count(id_tipo) existe from vehiculo_tipo where id_tipo='$id_tipo'";
	$result 	= mysqli_query($mysql,$sql);
	$array 		= mysqli_fetch_array($result);

	if($array['existe']==0)
	{

		$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>El tipo de vehiculo ya se encuentra en la base de datos!</font></b></td></tr></table>";

	}
	else
	{

		$sql="update vehiculo_tipo set tipo='$descripcion', estado='$estado' where id_tipo='$id_tipo'";

		if(mysqli_query($mysql,$sql))
		{
			logTransaccion2($_SESSION['id_usuario'],"Modifica tipo de vehiculo id_tipo:$id_tipo tipo:$tipo estado:$estado");

			$resultado="<table width='100%'><tr><td bgcolor='green'><b><font color='white'>Se han modificado los datos exitosamente!</font></b></td></tr></table>";
		}
		else
		{
			$resultado="<table width='100%'><tr><td bgcolor='red'><b><font color='white'>Hubo un error al modificar el registro!</font></b></td></tr></table>";
		}
	}

	return $resultado;
}



function eliminarTipoDefecto($id_tipo_defecto)
{
	include '../include/conexionSistema.php';

	$sql="delete from tipo_defecto where id_tipo_defecto='$id_tipo_defecto'";
	mysqli_query($mysql,$sql);

	logTransaccion2($_SESSION['id_usuario'],"Elimina tipo defecto $id_tipo_defecto");

	return "<script>alert('Se ha eliminado el defecto exitosamente.');document.location.href='mantenedorDefectos.php';</script>";
}




function eliminarTipoGasto($id)
{
	include '../include/conexionSistema.php';

	$sql="delete from oc_tipo_gasto where id='$id'";
	mysqli_query($mysql,$sql);

	logTransaccion2($_SESSION['id_usuario'],"Elimina tipo gasto $id");

	return "<script>alert('Se ha eliminado el tipo de gasto.');document.location.href='mantenedorTipoGasto.php';</script>";
}


function eliminaTipoVehiculo($id_tipo)
{
	include '../include/conexionSistema.php';

	$sql="delete from vehiculo_tipo where id_tipo='$id_tipo'";
	mysqli_query($mysql,$sql);

	logTransaccion2($_SESSION['id_usuario'],"Elimina tipo vehiculo $id_tipo");

	return "<script>alert('Se ha eliminado el tipo de vehiculo.');document.location.href='mantenedorTipoVehiculo.php';</script>";
}




function agregarTipoDefecto($descripcion,$cuenta_contable)
{
	include '../include/conexionSistema.php';

	$sql="insert into tipo_defecto (id_tipo_defecto,descripcion,cuenta_contable,estado) values(null,'$descripcion','$cuenta_contable','ACT');";
	if(mysqli_query($mysql,$sql))
	{
		logTransaccion2($_SESSION['id_usuario'],"Agrega defecto $descripcion cuenta_contable:$cuenta_contable");

		$resultado.="<table width='100%' ><tr><td bgcolor='green'><font color='white'>Se han guardado los datos exitosamente!</font></td></tr></table>";
	}
	else
	{
		$resultado.="<table width='100%' ><tr><td bgcolor='green'><font color='white'>Ocurrio un error al guardar el registro.</font></td></tr></table>";
	}

	return $resultado;
}


function agregarTipoGasto($tipo_gasto)
{
	include '../include/conexionSistema.php';

	$sql="insert into oc_tipo_gasto (id,tipo_gasto) values(null,'".strtoupper($tipo_gasto)."');";
	if(mysqli_query($mysql,$sql))
	{
		logTransaccion2($_SESSION['id_usuario'],"Agrega tipo gasto $tipo_gasto");

		$resultado.="<table width='100%' ><tr><td bgcolor='green'><font color='white'>Se han guardado los datos exitosamente!</font></td></tr></table>";
	}
	else
	{
		$resultado.="<table width='100%' ><tr><td bgcolor='green'><font color='white'>Ocurrio un error al guardar el registro.</font></td></tr></table>";
	}

	return $resultado;
}


function generarGuiaPorOC($numero_interno)
{
	include '../include/conexionSistema.php';

	  $sql="insert into guia (numero_interno,rut_empresa,codigo_movimiento,descripcion_movimiento,fecha_ingreso,digitador,rut_proveedor,nombre_proveedor,estado,numero_boleta,numero_factura,oc,neto,iva,total,razon_emision,fecha_digitacion,despachar_a,ot,patente)
			select  '' numero_interno,rut_empresa,(select codigo_movimiento from tipo_movimiento where descripcion='ENTRADA POR COMPRA') codigo_movimiento,'ENTRADA POR COMPRA' descripcion_movimiento,now() fecha_ingreso,digitador,rut_proveedor,nombre_proveedor,'PENDIENTE' estado,numero_boleta,numero_factura,numero_interno,neto,iva,total,razon_emision,fecha_digitacion,despachar_a,ot,patente  from orden_compra where numero_interno='$numero_interno';";

	if(mysqli_query($mysql,$sql))
	{
		$sql="insert into guia_detalle (id_detalle,numero_interno,id_articulo,descripcion_articulo,id_bodega_destino,costo,cantidad,iva,total)
			   select '',(select max(numero_interno) from guia where oc='$numero_interno'),id_articulo,descripcion_articulo,id_bodega_destino,costo,cantidad,iva,total  from orden_compra_detalle where numero_interno='$numero_interno';";
		mysqli_query($mysql,$sql);

		logTransaccion2($_SESSION['id_usuario'],"Genera guia por OC $numero_interno");
	}

	$sql="select max(numero_interno) numero_interno from guia where oc='$numero_interno'";
	$result = mysqli_query($mysql,$sql);
	$array = mysqli_fetch_array($result);
	$guia  = $array['numero_interno'];

	$sql="update orden_compra set guia_despacho='$guia' where numero_interno='$numero_interno'";
	mysqli_query($mysql,$sql);

	$resultado.="<script>alert('Se ha generado la guia de despacho numero $guia');</script>";

	return $resultado;
}


function eliminarPresupuestoMantenciones($ano)
{
	include '../include/conexionSistema.php';

	$resultado	="";

	$sql		="delete from bodega_presupuesto where ano='$ano'";

	if(mysqli_query($mysql,$sql))
	{
		logTransaccion2($_SESSION['id_usuario'],"Elimina presupuesto $ano");
		$resultado.="<table width='100%' ><tr><td bgcolor='green'><font color='white'>Se ha eliminado el presupuesto $ano!</font></td></tr></table>";
	}

	return $resultado;
}


function agregarVehiculo($patente,$id_marca,$id_modelo,$id_tipo,$id_bodega,$vin,$ano,$rendimiento_teorico,$kilometraje_actual,$fecha_ultima_mantencion,$numero_ejes,$capacidad_carga,$compartimientos,$tipo_carga,$id_operacion,$kilometraje_proxima_mantencion)
{
	include '../include/conexionSistema.php';

	$sql	=	"select count(*) existe from vehiculo where patente='".$_REQUEST['patente']."'";
	$result = 	mysqli_query($mysql,$sql);
	$array  = 	mysqli_fetch_array($result);

	if($array['existe']==0)
	{

		$sql	="insert into vehiculo (patente,estado,id_marca,id_modelo,id_tipo,vin,ano,id_bodega,rendimiento_teorico,kilometraje_actual,fecha_ultima_mantencion,numero_ejes,capacidad_carga,compartimientos,tipo_carga,id_operacion,kilometraje_proxima_mantencion) values ('".strtoupper($patente)."','ACT','$id_marca','$id_modelo','$id_tipo','".strtoupper($vin)."','$ano','".strtoupper($id_bodega)."','".str_replace(',','.',$rendimiento_teorico)."','$kilometraje_actual','$fecha_ultima_mantencion','$numero_ejes','$capacidad_carga','$compartimientos','$tipo_carga','$id_operacion','$kilometraje_proxima_mantencion')";
		if(mysqli_query($mysql,$sql)){
			logTransaccion2($_SESSION['id_usuario'],"Agrega vehiculo patente:$patente id_marca:$id_marca id_modelo:$id_modelo id_tipo:$id_tipo id_bodega:$id_bodega vin:$vin ano:$ano rendimiento_teorico:$rendimiento_teorico kilometraje_actual:$kilometraje_actual fecha_ultima_mantencion:$fecha_ultima_mantencion numero_ejes:$numero_ejes capacidad_carga:$capacidad_carga compartimientos:$compartimientos tipo_carga:$tipo_carga id_operacion:$id_operacion kilometraje_proxima_mantencion:$kilometraje_proxima_mantencion");

			$resultado="<table width='100%'><tr><td bgcolor='green'><font color='white'>Se han guardado los datos exitosamente!</font></td></tr></table>";
		}else{
			$resultado="<table width='100%'><tr><td bgcolor='red'><font color='white'>Ocurrio un error al guardar los datos!</font></td></tr></table>";
		}
	}else{
		$resultado="<table width='100%'><tr><td bgcolor='red'><font color='white'>La pantente ".strtoupper($patent)."e ya existe en la base de datos!</font></td></tr></table>";
	}


	return $resultado;
}



function modificarVehiculo($patente,$id_marca,$id_modelo,$id_tipo,$id_bodega,$id_operacion,$vin,$ano,$rendimiento_teorico,$kilometraje_actual,$fecha_ultima_mantencion,$estado,$numero_ejes,$capacidad_carga,$compartimientos,$tipo_carga,$id_pauta_mantencion,$kilometraje_proxima_mantencion,$rut_empresa)
{
	include '../include/conexionSistema.php';

	$sql	=	"select count(*) existe from vehiculo where patente='".$_REQUEST['patente']."'";
	$result = 	mysqli_query($mysql,$sql);
	$array  = 	mysqli_fetch_array($result);

	if($array['existe']==1)
	{
		$sql	="update vehiculo set estado='$estado', id_marca='$id_marca', id_modelo='$id_modelo', id_tipo='$id_tipo', vin='$vin', ano='$ano', id_bodega='$id_bodega',id_operacion='$id_operacion', rendimiento_teorico='$rendimiento_teorico',	kilometraje_actual='$kilometraje_actual', fecha_ultima_mantencion='$fecha_ultima_mantencion',numero_ejes='$numero_ejes',capacidad_carga='$capacidad_carga',compartimientos='$compartimientos',tipo_carga='$tipo_carga',id_pauta_mantencion='$id_pauta_mantencion',kilometraje_proxima_mantencion='$kilometraje_proxima_mantencion',rut_empresa='$rut_empresa' where patente='$patente'";
		if(mysqli_query($mysql,$sql))
		{
			logTransaccion2($_SESSION['id_usuario'],"Modifica vehiculo $patente $id_marca $id_modelo $id_tipo $id_bodega $id_operacion $vin $ano $rendimiento_teorico $kilometraje_actual $fecha_ultima_mantencion $estado kilometraje_proxima_mantencion:$kilometraje_proxima_mantencion rut_empresa: $rut_empresa");

			$resultado="<table width='100%'><tr><td bgcolor='green'><font color='white'>Se han guardado los datos exitosamente!</font></td></tr></table>";
		}
		else
		{
			$resultado="<table width='100%'><tr><td bgcolor='red'><font color='white'>Ocurrio un error al guardar los datos!</font></td></tr></table>";
		}
	}
	else
	{
		$resultado="<table width='100%'><tr><td bgcolor='red'><font color='white'>La pantente ".strtoupper($patent)."e ya existe en la base de datos!</font></td></tr></table>";
	}

	return $resultado;
}


function eliminaVehiculo($patente)
{

	include '../include/conexionSistema.php';

	$sql	=	"delete from vehiculo where patente  = '$patente'";

	if(mysqli_query($mysql,$sql))
	{
		logTransaccion2($_SESSION['id_usuario'],"Elimina patente $patente");

		$resultado= "<script>alert('Se ha eliminado la patente $patente existosamente');document.location.href='mantenedorVehiculo.php';</script>";
	}
	else
	{
		$resultado= "<script>alert('Ocurrio un error al eliminar la patente $patente');document.location.href='fichaVehiculo.php?patente=$patente';</script>";
	}

	return $resultado;
}


function eliminaDocumentoVehiculo($id,$patente)
{

	include '../include/conexionSistema.php';

	$sql			=	"select nombre_archivo from vehiculo_archivo_adjunto where id='$id'";
	$result 		=	mysqli_query($mysql,$sql);
	$array			=	mysqli_fetch_array($result);
	$nombre_archivo	=	$array['nombre_archivo'];


	unlink("../adjuntos/vehiculo/$nombre_archivo");

	$sql	=	"delete from vehiculo_archivo_adjunto where id  = '$id'";

	if(mysqli_query($mysql,$sql))
	{
		logTransaccion2($_SESSION['id_usuario'],"Elimina documento adjunto de patente $patente id_documento: id");

		$resultado= "<script>alert('Se ha eliminado el documento exitosamente');document.location.href='fichaVehiculo.php?patente=$patente';</script>";
	}
	else
	{
		$resultado= "<script>alert('Ocurrio un error al ejecutar la transaccion');document.location.href='fichaVehiculo.php?patente=$patente';</script>";
	}

	return $resultado;
}


function eliminarArchivoAdjuntoVehiculo($id){

	include '../include/conexionSistema.php';

	unlink("../adjuntos/vehiculo/$nombre_archivo");

	$sql	=	"update vehiculo_archivo_adjunto set nombre_archivo='' where id='$id'";

	if(mysqli_query($mysql,$sql))
	{
		logTransaccion2($_SESSION['id_usuario'],"Elimina documento adjunto de patente id_documento: id");


		$resultado="<table width='100%'><tr><td bgcolor='green'><font color='white'>Se han realizado los cambios exitosamete!</font></td></tr></table>";
	}
	else
	{
		$resultado="<table width='100%'><tr><td bgcolor='red'><font color='white'>Ocurrio un error al guardar los datos!</font></td></tr></table>";
	}

	return $resultado;

}


function guardarPuntoVenta($id_articulo){
	include '../include/conexionSistema.php';
	$sql="insert into punto_venta (id_punto_venta,id_articulo,descripcion,costo_unitario,id_usuario,fecha) values(null,'$id_articulo',(select descripcion from articulo where id_articulo='$id_articulo'),(select costo_promedio from articulo where id_articulo='$id_articulo'),'".$_SESSION['id_usuario']."',now())";
	mysqli_query($mysql,$sql);
}

function eliminarItemPuntoVenta($id_punto_venta){
	include '../include/conexionSistema.php';
	$sql	=	"delete from punto_venta where id_punto_venta='$id_punto_venta'";
	mysqli_query($mysql,$sql);
}

function cancelarVenta(){
	include '../include/conexionSistema.php';
	$sql="delete from punto_venta where id_venta=0";
	mysqli_query($mysql,$sql);
}

function guardarVentaPuntoVenta(){
	include '../include/conexionSistema.php';
	$sql		="select max(id_venta)+1 id_venta from punto_venta";
	$result 	= mysqli_query($mysql,$sql);
	$array		= mysqli_fetch_array($result);
	$id_venta	= $array['id_venta'];

	$sql	="update punto_venta set id_venta=$id_venta where id_venta=0;";
	mysqli_query($mysql,$sql);

	return $id_venta;
}

function tiempoEsperaTaller($numero_interno)
{
	include '../include/conexionSistema.php';

	$sql="select fecha_inicio_espera,fecha_termino_espera from orden_trabajo where numero_interno='$numero_interno'";
	$result = mysqli_query($mysql,$sql);
	$array = mysqli_fetch_array($result);

	$fecha_inicio_espera = $array['fecha_inicio_espera'];
	$fecha_termino_espera = $array['fecha_termino_espera'];

	if($fecha_inicio_espera<>'0000-00-00 00:00:00' and $fecha_termino_espera<>'0000-00-00 00:00:00')
	{
		$sql				=	"SELECT SEC_TO_TIME((TIMESTAMPDIFF(MINUTE , '$fecha_inicio_espera' ,'$fecha_termino_espera' ))*60) AS tiempo_en_proceso";
		$result 			= 	mysqli_query($mysql,$sql);
		$array  			= 	mysqli_fetch_array($result);
		$tiempo_en_proceso	= $array['tiempo_en_proceso'];
	}

	if($fecha_termino_espera=='0000-00-00 00:00:00')
	{
		$sql				=	"SELECT SEC_TO_TIME((TIMESTAMPDIFF(MINUTE , '$fecha_inicio_espera' ,now()))*60) AS tiempo_en_proceso";
		$result 			= 	mysqli_query($mysql,$sql);
		$array  			= 	mysqli_fetch_array($result);
		$tiempo_en_proceso	= $array['tiempo_en_proceso'];
	}

	return $tiempo_en_proceso;
}




//guarda detalle de OT
function guardarDetalleOT($numero_interno,$fecha_entrada_taller,$fecha_salida_taller,$fecha_termino_taller){
	include '../include/conexionSistema.php';
	$sql="update orden_trabajo set fecha_entrada_taller='$fecha_entrada_taller',fecha_salida_taller='$fecha_salida_taller',fecha_termino_taller='$fecha_termino_taller' where numero_interno='$numero_interno'";
	if(mysqli_query($mysql,$sql)){

		$resultado="<table width='100%'><tr><td bgcolor='green'><font color='white'>Se han guardado los datos exitosamente!</font></td></tr></table>";
		logTransaccion2($_SESSION['id_usuario'],"Modifica OT:$numero_interno entrada taller:$fecha_entrada_taller salida taller:$fecha_salida_taller termino taller:$fecha_termino_taller");

	}else{
		$resultado="<table width='100%'><tr><td bgcolor='red'><font color='white'>Ocurrio un error al guardar los datos!</font></td></tr></table>";
	}

	return $resultado;
}

function rechazarOT($numero_interno,$comentario_rechazo_defecto_vehiculo,$id_motivo_rechazo){

	include '../include/conexionSistema.php';

	$sql="update orden_trabajo set estado='RECHAZADA',comentario_rechazo_defecto_vehiculo='".strtoupper($comentario_rechazo_defecto_vehiculo)."',id_motivo_rechazo='$id_motivo_rechazo' where numero_interno='$numero_interno'";

	if(mysqli_query($mysql,$sql)){

		//consulta si debe notificar al usuario cuando rechace la OT
		$sql				=	"select opcion,
								 (select correo from usuario where id_usuario in (select digitador from orden_trabajo where numero_interno='$numero_interno')) correo_digitador
								 from parametro
								 where parametro='notifica_usuario_rechazo_ot'
								 and proceso='OT'";
		$result2			= 	mysqli_query($mysql,$sql);
		$array2				=	mysqli_fetch_array($result2);
		$opcion 			=	$array2['opcion'];
		$correo_digitador	=	$array2['correo_digitador'];

		if($opcion=='SI'){
			mail("$correo_digitador","RECHAZO OT:$numero_interno","Estimada/o, <br><br>El usuario ".$_SESSION['id_usuario']." ha rechazado la OT $numero_interno. <br><br>Comentario: $comentario_rechazo_defecto_vehiculo",'Content-type: text/html; charset=utf-8');
		}

		$resultado="<table width='100%'><tr><td bgcolor='green'><font color='white'>Se ha rechazado la OT $numero_interno exitosamente!</font></td></tr></table><script>opener.location.reload();</script>";

		logTransaccion2($_SESSION['id_usuario'],"Rechaza OT:$numero_interno id_motivo_rechazo:$id_motivo_rechazo comentario:$comentario");

		//devuelve las unidades a la bodega de origen

		//consulta cual es la bodega de origen
		$sql		=	"select id_bodega from orden_trabajo where numero_interno='$numero_interno'";
		$result 	= 	mysqli_query($mysql,$sql);
		$array  	= 	mysqli_fetch_array($result);
		$id_bodega 	= 	$array['id_bodega'];

		//lista el detalle de la OT y consulta si hay stock del item en la bodega de origen
		$sql		=	"select ota.id_articulo, ota.cantidad,
						(select count(id_articulo) from existencia where id_bodega='$id_bodega' and id_articulo=ota.id_articulo) hay_stock
						from orden_trabajo_articulo ota where ota.numero_interno='$numero_interno'";
		$result 	= 	mysqli_query($mysql,$sql);
		while($row  = 	mysqli_fetch_array($result)){

				//devuelve las unidades a la bodega de origen
				if($row['hay_stock']>0){
					$sql="update existencia set cantidad = cantidad + ".$row['cantidad']." where id_bodega='$id_bodega' and id_articulo='".$row['id_articulo']."'";
				}
				else{
					$sql="insert into existencia (id,id_articulo,descripcion,id_bodega,cantidad) values(null,'".$row['id_articulo']."',(select descripcion from articulo where id_articulo='".$row['id_articulo']."'),'$id_bodega','".$row['cantidad']."')";
				}

				mysqli_query($mysql,$sql);


				logTransaccion2($_SESSION['id_usuario'],"Devuelve Unidades por Rechazo de OT:$numero_interno id_articulo:".$row['id_articulo']." cantidad:".$row['cantidad']." id_bodega:$id_bodega");
		}


	}else{
		$resultado="<table width='100%'><tr><td bgcolor='red'><font color='white'>Ocurrio un error al rechazar la OT!</font></td></tr></table><script>opener.location.reload();</script>";
	}

	return $resultado;
}



function devolverUnidades($numero_interno,$id_articulo,$cantidad_devolver){

	include '../include/conexionSistema.php';

	$sql		=	"select ot.id_bodega,
							(select count(id_articulo) from existencia where id_bodega=ot.id_bodega and id_articulo='$id_articulo')hay_stock
							from orden_trabajo ot
							where ot.numero_interno='$numero_interno'";
	$result 	= 	mysqli_query($mysql,$sql);
	$array		= 	mysqli_fetch_array($result);
	$id_bodega	= 	$array['id_bodega'];
	$hay_stock	= 	$array['hay_stock'];

	if($hay_stock>0){
		$sql="update existencia set cantidad = cantidad + $cantidad_devolver where id_bodega='$id_bodega' and id_articulo = '$id_articulo'";
	}else{
		$sql="insert into existencia (id,id_articulo,descripcion,id_bodega,cantidad) values(null,'$id_articulo',(select descripcion from articulo where id_articulo='$id_articulo'),'$id_bodega','$cantidad_devolver')";
	}

	mysqli_query($mysql,$sql);

	if($cantidad_devolver>0){
	registrarKardex($numero_interno,$id_articulo,$cantidad_devolver,'INGRESO','DEVOLUCION UNIDADES OT',$id_bodega);
	}

	logTransaccion2($_SESSION['id_usuario'],"Devuelve Unidades OT:$numero_interno id_articulo:$id_articulo cantidad:$cantidad_devolver id_bodega:$id_bodega");

}


function registrarAjuste($codigo_movimiento,$id_existencia,$cantidad_ajustar,$motivo_ajuste){


	/*
		primero debe consultar si se debe validar el ajuste
		si no lo valida nadie el ajuste debe pasar de inmediato

		si se aprueba el primer estado debe ser pendiente aprobacion
		segundo estado: aprobado o rechazado

		no puede quedar un saldo negativo
	*/

	include '../include/conexionSistema.php';

	$sql			=	"select * from workflow where codigo_movimiento = '$codigo_movimiento' and estado='ACT'";
	$result			= 	mysqli_query($mysql,$sql);
	$array 		 	= 	mysqli_fetch_array($result);
	$notifica_correo=	$array['notifica_correo'];

	if($array['id_workflow']){
		//asigna el estado del documento
		$estado		=	"PENDIENTE APROBACION";
	}else{
		$estado		=	"PROCESADA";
	}


	$sql			=	"insert into guia (codigo_movimiento,descripcion_movimiento,fecha_ingreso,digitador,estado,fecha_digitacion,razon_emision) values('$codigo_movimiento',(select descripcion from tipo_movimiento where codigo_movimiento='$codigo_movimiento'),now(),'".$_SESSION['id_usuario']."','PENDIENTE APROBACION',now(),'$motivo_ajuste')";
	mysqli_query($mysql,$sql);

	$sql			=	"select max(numero_interno) numero_interno from guia";
	$result 		=	mysqli_query($mysql,$sql);
	$array			=	mysqli_fetch_array($result);
	$numero_interno	=	$array['numero_interno'];

	$sql			=	"insert into guia_detalle (id_detalle,numero_interno,id_articulo,descripcion_articulo,id_bodega_origen,costo,cantidad) values(null,'$numero_interno',(select id_articulo from existencia where id='$id_existencia'),(select descripcion from existencia where id='$id_existencia'),(select id_bodega from existencia where id='$id_existencia'),(select costo_promedio from articulo where id_articulo in (select id_articulo from existencia where id='$id_existencia')),(select id_articulo from existencia where id='$id_existencia'));";
	if(mysqli_query($mysql,$sql)){
		$resultado	=	"<table width='100%'><tr><td bgcolor='green'><font color='white'>Se ha generado la Guia Numero $numero_interno por Ajuste de Inventario. Este documento ha quedado en estado $estado</font></td></tr></table><script>opener.location.reload();</script>";

		//consulta si requiere aprobacion
		if($notifica_correo=='ACT'){
			//busca el usuario que aprueba
			$cantidad_usuarios_aprueban = cantidadUsuariosAprueban($codigo_movimiento,$numero_interno);
			//notifica al usuario que aprueba
			notificaUsuario($codigo_movimiento,$numero_interno,$usuario_aprueba);
		}

	}else{
		$resultado	=	"<table width='100%'><tr><td bgcolor='green'><font color='white'>Ocurrio un error al generar la guia por Ajuste</font></td></tr></table><script>opener.location.reload();</script>";
		$sql		=	"delete from guia where numero_interno='$numero_interno'";
		mysqli_query($mysql,$sql);
	}

	return $resultado;
}

function ultimoOdometroOT($patente_vehiculo){

	/*esta funcion obtiene el odometro de la ultima mantencion*/
	include 			'../include/conexionSistema.php';
	$sql			= 	"select kilometraje from orden_trabajo where patente='$patente_vehiculo' order by numero_interno desc limit 1,1;";
	$result 		=	mysqli_query($mysql,$sql);
	$array			= 	mysqli_fetch_array($result);
	$kilometraje	=	$array['kilometraje'];

	if($kilometraje==0 or $kilometraje==null){
		$kilometraje="No hay datos anteriores!";
	}else{
		$kilometraje=number_format($kilometraje,0,'','.');
	}

	return 	$kilometraje;

}

function ultimaFechaOT($patente_vehiculo){

	/*esta funcion obtiene el odometro de la ultima mantencion*/
	include 		'../include/conexionSistema.php';
	$sql			= 	"select fecha_solicitud from orden_trabajo where patente='$patente_vehiculo' order by numero_interno desc limit 1,1;";
	$result 		=	mysqli_query($mysql,$sql);
	$array			= 	mysqli_fetch_array($result);
	$fecha_solicitud	=	$array['fecha_solicitud'];

	if($fecha_solicitud==0 or $fecha_solicitud==null){
		$fecha_solicitud="No hay datos anteriores!";
	}else{
		$fecha_solicitud=date('d-m-Y',strtotime($fecha_solicitud));
	}

	return 	$fecha_solicitud;

}


function ultimaOT($patente_vehiculo){

	/*esta funcion obtiene el odometro de la ultima mantencion*/
	include 			'../include/conexionSistema.php';
	$sql				= 	"select numero_interno from orden_trabajo where patente='$patente_vehiculo' order by numero_interno desc limit 1,1;";
	$result 			=	mysqli_query($mysql,$sql);
	$array				= 	mysqli_fetch_array($result);
	$numero_interno		=	$array['numero_interno'];

	if($numero_interno==0 or $numero_interno==null){
		$numero_interno="No hay datos anteriores!";
	}

	return 	$numero_interno;

}


function generaInventario($id_bodega,$descripcion_articulo,$cantidad){

	include '../include/conexionSistema.php';

	$id_inventario	= date('YmdHis');
	$sql			=	"select id_articulo from articulo where descripcion='$descripcion_articulo'";
	$result			=	mysqli_query($mysql,$sql);
	$array			= 	mysqli_fetch_array($result);
	$id_articulo	=	$array['id_articulo'];

	//elimina el registro de la tabla existencia
	$sql			=	"delete from existencia where id_bodega='$id_bodega' and id_articulo='$id_articulo'";
	mysqli_query($mysql,$sql);

	//inserta el nuevo inventario en la bodega
	$sql		="insert into existencia (id,id_articulo,descripcion,id_bodega,cantidad) values(null,'$id_articulo','$descripcion_articulo','$id_bodega','$cantidad');";
	if(mysqli_query($mysql,$sql)){
		$resultado	=	"<table width='100%'><tr><td bgcolor='green'><font color='white'>Se ha guardado el registro exitosamente!</font></td></tr></table><script>opener.location.reload();</script>";
	}else{
		$resultado	=	"<table width='100%'><tr><td bgcolor='red'><font color='white'>Ocurrio un error al guardar el registro!</font></td></tr></table><script>opener.location.reload();</script>";
	}

	registrarKardex($id_inventario,$id_articulo,$cantidad,'INVENTARIO','INVENTARIO',$id_bodega);

	logTransaccion2($_SESSION['id_usuario'],"Inventario $id_inventario id_bodega:$id_bodega id_articulo:$id_articulo descripcion:$descripcion_articulo cantidad:$cantidad");

	return $resultado;

}



function ingresarDestino($nombre,$direccion){

	include '../include/conexionSistema.php';
	include '../include/httpful.phar';

	$data 		= 	file_get_contents("http://maps.google.com/maps/api/geocode/json?address=".str_replace(' ','+',$direccion)."&sensor=false");
	$data 		= 	json_decode($data, true);
	$latitud 	=	$data['results'][0]['geometry']['location']['lat'];
	$longitud	=	$data['results'][0]['geometry']['location']['lng'];

	if($latitud=='' or $longitud==''){
			$resultado .= "<table width='100%'><tr><td bgcolor='red'><font color='white'>Ocurrio un error buscar la direccion $direccion !</font></td></tr></table>";
	}else{
		$sql	=	"insert into markers (id,name,address,lat,lng,type,fecha_digitacion) values(null,'$nombre','$direccion','$latitud','$longitud','ACT',now())";
		if(mysqli_query($mysql,$sql)){
			$resultado .= "<table width='100%'><tr><td bgcolor='green'><font color='white'>Se han registrado los datos exitosamente!</font></td></tr></table>";
		}else{
			$resultado .= "<table width='100%'><tr><td bgcolor='red'><font color='white'>Ocurrio un error al guardar los datos!</font></td></tr></table>";
		}
	}

	return $resultado;

}

function cerrarOt($numero_interno){

	include '../include/conexionSistema.php';
	$sql	=	"update orden_trabajo set fecha_termino_taller = now() , estado='CERRADA' where numero_interno='$numero_interno'";

	if(mysqli_query($mysql,$sql)){
	$resultado .= "<table width='100%'><tr><td bgcolor='green'><font color='white'>Se han registrado los datos exitosamente!</font></td></tr></table>";
	}else{
	$resultado .= "<table width='100%'><tr><td bgcolor='red'><font color='white'>Ocurrio un error al guardar los datos!</font></td></tr></table>";
	}

	logTransaccion2($_SESSION['id_usuario'],"Cierra la OT $numero_interno");

	return $resultado;
}



function QuitarTiles($nombre_archivo) {
$no_permitidas		= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
$permitidas			= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
$nombre_archivo	 	= str_replace($no_permitidas, $permitidas ,$nombre_archivo);
return $nombre_archivo;
}


function adjuntarArchivo($carpeta,$numero_interno,$nombre_archivo){
	include '../include/conexionSistema.php';
	$sql	=	"insert into archivo_adjunto (id_archivo_adjunto,carpeta,numero_interno,nombre_archivo,id_usuario,fecha_subida)values(null,'$carpeta','$numero_interno','$nombre_archivo','".$_SESSION['id_usuario']."',now());";
	mysqli_query($mysql,$sql);
}

function eliminarArchivoAdjunto($id_archivo_adjunto,$carpeta,$nombre_archivo){
	include "../include/conexionSistema.php";
	$archivo	 = "../adjuntos/$carpeta/".$nombre_archivo;
	unlink($archivo);
	if(!file_exists($archivo)){
		$sql	=	"delete from archivo_adjunto where id_archivo_adjunto='$id_archivo_adjunto'";
		mysqli_query($mysql,$sql);
	}
}


function agregarPautaMantencion($marca,$modelo,$descripcion,$kilometraje,$motor){

	include '../include/conexionSistema.php';

	$sql	=	"insert into pauta_mantencion (id_pauta_mantencion,marca,modelo,descripcion,kilometraje,motor) values(null,'".strtoupper($marca)."','".strtoupper($modelo)."','".strtoupper($descripcion)."','$kilometraje','$motor');";
	if(mysqli_query($mysql,$sql)){
	$resultado .= "<table width='100%'><tr><td bgcolor='green'><font color='white'>Se han registrado los datos exitosamente!</font></td></tr></table>";
	logTransaccion2($_SESSION['id_usuario'],"Agrega la pauta de mantencion marca:$marca modelo:$modelo descripcion:$descripcion kilometraje:$kilometraje motor:$motor");

	}else{
	$resultado .= "<table width='100%'><tr><td bgcolor='red'><font color='white'>Ocurrio un error al guardar los datos!</font></td></tr></table>";
	}

	return $resultado;
}


function modificarPautaMantencion($id_pauta_mantencion,$marca,$modelo,$descripcion,$kilometraje,$motor,$tipo_mantencion){

	include '../include/conexionSistema.php';

	$sql	=	"update pauta_mantencion set marca = '$marca', modelo='$modelo',descripcion='$descripcion',	kilometraje='$kilometraje', motor =	'$motor', tipo_mantencion='$tipo_mantencion' where id_pauta_mantencion='$id_pauta_mantencion'";

	if(mysqli_query($mysql,$sql)){
		$resultado .= "<table width='100%'><tr><td bgcolor='green'><font color='white'>Se han registrado los datos exitosamente!</font></td></tr></table>";
		logTransaccion2($_SESSION['id_usuario'],"Modifica la pauta de mantencion marca:$marca modelo:$modelo descripcion:$descripcion kilometraje:$kilometraje motor:$motor tipo_mantencion:$tipo_mantencion");
	}else{
		$resultado .= "<table width='100%'><tr><td bgcolor='red'><font color='white'>Ocurrio un error al guardar los datos!</font></td></tr></table>";
	}

	return $resultado;
}

function agregarDetallePautaMantencion($id_pauta_mantencion,$tipo_mantencion,$detalle_mantencion,$kilometraje,$descripcion_articulo,$cantidad_unidades){

        include '../include/conexionSistema.php';

        $sql            =   "select id_articulo from articulo where descripcion='$descripcion_articulo'";
        $result         =   mysqli_query($mysql,$sql);
        $array          =   mysqli_fetch_array($result);
        $id_articulo    =   $array['id_articulo'];



        $sql    =   "insert into pauta_mantencion_detalle (id_pauta_mantencion_detalle,id_pauta_mantencion,tipo_mantencion,detalle_mantencion,kilometraje,id_articulo,descripcion_articulo,cantidad_unidades)values(null,'$id_pauta_mantencion','$tipo_mantencion','$detalle_mantencion','$kilometraje','$id_articulo','$descripcion_articulo','$cantidad_unidades');";
        if(mysqli_query($mysql,$sql)){
            $resultado .= "<table width='100%'><tr><td bgcolor='green'><font color='white'>Se han registrado los datos exitosamente!</font></td></tr></table>";

            $resultado .= "<script>opener.location.reload();</script>";
            logTransaccion2($_SESSION['id_usuario'],"Agregar nuevo item a la pauta de mantencion $id_pauta_mantencion tipo:$tipo_mantencion detalle:$detalle_mantencion kilometraje:$kilometraje cantidad_unidades:$cantidad_unidades");
        }else{
            $resultado .= "<table width='100%'><tr><td bgcolor='red'><font color='white'>Ocurrio un error al guardar los datos!</font></td></tr></table>";
        }

        return $resultado;

}



function modificarDetallePautaMantencion($id_pauta_mantencion,$id_pauta_mantencion_detalle,$tipo_mantencion,$detalle_mantencion,$kilometraje,$descripcion_articulo,$cantidad_unidades){

        include '../include/conexionSistema.php';

        $sql            =   "select id_articulo from articulo where descripcion='$descripcion_articulo'";
        $result         =   mysqli_query($mysql,$sql);
        $array          =   mysqli_fetch_array($result);
        $id_articulo    =   $array['id_articulo'];



         $sql    =   "update pauta_mantencion_detalle   set tipo_mantencion='$tipo_mantencion', detalle_mantencion='$detalle_mantencion',  kilometraje='$kilometraje', id_articulo='$id_articulo',descripcion_articulo='$descripcion_articulo',cantidad_unidades='$cantidad_unidades'  where id_pauta_mantencion_detalle='$id_pauta_mantencion_detalle'";

        if(mysqli_query($mysql,$sql)){
            $resultado .= "<table width='100%'><tr><td bgcolor='green'><font color='white'>Se han modificado los datos exitosamente!</font></td></tr></table>";
            $resultado .= "<script>opener.location.reload();</script>";
            logTransaccion2($_SESSION['id_usuario'],"Modifica item a la pauta de mantencion $id_pauta_mantencion tipo:$tipo_mantencion detalle:$detalle_mantencion kilometraje:$kilometraje articulo:$descripcion_articulo cantidad_unidades:$cantidad_unidades");
        }else{
            $resultado .= "<table width='100%'><tr><td bgcolor='red'><font color='white'>Ocurrio un error al guardar los datos!</font></td></tr></table>";
        }



        return $resultado;

}



function eliminarPautaMantencionDetalle($id_pauta_mantencion,$id_pauta_mantencion_detalle){
    include '../include/conexionSistema.php';

    $sql    =   "delete from pauta_mantencion_detalle where id_pauta_mantencion_detalle='$id_pauta_mantencion_detalle'";

    if(mysqli_query($mysql,$sql)){
            $resultado .= "<table width='100%'><tr><td bgcolor='green'><font color='white'>Se han eliminado los datos exitosamente!</font></td></tr></table>";
            $resultado .= "<script>opener.location.reload();</script>";
            logTransaccion2($_SESSION['id_usuario'],"Eliminar item de pauta de mantencion id_pauta_mantencion_detalle:$id_pauta_mantencion_detalle");
    }else{
            $resultado .= "<table width='100%'><tr><td bgcolor='red'><font color='white'>Ocurrio un error al guardar los datos!</font></td></tr></table>";
    }

    return $resultado;

}


function eliminarPautaMantencion($id_pauta_mantencion){
    include '../include/conexionSistema.php';

    $sql    =   "delete from pauta_mantencion_detalle where id_pauta_mantencion='$id_pauta_mantencion'";
    if(mysqli_query($mysql,$sql)){
        $sql=   "delete from pauta_mantencion where id_pauta_mantencion='$id_pauta_mantencion'";
        if(mysqli_query($mysql,$sql)){
        $resultado.="<script>alert('Se ha eliminado la pauta de mantencion exitosamente!');document.location.href='mantenedorPautasMantencion.php';</script>";
        }else{
        $resultado.="<table><tr><td bgcolor='red'><font color='white'>Ocurrio un error al eliminar los datos!</font></td></tr></table>";
        }
    }
    return $resultado;
}


function registrarTurno($id_proyecto,$ano,$mes,$dia,$rut_persona,$sigla,$hn,$hen,$hee,$hab,$bono){
     include '../include/conexionSistema.php';

     $sql       =      "select count(*) existe from proyecto_registro_turno where id_proyecto='$id_proyecto' and rut_persona='$rut_persona' and fecha_turno='".$ano."-".$mes."-$dia'";
     $result    =       mysqli_query($mysql,$sql);
     $array     =       mysqli_fetch_array($result);

     if($array['existe']==0){

        if($sigla<>'' or $hn<>'' or $hen<>'' or $hee<>'' or $hab<>'' or $bono<>''){
                $sql="insert into proyecto_registro_turno(id_proyecto,rut_persona,fecha_turno,sigla,horas_trabajadas,horas_extra_trabajadas,horas_extra_especiales,horas_extra_acuerdo,bono_pagar,usuario_digitador,fecha_digitacion)
				values ('$id_proyecto','$rut_persona','".$ano."-".$mes."-".$dia."','$sigla','$hn','$hen','$hee','$hab','$bono','".$_SESSION['id_usuario']."',now())";
                mysqli_query($mysql,$sql);
				$c++;
        }
	}else{

				$sql="update proyecto_registro_turno set sigla='$sigla', horas_trabajadas='$hn', horas_extra_trabajadas='$hen', horas_extra_especiales='$hee', horas_extra_acuerdo='$hab', bono_pagar='$bono', usuario_modifica='".$_SESSION['id_usuario']."', fecha_modifica=now() where id_proyecto='$id_proyecto' and rut_persona='$rut_persona' and fecha_turno='".$ano."-".$mes."-".$dia."'";
				mysqli_query($mysql,$sql);
				$c++;
	 }



	return $c;
}


function asignarMecanicoTodosItems($rut_mecanico_todos,$numero_interno){

	include '../include/conexionSistema.php';

	$sql	=	"update orden_trabajo_defecto set rut_mecanico='$rut_mecanico_todos',nombre_mecanico=(select nombre from empleado where rut='$rut_mecanico_todos') where numero_interno='$numero_interno'";
	if(mysqli_query($mysql,$sql)){
        $resultado.="<table width='100%'><tr><td bgcolor='green'><font color='white'>Se han registrado los datos exitosamente!</font></td></tr></table>";
	}else{
        $resultado.="<table width='100%'><tr><td bgcolor='red'><font color='white'>Ocurrio un error al guardar los datos!</font></td></tr></table>";
    }
	return $resultado;
}



function horasTotalesDiaProyecto($id_proyecto,$nombre_dia){

	include '../include/conexionSistema.php';

	$sql	=	"SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(hora_termino_".$nombre_dia." - hora_inicio_".$nombre_dia." - colacion_".$nombre_dia."))) horas FROM proyecto_persona where id_proyecto='$id_proyecto'";
	$result =	mysqli_query($mysql,$sql);
	$array  =	mysqli_fetch_array($result);
	$horas 	=	$array['horas'];

	return $horas;
}

function guia_modificarItem($id_detalle,$descripcion_articulo,$id_bodega_origen,$id_bodega_destino,$cantidad,$costo){

	include '../include/conexionSistema.php';

	$sql="update guia_detalle set descripcion_articulo='$descripcion_articulo', id_bodega_origen='$id_bodega_origen', id_bodega_destino='$id_bodega_destino', costo='".str_replace(",","",str_replace(".","",$costo))."', cantidad='".str_replace(",","",str_replace(".","",$cantidad))."' where id_detalle='$id_detalle'";
	if(mysqli_query($mysql,$sql)){
        $resultado.="<table width='100%'><tr><td bgcolor='green'><font color='white'>Se han registrado los datos exitosamente!</font></td></tr></table>";
		$resultado.="<script>opener.location.reload();</script>";
	}else{
        $resultado.="<table width='100%'><tr><td bgcolor='red'><font color='white'>Ocurrio un error al guardar los datos!</font></td></tr></table>";
    }

	$sql			=	"select numero_interno from guia_detalle where id_detalle='$id_detalle'";
	$result 		=	mysqli_query($mysql,$sql);
	$array			=	mysqli_fetch_array($result);
	$numero_interno	=	$array['numero_interno'];

	recalcularTotalesGuia($numero_interno);

	return $resultado;
}



 function modificarMarca($id_marca,$marca,$estado){

	 include '../include/conexionSistema.php';

	 $sql="update vehiculo_marca set marca='$marca',estado='$estado' where id_marca='$id_marca'";
	 if(mysqli_query($mysql,$sql)){
        $resultado.="<table width='100%'><tr><td bgcolor='green'><font color='white'>Se han registrado los cambios exitosamente!</font></td></tr></table>";
	 }else{
        $resultado.="<table width='100%'><tr><td bgcolor='red'><font color='white'>Ocurrio un error al guardar los datos!</font></td></tr></table>";
    }

	 return $resultado;
 }


 function eliminarMarca($id_marca,$marca){

	 include '../include/conexionSistema.php';

	 $sql="select
				(select count(*) from vehiculo where id_marca='".$id_marca."') vehiculos,
				(select count(*) from vehiculo_modelo where id_marca='".$id_marca."')modelos";
	 $result = mysqli_query($mysql,$sql);
	 $array	 = mysqli_fetch_array($result);


	 if($array['vehiculos']>0 or $array['modelo']){
		 $resultado.="<script>alert('No puede eliminar esta marca ya que tiene ".$array['vehiculos']." vehiculos asociados y ".$array['modelos']." modelos asociados');</script>";
	 }else{
		$sql="delete from vehiculo_marca where id_marca='".$id_marca."'";
		 if(mysqli_query($mysql,$sql)){
		$resultado.="<script>alert('Se ha eliminado el registro exitosamente!');
							 document.location.href='mantenedorMarcas.php';</script>";
		}else{
		$resultado.="<script>alert('Ocurrio un error al eliminar el registro!');</script>";
		}

	 }

	 return $resultado;

 }


 function agregarMarca($id_marca,$marca){

	 include '../include/conexionSistema.php';

	 $sql	=	"select count(*) cantidad from vehiculo_marca where id_marca='$id_marca'";
	 $result=	mysqli_query($mysql,$sql);
	 $array =	mysqli_fetch_array($result);

	 if($array['cantidad']>0){
		  $resultado.="<table width='100%'><tr><td bgcolor='red'><font color='white'>El ID $id_marca ya se encuentra en la base de datos. Intente con un ID que no exista en la base de datos!</font></td></tr></table>";
	}else{
		$sql="insert into vehiculo_marca values('$id_marca','".strtoupper($marca)."','ACT');";
		if(mysqli_query($mysql,$sql)){
			$resultado.="<table width='100%'><tr><td bgcolor='green'><font color='white'>Se han registrado los datos exitosamente!</font></td></tr></table>";
		}else{
			$resultado.="<table width='100%'><tr><td bgcolor='red'><font color='white'>Ocurrio un error al guardar los datos!</font></td></tr></table>";
		}
	 }

	 return $resultado;
 }

 function agregarModelo($id_marca,$modelo){


	  include '../include/conexionSistema.php';

	    $sql	=	"insert into vehiculo_modelo values(null,'$id_marca','$modelo','ACT');";
		if(mysqli_query($mysql,$sql)){
			$resultado.="<table width='100%'><tr><td bgcolor='green'><font color='white'>Se han registrado los datos exitosamente!</font></td></tr></table>";
		}else{
			$resultado.="<table width='100%'><tr><td bgcolor='red'><font color='white'>Ocurrio un error al guardar los datos!</font></td></tr></table>";
		}

	return $resultado;
 }


function eliminarModelo($id_modelo){
	 include '../include/conexionSistema.php';
	 $sql="select count(*) vehiculos from vehiculo where id_modelo='".$id_modelo."'";
	 $result = mysqli_query($mysql,$sql);
	 $array	 = mysqli_fetch_array($result);


	 if($array['vehiculos']>0){
		 $resultado.="<script>alert('No puede eliminar este modelo ya que tiene ".$array['vehiculos']." vehiculos asociados');</script>";
	 }else{
		echo $sql="delete from vehiculo_modelo where id_modelo='".$id_modelo."'";
		 if(mysqli_query($mysql,$sql)){
		$resultado.="<script>alert('Se ha eliminado el registro exitosamente!');
							 document.location.href='mantenedorModelos.php';</script>";
		}else{
		$resultado.="<script>alert('Ocurrio un error al eliminar el registro!');</script>";
		}

	 }

	 return $resultado;

}


function consultaApruebaOC($numero_interno)
{
	include '../include/conexionSistema.php';


	//consulta si hay un documento pendiente de aprobar por parte del usuario.
	//si el resultado de la consulta es 1 el usuario tiene pendiente aprobar el documento

	  $sql	=	"select
					count(id_workflow) pendiente
				 from workflow
				 where id_workflow in (select id_workflow
										from workflow_usuario
										where id_usuario='".$_SESSION['id_usuario']."'
										and monto_inicio<=(select total
															from orden_compra
															where numero_interno='$numero_interno'
															and estado='PENDIENTE APROBACION')
										and monto_termino>=(select total
															from orden_compra
															where numero_interno='$numero_interno'
															and estado='PENDIENTE APROBACION' ))
					and descripcion='orden de compra'
					and estado='ACT'
					and 1 <> (select count(*)
								from
								workflow_documento
								where numero_interno='$numero_interno'
								and referencia='orden_compra');";

	$result 	=	mysqli_query($mysql,$sql);
	$array		=	mysqli_fetch_array($result);
	$pendiente 	=	$array['pendiente'];

	if($pendiente==1)
	{
		$resultado		="<a href='#' onClick='aprobarDocumento(".$numero_interno.",`orden_compra`)'>
							<input type='image' src='../images/aprobar2.png'>
						  </a>";

	}
	else
	{
		$resultado		="";
	}

	return $resultado;
}



?>