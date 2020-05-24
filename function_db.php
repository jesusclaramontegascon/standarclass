<?php

	/*
	 * Devuelve el array con los usuarios y sus identificadores
	 * @param array $params [cod_ent, id_user]
	 * @return array
	 */
	function get_option_usuarios($params)
	{
		$q = "SELECT id, user FROM usuarios ";
		if (isset($params['cod_ent']))
		{
			$q .= "WHERE cod_ent ='". $params['cod_ent'] ."'";
		}
		$q .= " ORDER BY user ASC";
		$result = mysql_query ($q, $GLOBALS['conn']) or die (mysql_error());
		$usuarios = array();
		while($fila = mysql_fetch_assoc($result))
		{
			$usuarios[$fila['id']] = $fila['user'];
		}
		
		return $usuarios;
	}


    /*
	 * Devuelve el array con los archivos del registro
	 * @param array $params [cod_ent, id_user]
	 * @return array
	 */
	function get_option_archivos()
	{
        $cod_ent = $_SESSION['usuario']['cod_ent'];
		$q = "SELECT registro.archivo FROM registro WHERE cod_ent = '".$cod_ent."' GROUP BY registro.archivo ORDER BY registro.archivo ASC ";
		$result = mysql_query ($q, $GLOBALS['conn']) or die (mysql_error());
		$archivos = array();
		while($fila = mysql_fetch_assoc($result))
		{
			$archivos[] = $fila['archivo'];
		}

		return $archivos;
	}


  

	function get_codigo_pais($cod_ent)
	{
	    $q = 'SELECT codigo_pais FROM entidades WHERE cod_ent = "' . $cod_ent .'"';
    	$result = mysql_query ($q, $GLOBALS['conn']) or die (mysql_error());
		if (mysql_num_rows($result) > 0)
		{
		    $cod_pais = mysql_result($result, 0, 'cod_pais');
		    return $cod_pais;
		}
	}


    function get_id_clasificador_cobro_familiar_ambos()
    {
        $clasificadores = array();
        $conceptos = array();
        $q_conceptos = "SELECT Id
                        FROM conceptos_cobro
                        WHERE tfa ='F'
                        and cod_ent ='".$_SESSION['usuario']['cod_ent']."'";
        $r_conceptos = mysql_query($q_conceptos, $GLOBALS['conn']) or die (mysql_error($GLOBALS['conn']));
        while($concepto = mysql_fetch_array($r_conceptos))
        {
            $conceptos[] = $concepto['Id'];
        }



        $q_clasificadores = 'SELECT Id, conceptos_cobro FROM clasificador_cobros
              WHERE cod_ent = "' . $_SESSION['usuario']['cod_ent'] .'"';
    	$r_clasificadores = mysql_query ($q_clasificadores, $GLOBALS['conn']) or die (mysql_error($GLOBALS['conn']));
		while($clasificador = mysql_fetch_array($r_clasificadores))
        {
            $conceptos_cobro_clasificador = explode(',', $clasificador['conceptos_cobro']);
            $presente = false;
            foreach($conceptos_cobro_clasificador as $concepto_cobro)
            {
                
                if (in_array($concepto_cobro, $conceptos))
                {
                    $presente = true;
                }
            }
            if($presente)
            {
                $clasificadores[] = $clasificador['Id'];
            }
        }

        return $clasificadores;
    }
	
	// Función para ajax / código postal
	function get_provincia_by_codigopostal($id_cp = "", $cod_pais = "")
	{
		if (!empty($id_cp))
		{
			$q = sprintf('SELECT cod_prv FROM provincias WHERE cp = "%s"', $id_cp);
            if ($cod_pais != '') $q .= ' AND cod_pais ="'.$cod_pais.'"';
    		$result = mysql_query ($q, $GLOBALS['conn']) or die (mysql_error());
    		if (mysql_num_rows($result) > 0)
    		{
    		    $cod_prv = mysql_result($result, 0, 'cod_prv');
    		    return $cod_prv;
    		}
    		
		}
		return false;
	}


	
	
	
	function get_data_entidad($id_entidad = "")
	{
		if (!empty($id_entidad))
		{
			// SELECCIONAR SOLO 1 REGISTRO
			$q = 'SELECT *  FROM entidades WHERE Id = ' . $id_entidad;
		}
		else
		{
			// SELECCIONAR TODOS LOS REGISTROS
			$q = 'SELECT *  FROM entidades WHERE sub_ent = "0"';
		}
		$result = mysql_query ($q, $GLOBALS['conn']) or die (mysql_error());
		$data_entidades = array();
		while($fila = mysql_fetch_assoc($result))
		{	
			array_push($data_entidades, $fila);
		}
		
		return $data_entidades;		
	}
	


	function get_data_pref_socios($id_preferencia = "", $id_user = "", $cod_ent = "")
	{
		if ($id_preferencia != '')
		{
			if(exists_preferencia($id_preferencia))
			{
				// Seleccionar por id
				$q = 'SELECT * FROM pref_socios WHERE Id = "' . $id_preferencia. '"';
			}
			else
			{
				return false;
			}
		}
        else if($id_user != '')
        {
            // Todos los registros pertenecientes a un usuario
            $q = 'SELECT * FROM  pref_socios  WHERE user = "' . $id_user . '" 
				  AND cod_ent = "'.$cod_ent.'"
				 ';
        }
		else
		{
            // Registros pertenecientes a una entidad
			$q = 'SELECT * FROM pref_socios 
                  WHERE cod_ent = "' . $_SESSION['usuario']['cod_ent'] . '"
                 ';
		}
		$result = mysql_query ($q, $GLOBALS['conn']) or die (mysql_error());
		$data = array();
		while($fila = mysql_fetch_assoc($result))
		{	
			$data[] = $fila;
		}
		
		return $data;
	}

	

	
	function get_data_delegado($id_delegacion = '', $id_delegado = '')
	{
		if ($id_delegado != '')
		{
			// SELECCIONAR SOLO 1 REGISTRO
			$q = 'SELECT *  FROM delegados WHERE Id = ' . $id_delegado;
		}
		elseif($id_delegacion != "")
		{
			// SELECCIONAR TODOS LOS REGISTROS
			$q = 'SELECT *  FROM delegados WHERE cod_del = "' . $id_delegacion .'"' ;
		}
		else
		{
		    $q = 'SELECT *  FROM delegados WHERE cod_ent = ' . $_SESSION['usuario']['cod_ent'];
		}
		
		$result = mysql_query ($q, $GLOBALS['conn']) or die (mysql_error());
		$data_delegados = array();
		while($fila = mysql_fetch_assoc($result))
		{	
			array_push($data_delegados, $fila);
		}
		
		return $data_delegados;		
	}


	
    

    function get_data_cobros_ordenante($cod_ent = null, $sub_ent = null, $ano = null)
	{
        $q = 'SELECT * FROM cobros_ordenante
              WHERE cod_ent = "'.$cod_ent.'"
              AND sub_ent = "'. $sub_ent.'"'
              .($ano != null ? ' AND ano = "'.$ano.'" ' : '').
              'ORDER BY Id DESC
              LIMIT 0,1';
              //echo "ano = ".$ano;
        $result = mysql_query ($q, $GLOBALS['conn']) or die (mysql_error());
		$cobros = array();
		while($fila = mysql_fetch_assoc($result))
		{
			$cobros[] = $fila;
		}
        
		return $cobros;

	}

    function get_data_clasificador_cobros($params = array(), $page = 1, $number_rows = 20, $allresults = false)
	{
		if (isset ($params['Id']))
		{
			// SELECCIONAR SOLO 1 REGISTRO
            $q = 'SELECT *  FROM clasificador_cobros WHERE Id = ' . $params['Id'];
            $result = mysql_query ($q, $GLOBALS['conn']) or die (mysql_error());
            $data = array();
            while($fila = mysql_fetch_assoc($result))
            {
                array_push($data, $fila);
            }
		}
		else
		{
		    $q = 'SELECT *
                FROM clasificador_cobros WHERE cod_ent = "'.$_SESSION['usuario']['cod_ent'].'" ORDER BY tipo_clasificador ASC';
            $data = get_data_paginated($q, $page, $number_rows, $allresults );
		}

		return $data;
	}
    
	

	
	function get_data_suscriptor($id_suscriptor = "", $id_entidad = "")
	{		
		if ($id_entidad == '') $id_entidad = $_SESSION['usuario']['cod_ent'];  
		
		if ($id_suscriptor != '')
		{
			// SELECCIONAR SOLO 1 REGISTRO
			$q = 'SELECT *  FROM suscriptores 
				  WHERE Id = "' . $id_suscriptor . '"
				  AND cod_ent = "'. $id_entidad .'"
				  ';
		}
		$result = mysql_query ($q, $GLOBALS['conn']) or die (mysql_error());
		$data_suscriptores = array();
		while($fila = mysql_fetch_assoc($result))
		{	
			$data_suscriptores[] = $fila;
		}
		
		return $data_suscriptores;		
	}
	

	



	



	


    


   
    

	function get_data_carnets($carnet = "")
	{
		if ($carnet != '')
		{
			// SELECCIONAR SOLO 1 REGISTRO
			$q = 'SELECT * FROM carnets
                  WHERE carnet = "' . $carnet . '" AND
                  cod_ent = "' . $_SESSION['usuario']['cod_ent'] .'"
				  ';
            
		}
		else
		{
			$q = 'SELECT * FROM carnets WHERE cod_ent = ' 
			. $_SESSION['usuario']['cod_ent'] . ' ORDER BY carnet';

		}
		$result = mysql_query ($q, $GLOBALS['conn']) or die (mysql_error());
		$data_carnets = array();
		while($fila = mysql_fetch_assoc($result))
		{	
			$data_carnets[] = $fila;
		}
		
		return $data_carnets;
	}
	

	

	function get_lastid_cnae()
	{
		$q = 'SELECT Id FROM cnae ORDER BY Id desc LIMIT 0, 1';
		$result = mysql_query ($q, $GLOBALS['conn']) or die (mysql_error());
		$data_last_id_actividad = array();
		while ($fila = mysql_fetch_array($result)):
			$cod_actividad_fetched = $fila['Id'];
		endwhile;
		return $cod_actividad_fetched + 1;	
	}

	
	

?>