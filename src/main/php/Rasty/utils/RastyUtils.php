<?php
namespace Rasty\utils;
/**
 * Utilidades para rasty.
 * 
 * @author bernardo
 * @since 13-09-2011
 */
class RastyUtils{
	
	public static function getParam($from, $name, $default='', $filter = true, $encode = true){
		if (isset ( $from [$name] )){
			if($filter){
				
				$inputFilter = new InputFilter();
				$value = $inputFilter->process($from[$name]);
				//if($encode)
					//$value = InputFilter::urlEncode($value);
			}
			else
				$value = $from [$name];
			
		}
		if(empty($value))
			$value = $default;
		return $value;
	}
	
	public static function getParamSESSION($name, $default='', $filter = true, $encode = true){
		/*
		if (isset ( $_SESSION [$name] )){
			if($filter){
				$inputFilter = new InputFilter();
				$value = $inputFilter->process($_SESSION[$name]);
				if($encode)
					$value = InputFilter::urlEncode($value);
			}
			else
				$value = $_SESSION [$name];
			
		}
		if(empty($value))
			$value = $default;
		return $value;*/
		return self::getParam( $_SESSION, $name, $default, $filter, $encode );
	}
	
	public static function getParamGET($name, $default='', $filter = true, $encode = true){
		
		return self::getParam( $_GET, $name, $default, $filter, $encode );
	}

	public static function getParamPOST($name, $default='', $filter = true, $encode = false){
		
		return self::getParam( $_POST, $name, $default, $filter, $encode );
	}
		
	/**
	 * si cd1=cd2, formatea la salida :
	 *     'cd1' selected='selected'
	 *     
	 * @param unknown_type $cd1
	 * @param unknown_type $cd2
	 * @return unknown_type
	 */
	public static function selected($cd1, $cd2){
		$value='';
		if($cd1==$cd2){
				$value = "'". $cd1. "'" . " selected='selected'" ;
		}else{
				$value = $cd1;				
		}				
		return $value;
	}
	
	public static function quitarEnters($value){
		$value = str_replace("\n", "", $value);
		return str_replace("\r", "", $value);
	}

	public static function formatMessage($msg, $params){
		
		if(!empty($params)){
			
			$count = count ( $params );
			$i=1;
			while ( $i <= $count ) {
				$param = $params [$i-1];
				
				$msg = str_replace('$'.$i, $param, $msg);
				
				$i ++;
			}
			
		}
		return $msg;
		
	
	}
	
}