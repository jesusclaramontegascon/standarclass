<?php
	
function fecha_es($fecha)
{
    if ($fecha == '0000-00-00 00:00:00' || $fecha == '') return false;
    $marca_de_tiempo = strtotime($fecha);
    $fecha_es = strftime('%d/%m/%Y ', $marca_de_tiempo );
    
    return $fecha_es;
}

function fecha_hora($fecha)
{
    if ($fecha != '')
    {
        $marca_de_tiempo = strtotime($fecha);
        $fecha = strftime('%d/%m/%Y %H:%M', $marca_de_tiempo );
    }
    
    return $fecha;
}

/*
 * Función para paginar
 * @param array $params [url, page, query, last_page, limit_pages]
 * @return string
 */
function get_paginas ($params = array())
{
	if ($params['page'] == '') $params['page'] = 1;
	if ($params['limit_pages'] == '') $params['limit_pages'] = 3;
	
	
	$links = '<ul id="pages" class="floated_content">';
	
	
	if(($params['page'] - $params['limit_pages']) <= 0)
	{
		$i = 1;
		if ($params['last_page'] <= $params['limit_pages'])
		{
			$max_to_show = $params['last_page'];
		}
		else
		{
			$max_to_show = $params['limit_pages'] * 2;
		}
	}
	else
	{
		$i = $params['page'] - $params['limit_pages'];
		if(($params['page'] + $params['limit_pages']) < $params['last_page'])
		{
			$max_to_show = $params['page'] + $params['limit_pages'] ;
		}
		else
		{
			$max_to_show = $params['last_page'];
		}
	}
	
	
	if($i != 1)
	{
		$links .= '<li><a href="'. $params['url'] .'?';
		if (isset($params['query']))
		{
			$links .= $params['query'].'&';
		}		
		$links .= 'page=1">Primera</a></li>';
	}
		
	for($i; $i <= $max_to_show; $i++)
	{	
		
		if($params['page'] == $i) 
		{ 
			$links .= '<li class = "active">'.$i.'</li>';
		}
		else
		{
			$links .= '<li><a href="'. $params['url'] .'?';
			if (isset($params['query']))
			{
				$links .= $params['query'].'&';
			}		
			$links .= 'page='. $i;
			$links .= '">'. $i .'</a></li>';
		}
	}

	if ($params['last_page'] != $max_to_show)
	{
		$links .= '<li><a href="'. $params['url'] .'?';
		if (isset($params['query']))
		{
			$links .= $params['query'].'&';
		}		
		$links .= 'page='.$params['last_page'].'">Última</a></li>';
	}
	
	
	$links .= '</ul>';
	if(!isset($_GET['print']))
    {   
	return $links;
    }
}




function format_address($via, $address, $urb, $num, $piso, $letra, $portal, $esc, $km, $cp ="", $cpsec = "",$provincia ="", $pais  ="")
{
	$address_val = array();

	if ($via != ''):
        $address_val['direccion'] .= $via . ' ';
    endif;
    
    if ($address != ''):
        $address_val['direccion'] .= $address;
    endif;
		
	if ($urb != ''):
		$address_val['direccion'] .= ' ' . $urb;
	endif;
	
	if ($num != ''):
		$is_num = abs( substr($num, 0, 1) );
		if ($is_num >0):
			$address_val['direccion'] .= ' Nº ' . $num; // quito la coma + spc
			else:
			$address_val['direccion'] .= ' ' . $num; // quito la coma + spc
		endif;
		if ($piso != '' || $letra != '' || $portal != '' || $esc != '' || $km != ''):
			$address_val['direccion'] .= ', ';
		endif;
	endif;
	
	if ($piso != ''):
		if ( abs((strlen($piso)-1)) >0 ):
			$address_val['direccion'] .= $piso . ' '; // he añadido espacio
			else:
			$address_val['direccion'] .= $piso . 'º ';
		endif;
	endif;
	
	if ($letra != ''):
		$address_val['direccion'] .= $letra;
	endif;
	
	if ($portal != ''):
		$address_val['direccion'] .= ' PORTAL.' . $portal;
	endif;

	if ($esc != ''):
		$address_val['direccion'] .= ' ESC.' . $esc;
	endif;

	if ($km != ''):
        $address_val['direccion'] .= ' KM.' . $km;
    endif;      
    
    /*if ($cp!= ''):
        $address_val['localidad'] .= '('.$cp.')';
    endif;      
    */
    if ($cp != ''):
        $localidad = current(get_data_localidad($cp, $cpsec, $cod_pais));
        $address_val['localidad'] .= ' ' . mb_strtoupper($localidad['localidad'], 'UTF-8');
    endif;      
    
    
    if ($provincia!= ''):
        $provincia = current(get_data_provincia($provincia, null, $pais));
        $address_val['provincia'] .= mb_strtoupper($provincia['provincia'], 'UTF-8');
    endif;      
    
    if ($pais!= ''):
        $pais = current(get_data_pais($pais));
        $address_val['pais']  .= ' (' . mb_strtoupper($pais['pais'], 'UTF-8').')';
    endif;      
    

	return $address_val;
}


?>