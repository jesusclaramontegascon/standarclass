<?php
class conexiones
{ 
  var $servidor;var $usuario; var $contrasena;
  var $tipo;var $tabla;var $basededatos;
  var $borrado_cascada;
  function conexiones()
  {}
  
 function redirection($page)
   { 
   echo "<script>window.location='".$page."'</script>"; 
   exit();
   }
  
  function database_access($server,$user,$pass,$database)
  { 
    $this->servidor=$server;$this->usuario=$user;$this->contrasena=$pass;
    $this->basededatos=$database;
	
	if ($this->servidor=="")
	{$this->servidor="localhost";
	}else{$this->servidor=$server;}

  	$conexion=mysql_connect($this->servidor,$this->usuario=$user,$this->contrasena=$pass);
	$base=mysql_select_db($this->basededatos,$conexion);
	
	if ($base && $conexion){$conectado=1;}else{$conectado=0;}
	return $conectado;
  } 
   
     
  function user_access($user_table,$pass_table)
  { $sql="select usuario,paso,tipo from usuarios where usuario='".$user_table."'";
    
    $query=mysql_query($sql);$registro=mysql_fetch_array($query);
    $usertabla=$registro["usuario"];$paso_tabla=$registro["paso"];
	$type=$registro["tipo"];$block=$registro["bloqueado"];
	
	if ($usertabla!=$user_table)
	{$mensaje="El usuario no se encuentra en nuestra base de datos";}
	else
	{  if ($usertabla==$user_table)
	   {    if ($paso_tabla!=$pass_table)
		   {$mensaje="El password del usuario $usertabla no es el correcto";}
		   else{
		         if ($block==1){$mensaje="El usuario esta bloqueado mire su correo y desbloquee la cuenta";}
				 else
				 {$mensaje="Bienvenidos al gestor de contenidos de su web";}
		     }
	    }
	}
	$_SESSION["user"]=$usertabla;
   $_SESSION["type"]=$type;
  return $mensaje; 
  } //functiono
  
    function tabla_parametrizada_convectores($vectorcondatos,$iniciovector,$finalvector)
  { 
    $vector_parametrizado=array();
	  $contador=0; 
   foreach ($vectorcondatos as $clave=> $datos)
   { if ($contador>=$iniciovector && $contador<$finalvector){
	$vector_parametrizado[]=$vectorcondatos;}
	$contador++; 
	}
   return $vector_parametrizado;
  }
  
   function tabla_parametrizada($nombre_tabla,$inicio,$registros_pagina)
  { 
    $claveprimaria="Describe ".$nombre_tabla."";$ejecutar2=mysql_query($claveprimaria);
	$contador=0; 
	while($registro2 = mysql_fetch_row($ejecutar2)){ 
	$contador++; if ($contador==1){$campoclave=$registro2[0];break;} 	
    }
    $consulta_sql="select * from ".$nombre_tabla." order by $campoclave limit ".$inicio.",".$registros_pagina." ";
 
    $ejecutar=mysql_query($consulta_sql);
	$tabla_parametrizada=array();
	
	while ($lectura=mysql_fetch_array($ejecutar))
	{$tabla_parametrizada[]=$lectura;}
	
	return $tabla_parametrizada;
  }
  
  function usuario_repetido($usuario_repeat)
  {	$sql="select usuario from usuarios where usuario='".$usuario_repeat."'";
   
    $query=mysql_query($sql);
    
	$suich=0;
	while($registro = mysql_fetch_array($query))
	{ if ($registro["usuario"]==$usuario_repeat){$suich=1;
	  break;}
	}
	
	if ($suich==1)
	{$mensaje="repetido";}
	else
	{  if ($suich==0){$mensaje="valido";}
	}
   return $mensaje;  
  }
  
  function usuarios_primera_vez($nombre_usuario,$pasword_usuario,$cuenta_correo)
  {  $largura_usuario=strlen($nombre_usuario);$largura_pasword=strlen($pasword_usuario);

     $largura_correo=strlen($cuenta_correo);
      	
		
      if ($largura_usuario==1 || $largura_usuario>15)
	 {$mensaje="El usuario debe tener entre 2 y 15 caracteres";}
	 else
	 {  if($largura_pasword==1 || $largura_pasword>10){$mensaje="La contraseña debe tener entre 1 y 10 caracteres";}
	 	else {  
		       if ($largura_correo==1 || $$largura_correo>20)
			   {$mensaje="La cuenta de correo tiene que tener entre 1 y 20 caracteres";} 
		        else{$mensaje="correcto";}    
		
		     }
	 }
	 return $mensaje;
  }
  function array_tables($nametable)
  { $claveprimaria="Describe ".$nametable."";

	$ejecutar2=mysql_query($claveprimaria);
	$contador=0;
	while($registro2 = mysql_fetch_row($ejecutar2))
	{$contador++; if ($contador==1){$campoclave=$registro2[0];break;} 	
	}
   
	$q = "SELECT * FROM $nametable order by $campoclave";
   $result = mysql_query ($q);
   $archivos = array();
    while($fila =@mysql_fetch_array($result))
	{$archivos[] = $fila;}
      return $archivos;
  }
  
  function calculoregistros($table,$pages)
  { $consulta = "SELECT count(*) as total FROM $table";	
	$exe = mysql_query ($consulta);$register=mysql_fetch_array($exe);
	$paginas = ceil($register["total"]/$pages);
	return $paginas;
  }
  
  function calculoregistros_sql_concreto($consulta_sql,$pages)
  { 
	$exe = mysql_query ($consulta_sql);$register=mysql_fetch_row($exe);
	$resultado=$register[0];
	$paginas = ceil($resultado/$pages);
	return $paginas;
  }
  

  function type_user($nombre_user)
  { 
   $sql="select usuario,tipo,ultimo_acceso from usuarios where usuario='".$nombre_user."'";

   $query=mysql_query($sql);$registro=@mysql_fetch_array($query);
   $user=$registro["usuario"];$type=$registro["tipo"];$acceso=$registro["ultimo_acceso"];
	
   $_SESSION["user"]=$user;
   $_SESSION["type"]=$type;
   $_SESSION["lastimes"]=$acceso;
   $usuario_tipo_ultima_vez=array($_SESSION["user"],$_SESSION["type"],$_SESSION["lastimes"]);
   return $usuario_tipo_ultima_vez;
   
  }
  
  function generar_hash_user($id)
   {$seed = 'adicae';
    $hash = sha1(uniqid($seed . mt_rand(), true));
    $hash = substr($hash, 0, 10);
    $q = 'UPDATE usuarios SET hash = "'.$hash.'", activacion = "0" WHERE Id = "'.$id.'"';
    $result = mysql_query($q, $GLOBALS['conn']) or die(mysql_error($GLOBALS['conn']));
    return $hash;
   }

   function valid_hash($id, $hash)
   { if($id == '' || $hash == '') return false;
     $q = 'SELECT * FROM usuarios WHERE Id = "'.$id.'" AND hash = "'.$hash.'" ';
     $r =  mysql_query($q, $GLOBALS['conn']) or die(mysql_error($GLOBALS['conn']));
     $usuario = mysql_fetch_assoc($r);
    //var_dump($usuario);
     return $usuario;
   }
 
  
  function comprobaciones_user($usuario,$pasguor)
  {      
	$largura_usuario=strlen($usuario);
    $largura_pasguor=strlen($pasguor);
	 if ($largura_usuario==0)
	 { $mensaje_error="Debe de Rellenar el campo del usuario para el formulario";return $mensaje_error;}
	else
	{ if ($largura_pasguor==0){
	  $mensaje_error="Debe de Rellenar el campo de la contraseña para el formulario";return $mensaje_error;}
	  else{$mensaje_error="Correcto"; return $mensaje_error;}
	}
  } 
  
  function romper_session($desconectar)
  {  
     if ($desconectar=="si"){
      @session_destroy();$sesion_destruida="si";}
	  return $sesion_destruida;
  }
  function total_registros_tabla($tabla)
  { $consulta="select * from ".$tabla."";
    $ejecutar = mysql_query($consulta);
   $numero_columnas = mysql_num_rows($ejecutar);
   return $numero_columnas;
  }
  function operations_sql($action,$id_table,$vector_valores,$campo_tocable)
  { 
    
    $tablas_actuales=array();
    $consulta_tablas_actuales="show tables";
	$ejecutar=mysql_query($consulta_tablas_actuales);

	 while($registro = mysql_fetch_array($ejecutar))
	{$tablas_actuales[] = $registro;}
	$elementos_vector=count($tablas_actuales);

	foreach($tablas_actuales as $id => $tablas)
	{ 
	  if ($tablas['Tables_in_jesus_web']==$id_table){$tabla_seleccionada=$tablas['Tables_in_jesus_web'];break;}
	}
	$consulta_campos_tablas="Describe ".$tabla_seleccionada."";
	$ejecutar2=mysql_query($consulta_campos_tablas);
	
	$campos_tabla_seleccionada=array();
	while($registro2=@mysql_fetch_array($ejecutar2))
	{$campos_tabla_seleccionada[] = $registro2;}

 switch ($action)
 { case 1:
	$consulta_sql="INSERT INTO $tabla_seleccionada(";
	$contadorprimero=0;$total_campos_tabla_seleccionada=count($campos_tabla_seleccionada);
	
	foreach($campos_tabla_seleccionada as $id2 => $campos_tabla)
	{ $consulta_sql.=$campos_tabla['Field'];
	  $contadorprimero++;
	   if ($contadorprimero>=$total_campos_tabla_seleccionada){$consulta_sql.=")";}else{$consulta_sql.=",";}

	}
	$consulta_sql.="VALUES (";
    $contadorsegundo=0;$total_vector_valores=count($vector_valores);
	foreach($vector_valores as $id_vector=>$vector)
	{ $contadorsegundo++;
	  $consulta_sql.="'";
	  $consulta_sql.=$vector_valores[$id_vector];
	  $consulta_sql.="'";
	   if ($contadorsegundo>=$total_vector_valores){$consulta_sql.=")";} else{$consulta_sql.=",";}
	 }
   
	break;	
	case 2:
	$consulta_sql="UPDATE $tabla_seleccionada set ";$contadorprimero=0;$total_campos_tabla_seleccionada=count($campos_tabla_seleccionada);
	$contadorsegundo=0;$total_vector_valores=count($vector_valores);
	
	foreach($campos_tabla_seleccionada as $id2 => $campos_tabla)
	{  if ($contadorprimero==0){$clave_primaria=$campos_tabla['Field'];}
	  $consulta_sql.=$campos_tabla['Field'];
	  $consulta_sql.="=";
	  $consulta_sql.="'";
	  $consulta_sql.=$vector_valores[$id2];
	  $consulta_sql.="'";$contadorprimero++; $contadorsegundo++;
	
	  if ($contadorprimero>=$total_campos_tabla_seleccionada){$consulta_sql.=" ";}else{$consulta_sql.=",";}   
	}
	$consulta_sql.="where ".$tabla_seleccionada.".".$clave_primaria."=".$campo_tocable."";//cambiarlo con un describe del primer campo de la tabla clave primaria
	break;
	
	case 3:
	$tablas_actuales_borrado=array();
    $consulta_tablas_actuales_borrado="show tables";
	$ejecutar_tablas_borrado=mysql_query($consulta_tablas_actuales_borrado);
	
	$consulta_campos_tablas="Describe ".$tabla_seleccionada."";

	$ejecutar2=mysql_query($consulta_campos_tablas);
	$contador=0;
	while($registro2 = mysql_fetch_row($ejecutar2))
	{$contador++; if ($contador==1){$campoclave=$registro2[0];break;} 	
	}
	
	while($registro_borrado_tablas = mysql_fetch_array($ejecutar_tablas_borrado))
	{$tablas_actuales_borrado[] = $registro_borrado;}
	$consulta_sql="delete from ".$tabla_seleccionada." where ".$tabla_seleccionada.".".$campoclave."=".$campo_tocable."";
	break;
	
 }//llave del  switch case	

  $tarea_sql_ejecutada=mysql_query($consulta_sql);
   if ($tarea_sql_ejecutada==1){ $consulta_hecha=1;}
   else {$consulta_hecha=0;}

   return $consulta_hecha;	
  }//fin de la funcion
  
  function claveprimaria($tablita)
  {$consulta="Describe $tablita";
   $exe=mysql_query($consulta);
   $registro=mysql_fetch_array($exe);
   $namefield=$registro['Field'];
   return $namefield;
  }
  function ultimo_id($nombre_tabla)
  {$campo_identificador="Describe $nombre_tabla";
   $ejecutar_campo_identificador=mysql_query($campo_identificador);
   
    $registro_identificador = mysql_fetch_array($ejecutar_campo_identificador);
	$nombrecampo=$registro_identificador['Field'];
	
	$ultimo_registro="select ".$nombrecampo." from $nombre_tabla order by ".$nombrecampo." DESC limit 0,1";
	return $ultimo_registro;
  }
  
  function mandar_correo($destino_correo,$asunto_correo,$cuerpo_correo)
  {$mandar=@mail("$destino_correo","$asunto_correo","$cuerpo_correo");
   if ($mandar==TRUE){$mandado="si";}else{$mandado="no";}
   return $mandado;
  }
  function consulta_simple($cadena)
  {  
    $ejecutar_consulta=mysql_query($cadena);
    if ($ejecutar_consulta){
	$registro=mysql_fetch_row($ejecutar_consulta);
	$campodevuelto=$registro[0];
	}
	return $campodevuelto;
  }
  function borrado_simple($sql)
  { $ejecutar_sql=mysql_query($sql);
    if ($ejecutar_sql){$borrado="si";}else{$borrado="no";}
    return $borrado;
  }
  function consulta_compleja($consulta)
  { $vectorsolucion_borrado=array();
   $ejecuccion_consulta=mysql_query($consulta);
   while ($registros=mysql_fetch_array($ejecuccion_consulta))
   {$vectorsolucion_borrado[]=$registros;}
   return $vectorsolucion_borrado;
  }

  function comprobaciones($vector_funcion1,$vector_funcion2)
  { $chivato=0;
    foreach($vector_funcion1 as $campoclave => $vector1)
	{   if ($vector1==0){$chivato=1;$posicion=$campoclave;break;}
	}
 
	if ($chivato==1)
	{ foreach ($vector_funcion2 as $key=>$vector2){ 
	     if ($posicion==$key){$nombre_campo=$vector2;break;}
    }
	  
    $resultado="$nombre_campo se encuentra sin texto";
	}
	else{
	      if ($chivato==0){$resultado="correcto";}
	}
	return $resultado;
  }
  function camposdeunatabla($nombredelatabla)
  { $campostabla="Describe ".$nombredelatabla."";
	$query=mysql_query($campostablas);
	
	$campos_tabla_seleccionada=array();
	while($lectura = mysql_fetch_array($query))
	{$campos_tabla_seleccionada[] = $lectura['Field'];}
	return $campos_tabla_seleccionada;
  }
  function directory_galery($carpeta)
  { 
   $directorioactual=getcwd();
   $ruta_absoluta=sprintf("%s\%s",$directorioactual,$carpeta);
   
   $crear_directorio=@mkdir($ruta_absoluta);
   if ($crear_directorio=="TRUE"){$creacion=1;}
   else{$creacion=0;}
   return $creacion;

  }
 function vaciardire_administradores($directoriorigen)
 { 

  $directoriorigen_absoluta=sprintf("./%s",$directoriorigen);
  $archivos_directorio=@scandir($directoriorigen_absoluta); 
  $total_archivos_directorio = count($archivos_directorio);
 
   for ($i=0; $i<=$total_archivos_directorio; $i++) {
   /*echo $archivos_directorio[$i];echo "<br>";*/
   $archivo_queseborra=sprintf("%s/%s",$directoriorigen_absoluta,$archivos_directorio[$i]);
   $borradodearchivos=@unlink($archivo_queseborra);}
   $borradodirectorio=@rmdir($directoriorigen); 

	  if ($borradodirectorio==0){$vacio=0;}else{$vacio=1;}
	  /*echo "\$borradorire vale $borradire<br>";*/
  return $vacio;
  
 }
  function valores_repetidos($nombretable,$zonatable,$valuecampo)
  {
   $consultasql="select ".$zonatable." from ".$nombretable." where ".$nombretable.".".$zonatable."='".$valuecampo."'";
  
   $ejecuccion=mysql_query($consultasql);$lectura=mysql_fetch_row($ejecuccion);

   if ($valuecampo==$lectura[0]){$repetido="si";}else{$repetido="no";}
   return $repetido;
  }
  function describirtabla($tabla)
  { $consulta="Describe $tabla";
    $exe=mysql_query($consulta);
    $tabla_descrita=array();
	while($lectura = mysql_fetch_array($exe))
	{$tabla_descrita[]=$lectura;
	}
   return $tabla_descrita;
  }
  function borrado_cascada($campo_buscado,$tablas_eliminables,$valorcampo)
  {  
		foreach($tablas_eliminables as $campo_clave => $eliminables)
	    { $delete_cascada="delete  from ".$eliminables." where ".$campo_buscado."=".$valorcampo."";
		  $ejecutar_delete_cascada=mysql_query($delete_cascada);
		}
	$mensaje="El Borrado de los datos se ha realizado correctamente";
	return $mensaje;
  }
  
  function mostrar_tablas_usuario_concreto($tabla_concreta,$campo_identificador,$usuario_propietario)
  {   if ($tabla_concreta=="subsecciones")
      {$mostrar_idtabla_usuario_sql="select * from tabla where usuario='".$usuario_propietario."' order by idtabla asc";
	   $ejecutar_mostrar_idtabla_usuario_sql=mysql_query($mostrar_idtabla_usuario_sql);
	    $idtablas_usuario_concreto=array();
		
	  		while($lectura = mysql_fetch_array($ejecutar_mostrar_idtabla_usuario_sql))
			{$idtablas_usuario_concreto[]=$lectura;}
			
			$resultados_subsecciones_usuario_concreto=array();
			foreach ($idtablas_usuario_concreto as $campo_clave=>$campo_idtablas):
		    $subsecciones_usuario_dado="select * from subsecciones where idtabla=".$campo_idtablas["idtabla"]."";
			$ejecutar_subsecciones_usuario_dado=mysql_query($subsecciones_usuario_dado);
			
					while ($lectura_campos_usuario=mysql_fetch_array($ejecutar_subsecciones_usuario_dado)):
			        $resultados_subsecciones_usuario_concreto[]=$lectura_campos_usuario;
					endwhile;
			/*echo $subsecciones_usuario_dado;echo "<br/>";*/
			endforeach;
		return $resultados_subsecciones_usuario_concreto;	
	  }
	  else
	  {   if ($tabla_concreta!="subsecciones")
	      { $consulta_usuario_actual="select * from ".$tabla_concreta." where ".$campo_identificador."='".$usuario_propietario."'";
		    $ejecutar_consulta_usuario_actual=mysql_query($consulta_usuario_actual);
			
			$resultados_tabla_my_user=array();
			while ($lectura_campos_tabla_user=mysql_fetch_array($ejecutar_consulta_usuario_actual)):
			$resultados_tabla_my_user[]=$lectura_campos_tabla_user;
			endwhile;
		    return $resultados_tabla_my_user;
		  }
	  }
  }
  function calculo_registros_usuario_concreto($vectorusuario,$registrospagina)
  { $contar_vector=count($vectorusuario);
    $paginas=ceil($contar_vector/$registrospagina);
	return $paginas;
  }
  
  function subirfoto_bajo($trayectoria,$directorio,$nombrearchivo)
  {
  $destino=sprintf("../bajo/%s/%s",$directorio,$nombrearchivo);
  $subir=move_uploaded_file($trayectoria,$destino);
  if ($subir=="TRUE"){$fotosubida="si";}else{$fotosubida="no";}
  
  return $fotosubida;
  }
    function subirfoto_medio($trayectoria,$directorio,$nombrearchivo)
  {
  $destino=sprintf("../medio/%s/%s",$directorio,$nombrearchivo);
  $subir=move_uploaded_file($trayectoria,$destino);
  if ($subir=="TRUE"){$fotosubida="si";}else{$fotosubida="no";}
  
  return $fotosubida;
  }
  
  function subirfoto_administradores($trayectoria,$directorio,$nombrearchivo)
  {
  $destino=sprintf("../administradores/%s/%s",$directorio,$nombrearchivo);
  $subir=move_uploaded_file($trayectoria,$destino);
  if ($subir=="TRUE"){$fotosubida="si";}else{$fotosubida="no";}
  
  return $fotosubida;
  }
  function comprobaciones_fotos($type,$tam)
  { $conversion=round($tam/1024);
	if ($conversion>1024)
	{$tipofoto="incorrecto";}
	else
	{  if ($type!="image/pjpeg")
	   {$tipofoto="incorrecto";}
	   else
	   {$tipofoto="correcto";}
	}
	
	return $tipofoto;
  }
  function ver_directorio($nombre_directorio)
  {  $directorio_actual=getcwd();
	 $serdirectorio=is_dir($nombre_directorio);

   $apertura_directorio=opendir($nombre_directorio);
    
    if (!$apertura_directorio){$fallo="Fallo al abrir el directorio";return $fallo;}
	else{ while ($lectura_directorio=readdir($apertura_directorio))
	        {  if ($lectura_directorio!="." && $lectura_directorio!=".." && $lectura_directorio!="thumbs.db")
			   { echo $lectura_directorio; echo "<br>";
			   }
			}
	}
	
	
	closedir($apertura_directorio);
  }
  function eliminarfotos($ruta)
  { $borradofoto=@unlink("$ruta");

   if ($borradofoto){$this->mensaje="borrada";}
   else{$this->mensaje="no borrada";}
   
   return $this->mensaje;
  }
  function eliminardirectorio_fisico($rutadirectorio)
  {$borrado_directorio_fisico=@rmdir($rutadirectorio);
      if ($borrado_directorio_fisico==0)
	  {$borradofisico="no";}
	  else{$borradofisico="si";}
  return $borradofisico;
  }
  function contarvector($vectorpasado)
  {$totalvector=count($vectorpasado);return $totalvector;}

  function idsubsecciones_usuarioactual($quien)
  { $consulta_usuarioactual="select idtabla from tabla where usuario='".$quien."'";
    $ejecutar_consulta_usuarioactual=mysql_query($consulta_usuarioactual);
	$subsecciones_usuario=array();
	while ($lectura_consulta=mysql_fetch_array($ejecutar_consulta_usuarioactual)):
	$subsecciones_usuario[]=$lectura_consulta;
	endwhile;
	return $subsecciones_usuario;
  }

  
  function subsecciones_user_formarselect($quien,$vector_con_parametros)
  {   
      $primerlemento=$vector_con_parametros[0]["idtabla"];
	  $contador=0; 
	  $consulta="select * from subsecciones where idtabla=".$primerlemento."";
   foreach ($vector_con_parametros as $clave=> $vector_formar_consulta)
   { /*$consulta="select * from subsecciones where idtabla=".$vector["idtabla"]."";*/
    if ($contador>0):
	$consulta.=" or idtabla=".$vector_formar_consulta["idtabla"].""; 
    $ejecutar_consulta=mysql_query($consulta);$lectura=mysql_fetch_array($ejecutar_consulta);
	endif; $contador++;}
	return $consulta; 
  }

   
 function __destruct(){} 
}
?>