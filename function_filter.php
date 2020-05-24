<?php
/*
** Archivo para validar las entradas del usuario
*/

/*
** Función para validar números
*/
function validate_email($email) {
    $valid_email = filter_var($email, FILTER_VALIDATE_EMAIL);
        
    return $valid_email;
}


/*
 * Función para validar números
 */
function validate_number($number) {
	$regex = '/^[0-9]+$/';
    if(preg_match($regex, $number)) return true;
    else return false;
}


/*
 * Función para validar letras
 */
function validate_letters($letters) {
	$regex = '/^[a-zñÑáÁéÉíÍóÓúÚ]+$/i';
    if(preg_match($regex, $letters)) return true;
    else return false;
}

/*
 * Función para validar DNI duplicados 
 */
function validate_dni_duplicados($dni) {
	require_once '../inc/connection.php';
	$q = 'SELECT COUNT(Id) AS total FROM socios WHERE dni = "'. $dni . '"';
	$result =  mysql_query($q);
	$total = mysql_result($result, 0, 'total');
	if ($total > 1)
	return false;
	else 
	return true;
}

/*
 * Función para validar cuenta corriente
 */
function validate_ccc($ccc)
{
    if(strlen($ccc) == 20 && is_numeric($ccc))
    {
    	$entidad = substr($ccc, 0, 4);
    	$oficina = substr($ccc, 4, 4);
    	$control = substr($ccc, 8, 2);
    	$cuenta  = substr($ccc, 10, 10);
    	
    	$ceo = '00'.$entidad.$oficina;
    	
    	$fac = array(1, 2, 4, 8, 5, 10, 9, 7, 3, 6);
    	
    	for($i = 0; $i < count($fac); $i++)
    	{
    		$suma += $fac[$i] * substr($ceo, $i, 1);
    		$suma_cuenta += $fac[$i] * substr($cuenta, $i, 1);
    	}
    	
    	$resto = $suma % 11;
    	$cr = $resto == 1 || $resto == 0 ? $resto : 11 - $resto ;
        
    	$resto_cuenta = $suma_cuenta % 11;
    	$cr2 = $resto_cuenta == 1 || $resto_cuenta == 0 ? $resto_cuenta : 11 - $resto_cuenta ;
    	
    	return $control == $cr.$cr2;
    }
    else
    {
    	return false;
    }
    
}



/*
 * Función para validar fechas
 */
function comparar_fechas($primera, $segunda)
 {
  $valoresPrimera = explode ("/", $primera);   
  $valoresSegunda = explode ("/", $segunda); 
  $diaPrimera    = $valoresPrimera[0];  
  $mesPrimera  = $valoresPrimera[1];  
  $anyoPrimera   = $valoresPrimera[2]; 
  $diaSegunda   = $valoresSegunda[0];  
  $mesSegunda = $valoresSegunda[1];  
  $anyoSegunda  = $valoresSegunda[2];
  $diasPrimeraJuliano = gregoriantojd($mesPrimera, $diaPrimera, $anyoPrimera);  
  $diasSegundaJuliano = gregoriantojd($mesSegunda, $diaSegunda, $anyoSegunda);     
  if(!checkdate($mesPrimera, $diaPrimera, $anyoPrimera)){
    // "La fecha ".$primera." no es válida";
    return 0;
  }elseif(!checkdate($mesSegunda, $diaSegunda, $anyoSegunda)){
    // "La fecha ".$segunda." no es válida";
    return 0;
  }else{
    return  $diasPrimeraJuliano - $diasSegundaJuliano;
  } 
} 


?>