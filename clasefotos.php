<?php
/*include ("includes/conectar.inc");*/
class trabajarconfotos
{ var $mensaje=" ";
  var $nombreclase; 
  var $tipoclase; 
  var $tamaclase; 
  var $temporalclase; 
  var $direclase;
  var $redimension=" ";
  var $redimension2=" ";
  var $afoto=" ";
  var $textocorrecto=" ";
  var $mensajefallo=" ";
 function trabajarconfotos(){} 
  
  function subirfoto($nombreficheroinicial,$tipoinicial,$tamainicial,$directoriotemporalinicial,$directoriofinalinicial)
  {  
   $this->nombreclase=$nombreficheroinicial;  $this->tipoclase=$tipoinicial; $this->tamaclase=$tamainicial;    
   $this->temporalclase=$directoriotemporalinicial; $this->direclase=$directoriofinalinicial;
   $tamaredondeado=round($tamainicial/1024);
/*    echo "\$tamaredondeado vale $tamaredondeado<br>";*/
   if ($tamaredondeado<=1024) /*para controlar el tamano de la fotos 1mb mas o menos*/
   {
   $creodire=mkdir("$directoriofinalinicial");
   $nombrecompleto=sprintf("%s\%s",$this->direclase,$this->nombreclase);
   echo "\$nombrecompleto en la clase vale $nombrecompleto<br>";
   $subidafoto=move_uploaded_file($this->temporalclase,$nombrecompleto);

       if ($subidafoto=="True")
       { $this->mensaje="si";}else{$this->mensaje="no";}
	}//if del tamano   
	else{
	$this->mensaje="Fotos hasta 1024 kb";
	}
	   return $this->mensaje;
   }    
 
 function creargalerias($galeri)
 { /*echo "\$galeri vale $galeri<br>";*/
  $dia=date("d");$mes=date("m");$ano=date("Y"); $hora=date("H"); $minutos=date("i"); $segundos=date("s");
  $fechas=sprintf("%s-%s-%s",$dia,$mes,$ano);
  $tiempo=sprintf("%s:%s:%s",$hora,$minutos,$segundos);
/* echo "\$fechas vale $fechas<br>";echo "\$tiempo vale $tiempo<br>";*/
 
  $consulta="insert into standar.subsecciones (idsub,idtabla,componente,fecha,hora,imagen,imagen2,extras) 
  VALUES (NULL , '17', '".$galeri."', '".$fechas."', '".$tiempo."', 'no', 'no', 'no')";
/*  echo "\$consulta vale $consulta<br>";*/
  $ejecutar=mysql_query($consulta);$directorio=mkdir("$galeri");
  /*echo "\$galeri vale $galeri<br>";
  echo "\$directorio vale $directorio<br>";*/
  if ($ejecutar && $directorio){$this->mensaje="si";
/*  echo "<META HTTP-EQUIV='Refresh' CONTENT='1'; 'URL=clasefotos.php'>";*/
 }
  else{$this->mensaje="no";}
  
  return $this->mensaje;

 }
 function galeriarepe($nombregaleria)
 {
 $sqlrepeticion="select componente from subsecciones where idtabla=17 and subsecciones.componente='".$nombregaleria."'";
/* echo "\$sqlrepeticion vale  $sqlrepeticion<br>";*/
 $ejecutar=mysql_query($sqlrepeticion);
 $registro=mysql_fetch_row($ejecutar);
 $resultado=$registro[0];
 $largura=strlen($resultado);/*echo "\$largura vale $largura<br>";*/
  if ($largura!=0)
  {$this->mensaje="repetido";
  }else {$this->mensaje="no repetido";}
  return $this->mensaje;
 }
 
 function eliminargalerias($nombregaleriafun)
 { $sqleliminargaleria="delete  from subsecciones where idtabla=17 and subsecciones.componente='".$nombregaleriafun."'";
  /*echo "\$sqleliminargaleria vale $sqleliminargaleria<br>";*/
  $query=mysql_query($sqleliminargaleria);
  if ($query)
  {$this->mensaje="galeria borrada";}
  else{$this->mensaje="galeria no borrada";}
  return $this->mensaje;
 }
 
 function eliminarfotos($ruta)
 { $borradofoto=@unlink("$ruta");
/*   echo "\$borradofoto vale $borradofoto<br>";*/
   if ($borradofoto){$this->mensaje="borrada";}
   else{$this->mensaje="no borrada";}
   
   return $this->mensaje;
 }
 
 function vaciardire($directoriofun)
 { $vacio; $largura=strlen($directoriofun);
 if ($largura==0){
 echo "<center><img src=images/section.png class='bordefoto1'></center>";
 echo "<span class='letrainternagrande'>El Directorio se encuentra borrado de ficheros</span><br/>";
 echo "<a href='index.php?pagina=borradofotos.php' class='enlace2'>Volver</a>";}
 else{
 echo "<center><img src=images/section.png class='bordefoto1'></center>";
 echo "<span class='letrainternagrande'>El Borrado de la galeria de fotos ha sido efectuado</span><br/>";
 echo "<a href='index.php?pagina=borradofotos.php' class='enlace2'>Volver</a>";
  $directoriofun2=sprintf("./%s",$directoriofun);
  $archivos=@scandir($directoriofun2); 
   $num = count($archivos);

   for ($i=0; $i<=$num; $i++) {
   $archivo2=sprintf("%s/%s",$directoriofun2,$archivos[$i]);
   $borradodir=@unlink($archivo2);}
   	  $borradodire=@rmdir($directoriofun); 
	  if ($borrradodire==0){echo "error";$vacio=0;}else{echo "echo no error";$vacio=1;}
	  echo "\$borradorire vale $borradire<br>";

   }
 }
 
  function vaciardire2($directoriofun,$directoriofun3)
 { $vacio; $largura=strlen($directoriofun);$largurab=strlen($directoriofun3);
 if ($largura==0|| $largurab==0){
 echo "<center><img src=images/section.png class='bordefoto1'></center>";
 echo "<span class='letrainternagrande'>El Directorio se encuentra borrado de ficheros</span><br/>";
 echo "<a href='index.php?pagina=borradofotos.php' class='enlace2'>Volver</a>";}
 else{
 echo "<center><img src=images/section.png class='bordefoto1'></center>";
 echo "<span class='letrainternagrande'>El Borrado de la galeria de fotos ha sido efectuado</span><br/>";
 echo "<a href='index.php?pagina=borradofotos.php' class='enlace2'>Volver</a>";
  $directoriofun2=sprintf("./%s",$directoriofun);
  $directoriofun4=sprintf("./%s",$directoriofun3);
  $archivos=@scandir($directoriofun2); 
  $archivos4=@scandir($directoriofun3); 
   
   $num = count($archivos);

   for ($i=0; $i<=$num; $i++) {
   $archivo2=sprintf("%s/%s",$directoriofun2,$archivos[$i]);
    $archivo4=sprintf("%s/%s",$directoriofun3,$archivos4[$i]);
   $borradodir=@unlink($archivo2);$borradodir2=@unlink($archivo4);}
   	  $borradodire=@rmdir($directoriofun); $borradodire2=@rmdir($directoriofun3);
	  /*
	  if ($borrradodire==0){echo "error";$vacio=0;}else{echo "echo no error";$vacio=1;}
	  echo "\$borradorire vale $borradire<br>";*/

   }
 }
 
 
 
 function verdire($directoriofun)
 { $directoriofun2=sprintf("./%smini",$directoriofun);
/* echo "\$directoriofun2 vale $directoriofun2<br>";*/
  $archivos=@scandir($directoriofun2); 
 $num=count($archivos);
 echo "<table class='bordefoto1'>";

 for ($i=0; $i<=$num-1; $i++) 
 {
/* echo "<img src=".$directoriofun2.".".$archivos[$i].">";*/
	if(($archivos[$i]!=".")and($archivos[$i]!="..")and($archivos[$i]!="Thumbs.db"))
	{ $archivo2=sprintf("%s/%s",$directoriofun2,$archivos[$i]);
	 echo "<td><img src=$archivo2 class='bordefoto1'><br>";
	 echo "<span class='letrainterna'>Borrar</span>";/*&lafoto=".$archivo2."*/
	 echo "<a href=index.php?pagina=modificadofotos2.php&dir=".$directoriofun."&arch=".$archivo2.">";
	 echo "<img src=images/delete.jpg border=0></a></td>";
	 }//if
   }//for interno el de la i
  
 echo "</tr>"; 
echo "</table>";   
   
 }//function
 
  function verdire3($directoriofun)
 { $directoriofun2=sprintf("./%smini",$directoriofun);
/* echo "\$directoriofun2 vale $directoriofun2<br>";*/
  $archivos=@scandir($directoriofun2); 
 $num=count($archivos);
 echo "<table class='bordefoto1'>";

 for ($i=0; $i<=$num-1; $i++) 
 {
/* echo "<img src=".$directoriofun2.".".$archivos[$i].">";*/
	if(($archivos[$i]!=".")and($archivos[$i]!="..")and($archivos[$i]!="Thumbs.db"))
	{ $archivo2=sprintf("%s/%s",$directoriofun2,$archivos[$i]);
	 echo "<td><img src=$archivo2 class='bordefoto1'><br>";
	 echo "<span class='letrainterna'>Borrar</span>";/*&lafoto=".$archivo2."*/
	 echo "<a href=index.php?pagina=eliminadofotos2.php&dir=".$directoriofun."&arch=".$archivo2.">";
	 echo "<img src=images/delete.jpg border=0></a></td>";
	 }//if
   }//for interno el de la i
  
 echo "</tr>"; 
echo "</table>";   
   
 }//function
 
  function verdire2($directoriofun)
 { $directoriofun2=sprintf("./%smini",$directoriofun);
/* echo "\$directoriofun2 vale $directoriofun2<br>";*/
  $archivos=@scandir($directoriofun2); 
 $num=count($archivos);

 
if ($num==1){echo "<span class='letrainternagrande'>No hay fotos en este momento</span>";}
else
{ echo "<table class='bordefoto1'>";
   for ($i=0; $i<=$num-1; $i++) 
 {/* echo "\$i vale $i<br>";*/
 
/* echo "<img src=".$directoriofun2.".".$archivos[$i].">";*/
	if(($archivos[$i]!=".")and($archivos[$i]!="..")and($archivos[$i]!="Thumbs.db"))
	{ $archivo2=sprintf("%s/%s",$directoriofun2,$archivos[$i]);
	 echo "<td align='center'><img src=$archivo2 class='bordefoto1'></td>";
	 }//if
	//else de ke hay fotos 
   }//for interno el de la i
  
 echo "</tr>";
echo "</table>";   
 }  
 }//function
 
function thumbjpeg($imagen,$anchura,$directoriodestino) { 
     // Lugar donde se guardarán los thumbnails respecto a la carpeta donde está la imagen "grande". 
	/* echo "\$imagen vale $imagen<br>";*/
	 $directoriodestino2=sprintf("%smini",$directoriodestino);
/*	 echo "\$directoriodestino2 vale $directoriodestino2<br>";*/
     $diredestino="./$directoriodestino2";
/*	 echo "\$direstino vale $diredestino<br>";*/
	 
     // Prefijo que se añadirá al nombre del thumbnail. Ejemplo: si la imagen grande fuera "imagen1.jpg", 
     // el thumbnail se llamaría "tn_imagen1.jpg" 
     $prefijofotodestino= "th_"; 

     // Aquí tendremos el nombre de la imagen. 
     $nombreorigen=basename($imagen); /*	 echo "\$nombreorigen vale $nombreorigen<br>";*/
     // Aquí la ruta especificada para buscar la imagen. 
	 
     $directoriorigenfoto=dirname($imagen)."/"; 

/*     $imagenfinal=$directoriorigenfoto.$diredestino.$prefijofotodestino.$nombreorigen;*/
       $imagenfinal=$directoriorigenfoto."mini/".$prefijofotodestino.$nombreorigen;

/*	 $rutaimagenoriginal=$directoriorigenfoto.$prefijodestino.$nombreorigen;*/
	 $rutaimagenoriginal=$directoriorigenfoto.$nombreorigen;
/* echo "\$rutaimagenoriginal vale $rutaimagenoriginal<br>";*/

 if (!file_exists($diredestino)) {
          mkdir ($diredestino) ; }
		
     if (!file_exists($imagenfinal)) 
	 {  $img =@imagecreatefromjpeg($rutaimagenoriginal); 
        // miramos el tamaño de la imagen original... 
          $datos=@getimagesize($rutaimagenoriginal);
          /*echo "\$datos0 vale $datos[0]<br>";*/
          // intentamos escalar la imagen original a la medida que nos interesa reescalada correctametne
		  //para altura anchura cambiar el 0 por el 1 y viceversa
          $ratio=@round($datos[0]/$anchura); 
          $altura=@round($datos[1]/$ratio); 
          // esta será la nueva imagen reescalada 
          $mini=@imagecreatetruecolor($anchura,$altura); 
          $imagenfinal=@$diredestino."/".$prefijofotodestino.$nombreorigen;
		            // con esta función la reescalamos 
		/*          imagecopyresampled ($thumb, $img, 125, 0, 0, 0, $anchura, $altura, $datos[0], $datos[1]); */
		
		   $resample=@imagecopyresampled($mini,$img, 0, 0, 0, 0, $anchura, $altura, $datos[0], $datos[1]); 
	   

          // voilà la salvamos con el nombre y en el lugar que nos interesa. 
          if (imagejpeg($mini,$imagenfinal))
		  {return $this->redimension="si";
		  }else{return $this->redimension="no";}
		  
    }
	} 
	

	function grandes($imagen2,$anchura2,$directoriodestino2) { 
     // Lugar donde se guardarán los thumbnails respecto a la carpeta donde está la imagen "grande". 
/* echo "\$imagen2 vale $imagen2<br>";*/
     $diredestino2= "$directoriodestino2/"; 
	 
     // Prefijo que se añadirá al nombre del thumbnail. Ejemplo: si la imagen grande fuera "imagen1.jpg", 
     // el thumbnail se llamaría "tn_imagen1.jpg" 
     $prefijofotodestino2= "th_"; 

     // Aquí tendremos el nombre de la imagen. 
     $nombreorigen2=basename($imagen2); /*	 echo "\$nombreorigen vale $nombreorigen<br>";*/
     // Aquí la ruta especificada para buscar la imagen. 
	 
     $directoriorigenfoto2=dirname($imagen2)."/"; 
     $imagenfinal2=$diredestino2.$prefijofotodestino2.$nombreorigen2;
	 $rutaimagenoriginal2=$directoriorigenfoto2.$prefijodestino2.$nombreorigen2;

     if (!file_exists($imagenfinal2)) 
	 {   
	    
	     $img2 = @imagecreatefromjpeg($rutaimagenoriginal2); 
        // miramos el tamaño de la imagen original... 
          $datos2 =@getimagesize($rutaimagenoriginal2);
          /*echo "\$datos0 vale $datos[0]<br>";*/
          // intentamos escalar la imagen original a la medida que nos interesa reescalada correctametne
		  //para altura anchura cambiar el 0 por el 1 y viceversa
		  /*
		  echo "\$datos de cero vale $datos2[0]";
		  echo "\$datos de uno vale $datos2[1]";*/
		  echo "\$rutaimagenoriginal2 vale $rutaimagenoriginal2<br>";
          $ratio2 = $datos2[0]/ $anchura2; /*hay que divividir las fotos por lo mismo para que no se deformen*/
/*		  echo "\$anchura2pasada vale $anchura2";*/
	
          $altura2 = ($datos2[1]/$ratio2); 
/*		  echo "\$ratio2 vale $ratio2";
		  	  echo "\$altura2 vale $altura2";*/
          // esta será la nueva imagen reescalada 
          $mini2 = imagecreatetruecolor($anchura2,$altura2); 
           $imagenfinal2=$diredestino2.$prefijofotodestino2.$nombreorigen2;
          // con esta función la reescalamos 
		/*          imagecopyresampled ($thumb, $img, 125, 0, 0, 0, $anchura, $altura, $datos[0], $datos[1]); */
		   imagecopyresampled ($mini2,$img2, 0, 0, 0, 0, $anchura2, $altura2, $datos2[0], $datos2[1]); 
           $actual=getcwd();
		   @unlink("$actual/$imagen2");
          // voilà la salvamos con el nombre y en el lugar que nos interesa. 
          if (imagejpeg($mini2,$imagenfinal2))
		  {return $this->redimension2="si";
		  }else{return $this->redimension2="no";}
		  
     }

	} 

	function miniaturatama($fotofun,$anchofun,$largofun)
	{
	
	$src = imagecreatefromjpeg($fotofun);$dest = imagecreatetruecolor($anchofun,$largofun);
    imagecopy($dest, $src, 0, 0, 20, 13, $anchofun,$largofun);
	$this->redimension=imagejpeg($dest);
	imagedestroy($dest);
    imagedestroy($src);
	}
	
	function tipofotofun($fotografiafun)
	{ 
	$info=getimagesize($fotografiafun);
/*
	echo "\$fotografiafun vale $fotografiafun<br>";
	echo "\$info[2] vale $info[2]<br>";*/
/*    echo "$fotocorrecta[$desde]";*/
	  if ($info[2]==1|| $info[2]==2 || $info[2]==3){return $this->afoto="si";}
	  else{return $this->afoto="no";}
	}
	
	function tipofotofun2($fotografiafun)
	{$extensiones=array("jpg","JPG","GIF","gif","png","PNG");
/*	echo "\$fotografiafun vale $fotografiafun<br>";*/
     $fich=basename($fotografiafun);
/*	 echo "\$fich vale $fich<br<>";*/
	 $corte1=strtok($fich,".");$extension=strtok($corte1); 
	 $estar=0;$validefoto=" ";
	  for ($desde=0;$desde<=5;$desde++)
	  {if ($extensiones[$desde]==$extension){$estar=1;break;}
	  }
	  if ($estar==1){$this->afoto="si";}else{$this->afoto="no";}

	  return $afoto;
/*	  
	 echo "\$fich vale $fich"; 
	 echo "\$extension vale $extension<br>";
	 */
	}
	

	
	function comprobacionempleados($empleadofun,$datosfun,$dptofun,$fotofun)
	{/*echo "\$empleadofun vale $empleadofun<br>";
	 echo "\$datosfun vale $datosfun<br>";
	 echo "\$dptofun vale $dptofun<br>";
	 echo "\$fotofun $fotofun<br>";*/
/*	  var $mensajefallo=" ";*/
	 $largo1=strlen($empleadofun);$largo2=strlen($datosfun);$largo3=strlen($dptofun);
	 $largo4=strlen($fotofun);/*echo "\$largo1 vale $largo1<br>";echo "\$largo2 vale $largo2<br>";
		   echo "\$largo3 vale $largo3<br>";echo "\$largo4 vale $largo4<br>";*/
	      if ($largo1!=0 && $largo2!=0 && $largo3!=0 && $largo4!=0)
		  {$this->textocorrecto="si";}
	     else
		 {    if ($largo1==0)
		      { echo "<span class='letrainterna'>Introduzca un nombre para el empleado</span>";}
			  else
			   {
			      if ($largo2==0){echo "<span class='letrainterna'>Introduzca comentarios sobre el empleado</span>";}
				  else{
				        if ($largo3==0){echo "<span class='letrainterna'>Introduzca el departamento del empleado</span>";}
						else{
						      if ($largo4==0){echo "<span class='letrainterna'>Introduzca una foto para el empleado</span>";}
						}
						  
				      }
               }
		    
		     $this->textocorrecto="no";
		 }
	}
	
	function galerias2($value)
	{
	$sql="select * from subsecciones where idtabla=17 limit $value,5";
	$ejecutar=mysql_query($sql);
	echo "<center><img src=images/section.png class='bordefoto1'></center><br/>";
	echo "<span class='letrainternagrande'>Seleccione la galeria a eliminar</span>";
	echo "<table width=400 align=center >";
	echo "<tr bgcolor='#5E3313'>";
	echo "<td class='letrainteranoro' align='center'><center>Id</Center></td>";
	echo "<td class='letrainteranoro' align='center'>Titulo </td>";
	echo "<td class='letrainteranoro' align='center'>Borrar Galeria</td>";
	echo "</tr>";
	while ($registro=mysql_fetch_row($ejecutar))
	{$contador++;if ($contador%2==0){$color="#E5BF4F";}else {$color="#D7B85D";}
   	echo "<tr bgcolor=$color>"; 		
   	echo "<td align='center'><span class='letrainterna'>$contador</A></span>";
	echo "<td align='center'><span class='letrainterna'>$registro[2]</td>";
echo "<td align='center'><a href='index.php?pagina=borradofotos2.php&valor=$registro[0]'><img src='images/delete.jpg' border='0'></td>";
	echo "</tr>";}
	echo "</table>";
	}
	
	
	
	function galerias2b($value)
	{
	$sql="select * from subsecciones where idtabla=17 limit $value,5";
	$ejecutar=mysql_query($sql);
	echo "<center><img src=images/section.png class='bordefoto1'></center><br/>";
	echo "<span class='letrainternagrande'>Seleccione la galeria a eliminar</span>";
	echo "<table width=400 align=center >";
	echo "<tr bgcolor='#5E3313'>";
	echo "<td class='letrainteranoro' align='center'><center>Id</Center></td>";
	echo "<td class='letrainteranoro' align='center'>Titulo </td>";
	echo "<td class='letrainteranoro' align='center'>Borrar Galeria</td>";
	echo "</tr>";
	while ($registro=mysql_fetch_row($ejecutar))
	{$contador++;if ($contador%2==0){$color="#E5BF4F";}else {$color="#D7B85D";}
   	echo "<tr bgcolor=$color>"; 		
   	echo "<td align='center'><span class='letrainterna'>$contador</A></span>";
	echo "<td align='center'><span class='letrainterna'>$registro[2]</td>";
echo "<td align='center'><a href='index.php?pagina=borradofotosind.php&valor=$registro[0]'><img src='images/delete.jpg' border='0'></td>";
	echo "</tr>";}
	echo "</table>";
	}
	
	function galerias3b($value)
	{
	$sql="select * from subsecciones where idtabla=17 limit $value,5";
	$ejecutar=mysql_query($sql);
	echo "<center><img src=images/section.png class='bordefoto1'></center><br/>";
	echo "<span class='letrainternagrande'>Seleccione la galeria a para meter fotos</span>";
	echo "<table width=400 align=center >";
	echo "<tr bgcolor='#5E3313'>";
	echo "<td class='letrainteranoro' align='center'><center>Id</Center></td>";
	echo "<td class='letrainteranoro' align='center'>Titulo </td>";
	echo "<td class='letrainteranoro' align='center'>Meter Fotos</td>";
	echo "</tr>";
	while ($registro=mysql_fetch_row($ejecutar))
	{$contador++;if ($contador%2==0){$color="#E5BF4F";}else {$color="#D7B85D";}
   	echo "<tr bgcolor=$color>"; 		
   	echo "<td align='center'><span class='letrainterna'>$contador</A></span>";
	echo "<td align='center'><span class='letrainterna'>$registro[2]</td>";
echo "<td align='center'><a href='index.php?pagina=modificadofotos.php&valor=$registro[0]'><img src='images/update.jpg' border='0'></td>";
	echo "</tr>";}
	echo "</table>";
	}
	
	
		function galerias3($value)
	{
	$sql="select * from subsecciones where idtabla=17 limit $value,5";
	$ejecutar=mysql_query($sql);
	echo "<center><img src=images/section.png class='bordefoto1'></center><br/>";
	echo "<span class='letrainternagrande'>Seleccione la galeria a para meter fotos</span>";
	echo "<table width=400 align=center >";
	echo "<tr bgcolor='#5E3313'>";
	echo "<td class='letrainteranoro' align='center'><center>Id</Center></td>";
	echo "<td class='letrainteranoro' align='center'>Titulo </td>";
	echo "<td class='letrainteranoro' align='center'>Meter Fotos</td>";
	echo "</tr>";
	while ($registro=mysql_fetch_row($ejecutar))
	{$contador++;if ($contador%2==0){$color="#E5BF4F";}else {$color="#D7B85D";}
   	echo "<tr bgcolor=$color>"; 		
   	echo "<td align='center'><span class='letrainterna'>$contador</A></span>";
	echo "<td align='center'><span class='letrainterna'>$registro[2]</td>";
echo "<td align='center'><a href='index.php?pagina=modificadofotos.php&valor=$registro[0]'><img src='images/update.jpg' border='0'></td>";
	echo "</tr>";}
	echo "</table>";
	}
	
	
	function galerias4($value)
	{
	$sql="select * from subsecciones where idtabla=17 limit $value,5";
	$ejecutar=mysql_query($sql);
	echo "<center><img src=images/section.png class='bordefoto1'></center><br/>";
	echo "<span class='letrainternagrande'>Seleccione la galeria para modificar</span>";
	echo "<table width=400 align=center >";
	echo "<tr bgcolor='#5E3313'>";
	echo "<td class='letrainteranoro' align='center'><center>Id</Center></td>";
	echo "<td class='letrainteranoro' align='center'>Titulo </td>";
	echo "<td class='letrainteranoro' align='center'>Modificar Fotos</td>";
	echo "</tr>";
	while ($registro=mysql_fetch_row($ejecutar))
	{$contador++;if ($contador%2==0){$color="#E5BF4F";}else {$color="#D7B85D";}
   	echo "<tr bgcolor=$color>"; 		
   	echo "<td align='center'><span class='letrainterna'>$contador</A></span>";
	echo "<td align='center'><span class='letrainterna'>$registro[2]</td>";
echo "<td align='center'><a href='index.php?pagina=modificadofotos.php&valor=$registro[0]'><img src='images/update.jpg' border='0'></td>";
	echo "</tr>";}
	echo "</table>";
	}
	
	
	  function __destruct(){}
	  
}  

?>
