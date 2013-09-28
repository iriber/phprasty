<?php
namespace Rasty\utils;
/**
 * Utilidades armar links en la app (urls internas).
 * 
 * @author bernardo
 * @since 15-08-2013
 */
use Rasty\conf\RastyConfig;

use Rasty\app\RastyMapHelper;

class LinkBuilder{
	
	/**
	 * extensión para actions.
	 * @var string
	 */
	private static $actionExtension = ".do";
	
	/**
	 * extensión para actions ajax.
	 * @var string
	 */
	private static $ajaxExtension = ".json";
	
	/**
	 * extensión para componentes.
	 * @var string
	 */
	private static $componentExtension = ".rasty";
	
	/**
	 * extensión para páginas.
	 * @var string
	 */
	private static $pageExtension = ".html";
	
	/**
	 * crea la url para una página dado su nombre
	 * y los parámetros.
	 * @param string $pageName
	 * @param $params
	 */
	public static function getPageUrl($pageName, $params=null){
		$map = RastyMapHelper::getInstance();
		
		$webPath = RastyConfig::getInstance()->getWebPath();
		
	    $url = $webPath . $map->getPageUrl( $pageName ) . self::$pageExtension;
	    
	    if( !empty($params) ){
	    	$url =  self::addParams( $url, $params );
	    }
	    
	    return $url;
	}
	
	/**
	 * crea la url para una acción dado su nombre
	 * 
	 * @param string $actionName
	 */
	public static function getActionUrl($actionName, $params=null){
		$map = RastyMapHelper::getInstance();
		$webPath = RastyConfig::getInstance()->getWebPath();
	    
		$url = $webPath . $map->getActionUrl( $actionName ) . self::$actionExtension;
		
		if( !empty($params) ){
			$url =  self::addParams( $url, $params );
		}
		 
		return $url;
	}
	
	/**
	 * crea la url para una acción a ejecutar por ajax dado su nombre
	 * 
	 * @param string $actionName
	 */
	public static function getActionAjaxUrl($actionName, $params=null){
		
		$map = RastyMapHelper::getInstance();
		$webPath = RastyConfig::getInstance()->getWebPath();
	    
		$url = $webPath . $map->getActionUrl( $actionName ) . self::$ajaxExtension;
		
		if( !empty($params) ){
			$url =  self::addParams( $url, $params );
		}
		 
		return $url;
		
	    
	    
	}

		
	/**
	 * crea la url para un componente dado su tipo
	 * y los parámetros.
	 * @param string $type
	 * @param $params
	 */
	public static function getComponentUrl($type, $params=null){
		//$map = RastyMapHelper::getInstance();
		$webPath = RastyConfig::getInstance()->getWebPath();
	    $url = $webPath .  $type . self::$componentExtension;
	    
	    if( !empty($params) ){
	    	$url =  self::addParams( $url, $params );
	    }
	    
	    return $url;
	}
	
	
	/**
	 * agrega parámetros a la url
	 * @param $url
	 * @param $params
	 */
	public static function addParams($url, $params=null){

		if( empty($params) )
			return $url;
		
		$arrayparams = array();
		if( count( $params ) > 0){
			foreach ($params as $name => $value) {
				$arrayparams[] = "$name=" . urlencode($value);
			}
		}

		$strParams = "";
		if( count($arrayparams) > 0 ){
			$strParams = "?".  implode("&", $arrayparams ) ;
		}

		return $url . $strParams;
		
	}
}