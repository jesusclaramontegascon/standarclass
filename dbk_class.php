<?
//requerimos las constantes para el acceso a la base de datos
require("dbk_cnf.php");
//error_reporting(E_ALL);

class RegistroDBK {
	//Variables
	var $pagVar="pag"; //el NOMBRE que lleva la variable que almacena el num de pagina
	var $numRegPag=15; //numero d eregistros por pagina;
	var $pagLat=9; //máximo de páginas a ambos lados de la actual en el menu complejo de navegacion
	var $sql;
	var $numRegTotales;
	var $numPagTotales;
	var $resultado; //aquí va el puntero del resultado de la consulta sql ya paginada.
	var $resultado_noPag; //aquí va el puntero del resultado de la consulta sql sin paginar.
	var $numRegPagina;
	var $paginaActual;
	var $navega; //strings para avanzar o retroceder
	var $strNavega; //string navegacion compleja
	var $linkDB;
	var $oid;
	//
	
	//constructor
	function RegistroDBK(){
		$this->connDBK();
	}
	function inicializaDBK(){
		$this->calcRegTotales();
		$this->calcPagTotales();
		$this->calcPaginaActual();
		$this->queryDBK();
		$this->navegacion();
	}
	//conexion a la base de datos
	function connDBK(){
		$linkDB = mysql_connect (SERVIDOR_BD, LOGIN, PASSW) or die ("Error de Acceso: no conectado a la base de datos");
		mysql_select_db(NOMBRE_BD,$linkDB);
		$this->linkDB = $linkDB;
	}
	function insertOID(){
		$rt = mysql_query($this->sql) or die("$this->sql<br>comando incorrecto<br>");
		$this->oid = mysql_insert_id($this->linkDB);
	}
	function calcPaginaActual(){
		// A ? B : C es si A verdadero ejecuta B si A falso ejecuta C
		$this->paginaActual = (isset($_REQUEST[$this->pagVar]) && $_REQUEST[$this->pagVar] != "") ? $_REQUEST[$this->pagVar] : 0;
		$this->paginaActual = ($this->paginaActual < $this->numPagTotales && $this->paginaActual >= 0) ? $this->paginaActual : 0;
		return $this->paginaActual;
	}
	function calcRegTotales(){ //El query no está limitado como en regPagina
		$rt = mysql_query($this->sql) or die("$this->sql<br>".mysql_error());
		$this->numRegTotales = mysql_num_rows($rt);
		mysql_free_result($rt);
		return $this->numRegTotales;
	}
	function calcCamp_x_Reg(){ //El query no está limitado como en regPagina
		$rt = mysql_query($this->sql) or die("$this->sql<br>".mysql_error());
		$this->numCamp_x_Reg = mysql_num_fields($rt);
		mysql_free_result($rt);
		return $this->numCamp_x_Reg;
	}
	function regPagina(){ //aquíe el query está limitado al máx de registros por página
		$this->numRegPagina = mysql_num_rows($this->resultado);
		return $this->numRegPagina;
	}
	function calcPagTotales(){
		$this->numPagTotales = ceil($this->numRegTotales / $this->numRegPag);
		return $this->numPagTotales;
	}
	function queryDBK(){
		$inicio= $this->calcPaginaActual() * $this->numRegPag;
		$tmpSql = sprintf("%s LIMIT %s, %s", $this->sql, $inicio, $this->numRegPag);
		$this->resultado = mysql_query ($tmpSql) or die("Error: $tmpSql<br><a href=javascript:history.back()> [Volver] </a>");
		return $this->resultado;
	}
	function queryDBK_noPag(){
		$this->resultado_noPag = mysql_query ($this->sql) or die("Error: $this->sql<br>".NOMBRE_BD."<br>".mysql_error());
		return $this->resultado_noPag;
	}
	function freePageResult(){
		mysql_free_result($this->resultado);
	}
	function freePageResult_noPag(){
		mysql_free_result($this->resultado_noPag);
	}
	function close(){
		mysql_close($this->linkDB);
	}
	//elimina la variable $marca de la query string para poder añadir el nuevo valor y que no se repita
	function rehaceQS($marca){ 
		if (!empty($_SERVER['QUERY_STRING'])){
			$qs = $_SERVER['QUERY_STRING'];
			$aux = explode("&", $qs);
			$nuevaQS = array();
			foreach( $aux as $variable ){
				if ( stristr($variable,$marca) == false ) array_push($nuevaQS,$variable);
			}
			//esta comprobacion es por si la qs solo tiene la variable que eliminamos
			if ( count($nuevaQS) != 0 ) {
				$qs = "&".implode("&",$nuevaQS);
				return $qs; //la nueva query
			}
			else return false;
		}
		else return false;
	}
//	function navegacion($paginaDestino=""){
	function navegacion(){
		$regTot = $this->calcRegTotales();
		$pagTot = ceil($regTot / $this->numRegPag);
		$pagAct = $this->calcPaginaActual();
		//controlamos que la pagina pertenece al intervalo correcto
		$pagAct = ( $pagAct > 0 ) ? $pagAct : 0; 
		$pagAct = ( $pagAct <= $pagTot ) ? $pagAct : $pagTot; 
		$adelanteQS = $this->rehaceQS($this->pagVar)."&".$this->pagVar."=".($pagAct+1);
		$atrasQS = $this->rehaceQS($this->pagVar)."&".$this->pagVar."=".($pagAct-1);
		$navega["adelante"] = substr($adelanteQS,1);
		$navega["atras"] = substr($atrasQS,1);
		$navega["ini"] = $pagAct*$this->numRegPag+1;
		$navega["fin"] = $navega["ini"]+$this->numRegPag-1 <= $regTot ? $navega["ini"]+$this->numRegPag-1 : $regTot ;
		$this->navega = $navega;
		return $navega;
	}
	function navegacion2(){
		$regTot = $this->calcRegTotales();
		$pagTot = ceil($regTot / $this->numRegPag);
		$pagAct = $this->calcPaginaActual();
		//controlamos que la pagina pertenece al intervalo correcto
		$pagAct = ( $pagAct > 0 ) ? $pagAct : 0; 
		$pagAct = ( $pagAct <= $pagTot ) ? $pagAct : $pagTot; 
		//parte del menú de navegación compleja
		$this->strNavega="";
		$pagLat=$this->pagLat;
		$pagDch=$pagTot-1-$pagAct;
		$pagIzq=$pagAct-$pagLat;
		//pag a la izquierda
		if ( $pagAct <= 0 ) $pagIzq=0;
		else if ( $pagAct > 0 && $pagAct <= $pagLat ) $pagIzq=$pagAct-1;
		else if ( $pagAct > $pagLat ) $pagIzq=$pagLat;
		if ($pagTot==1 || $pagAct==0) $pagIzq=-1;
		//pag a la derecha
		if ( $pagAct >= $pagTot ) $pagDch=0;
		else if ($pagAct < $pagTot && $pagDch > $pagLat ) $pagDch=$pagLat;
		//construimos la secuencia de enlaces a las páginas
		for ($p=$pagAct-$pagIzq; $p<$pagAct+1; $p++){
			$this->strNavega.="<a href=\"?".substr($this->rehaceQS($this->pagVar)."&".$this->pagVar."=".($p-1),1)."\">".$p."</a> ";
		}
		$this->strNavega.="<b>".($pagAct+1)."</b>";
		for ($p=$pagAct; $p<=$pagAct-1+$pagDch; $p++){
			$this->strNavega.=" <a href=\"?".substr($this->rehaceQS($this->pagVar)."&".$this->pagVar."=".($p+1),1)."\">".(($p+1)+1)."</a>";
		}
		return $this->strNavega;
	}
	function navegacion3(){
		$regTot = $this->calcRegTotales();
		$pagTot = ceil($regTot / $this->numRegPag);
		$pagAct = $this->calcPaginaActual();
		//controlamos que la pagina pertenece al intervalo correcto
		$pagAct = ( $pagAct > 0 ) ? $pagAct : 0; 
		$pagAct = ( $pagAct <= $pagTot ) ? $pagAct : $pagTot; 
		$adelanteQS = $this->rehaceQS($this->pagVar)."&".$this->pagVar."=".($pagAct+1);
		$atrasQS = $this->rehaceQS($this->pagVar)."&".$this->pagVar."=".($pagAct-1);
		$finQS = $this->rehaceQS($this->pagVar)."&".$this->pagVar."=".($pagTot-1);
		$iniQS = $this->rehaceQS($this->pagVar)."&".$this->pagVar."=0";
//		$navega["adelante"] = substr($adelanteQS,1);
		$navega["adelante"] = " <a href=\"?".substr($adelanteQS,1)."\">siguiente &gt;&gt;</a> ";
//		$navega["atras"] = substr($atrasQS,1);
		$navega["atras"] =  "<a href=\"?".substr($atrasQS,1)."\">&lt;&lt; anterior</a> ";
//		$navega["ini"] = $pagAct*$this->numRegPag+1;
		$navega["ini"] = "<a href=\"?".substr($iniQS,1)."\">inicio</a> ";
//		$navega["fin"] = $navega["ini"]+$this->numRegPag-1 <= $regTot ? $navega["ini"]+$this->numRegPag-1 : $regTot ;
		$navega["fin"] = "<a href=\"?".substr($finQS,1)."\">fin</a> ";
		$this->navega = $navega;
		return $navega;
	}
	function locreg($condicion,$variable /*nombre de la variable que busco en el registro de la BD*/,$tabla){
		$sqlstr = 'SELECT '.$variable.' FROM '.$tabla.' WHERE '.$condicion;
		$esta = mysql_query ($sqlstr) or die(mysql_error());
/*	                  echo "\$esta vale $esta<br>";*/
		if ($i = mysql_fetch_array($esta)) return(utf8_encode($i[$variable])); 
		else return(0);
	}
	function simplereg($variable,$query){
		$esta = mysql_query ($query) or die(mysql_error());
		if ($i = mysql_fetch_array($esta)) {
			if ($i[$variable] != "") return($i[$variable]);
		}
		return(0);
	}
	function buscaPat($tabla, $patron, $cnd){
		//primero extraemos los tipos de la tabla y todos sus campos
		$this->sql = "DESCRIBE $tabla";
		$this->queryDBK_noPag();
		$strCnd = "WHERE (";
		while ($reg = mysql_fetch_array($this->resultado_noPag)){
			//extraemos los tipos de cada campo
			$tipoenarray = split("\(", $reg["Type"]); //eliminamos los datos adicionales en el tipo (longitud, signo, etc ... )
			$clave = $reg["Field"];
			switch ($tipoenarray[0]){
				case "int":
				case "tinyint": //añadimos un filtro para que si $patron no es un numero no se tome en cuenta 
					is_numeric($patron) ? $strCnd .= "$clave = $patron $cnd " : $strCnd .= "";
					break;
				case "char":
				case "varchar":
					$strCnd .= "$clave like '%$patron%' $cnd ";
					break;
			}
		}
		$strCnd = substr($strCnd,0,strlen($strCnd)-strlen($cnd)-1).")"; //quitamos la ultima cnd y cerramos el parentesis
		$this->freePageResult_noPag();
		return ($strCnd);
	}
/*	function buscaPatRestringido($tabla, $patron, $cnd, $campos){
		//primero extraemos los tipos de la tabla y todos sus campos
		$this->sql = "DESCRIBE $tabla";
		$this->queryDBK_noPag();
		$strCnd = "WHERE ";
		while ($reg = mysql_fetch_array($this->resultado_noPag)){
			//extraemos los tipos de cada campo en el cual  vamos a buscar
			(array_search($reg["Field"],$campos) != '' || array_search($reg["Field"],$campos) === 0 )? $si=true : $si=false;
			if ($si){
				$tipoenarray = split("\(", $reg["Type"]); //eliminamos los datos adicionales en el tipo (longitud, eigno, etc ... )
				$clave = $reg["Field"];
				switch ($tipoenarray[0]){
					case "int":
					case "tinyint": //añadimos un filtro para que si $patron no es un numero no se tome en cuenta 
						is_numeric($patron) ? $strCnd .= "$clave = $patron $cnd " : $strCnd .= "";
						break;
					case "char":
					case "varchar":
						$strCnd .= "$clave like '%$patron%' $cnd ";
						break;
				}
			}
		}
		$strCnd = substr($strCnd,0,strlen($strCnd)-strlen($cnd)-1); //quitamos la ultima cnd
		$this->freePageResult_noPag();
		return ($strCnd);
	}*/
	function buscaPatRestringido($t, $patron, $cnd, $campos){
		if (is_array($t)){ //por si es una o varias tablas
			$imax = count($t);
			$tabla = $t;
		} else {
			$imax = 1;
			$tabla[0] = $t;
		}
		$strCnd = "WHERE ";
		for ($i=0; $i<$imax; $i++){
			//primero extraemos los tipos de la tabla y todos sus campos
			$this->sql = "DESCRIBE ".$tabla[$i];
			$this->queryDBK_noPag();
			while ($reg = mysql_fetch_array($this->resultado_noPag)){
				$tbl=$tabla[$i];
				//extraemos los tipos de cada campo en el cual  vamos a buscar
				(array_search($tbl.".".$reg["Field"],$campos) != '' || array_search($tbl.".".$reg["Field"],$campos) === 0 )? $si=true : $si=false;
				if ($si){
					$tipoenarray = split("\(", $reg["Type"]); //eliminamos los datos adicionales en el tipo (longitud, eigno, etc ... )
					$clave = $tbl.".".$reg["Field"];
					switch ($tipoenarray[0]){
						case "int":
						case "tinyint": //añadimos un filtro para que si $patron no es un numero no se tome en cuenta 
							is_numeric($patron) ? $strCnd .= "$clave = $patron $cnd " : $strCnd .= "";
							break;
						case "char":
						case "varchar":
							$strCnd .= "$clave like '%$patron%' $cnd ";
							break;
					}
				}
			}
		}
		$strCnd = substr($strCnd,0,strlen($strCnd)-strlen($cnd)-1); //quitamos la ultima cnd
		$this->freePageResult_noPag();
		return ($strCnd);
	}
	function buscaPatRestringidosinwhere($t, $patron, $cnd, $campos){
		if (is_array($t)){ //por si es una o varias tablas
			$imax = count($t);
			$tabla = $t;
		} else {
			$imax = 1;
			$tabla[0] = $t;
		}
		for ($i=0; $i<$imax; $i++){
			//primero extraemos los tipos de la tabla y todos sus campos
			$this->sql = "DESCRIBE ".$tabla[$i];
			$this->queryDBK_noPag();
			while ($reg = mysql_fetch_array($this->resultado_noPag)){
				$tbl=$tabla[$i];
				//extraemos los tipos de cada campo en el cual  vamos a buscar
				(array_search($tbl.".".$reg["Field"],$campos) != '' || array_search($tbl.".".$reg["Field"],$campos) === 0 )? $si=true : $si=false;
				if ($si){
					$tipoenarray = split("\(", $reg["Type"]); //eliminamos los datos adicionales en el tipo (longitud, eigno, etc ... )
					$clave = $tbl.".".$reg["Field"];
					switch ($tipoenarray[0]){
						case "int":
						case "tinyint": //añadimos un filtro para que si $patron no es un numero no se tome en cuenta 
							is_numeric($patron) ? $strCnd .= "$clave = $patron $cnd " : $strCnd .= "";
							break;
						case "char":
						case "varchar":
							$strCnd .= "$clave like '%$patron%' $cnd ";
							break;
					}
				}
			}
		}
		$strCnd = substr($strCnd,0,strlen($strCnd)-strlen($cnd)-1); //quitamos la ultima cnd
		$this->freePageResult_noPag();
		return ($strCnd);
	}
}
?>