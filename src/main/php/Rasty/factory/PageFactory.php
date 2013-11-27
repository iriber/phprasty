<?php

namespace Rasty\factory;


use Rasty\cache\RastyCache;

/**
 * Factory para Page.
 * 
 * @author bernardo
 * @since 09/08/2013
 *
 */
use Rasty\utils\Logger;

use Rasty\app\RastyMapHelper;

use Rasty\utils\ReflectionUtils;
use Rasty\components\RastyPage;

class PageFactory{

	public function __construct(){
				
	}
		
	/**
	 * Construye una página a partir del nombre
	 *
	 * @param strin $name
	 * @return RastyPage
	 */
	public static function build( $name ){
	
// 		//chequeamos si está en caché
// 		$cache = RastyCache::getInstance();
// 		if($cache->contains("RastyPage_$name") )
// 			$page = $cache->fetch("RastyPage_$name");
// 		else{
	
// 			$page = self::myBuild($name);
	
// 			$cache->save("RastyPage_$name", $page);
// 		}
	
		$page = self::myBuild($name);
		return $page;
	}
	
	
	public static function buildFromUrl( $url){
	
// 		//chequeamos si está en caché
// 		$cache = RastyCache::getInstance();
// 		if($cache->contains("RastyPage_$url") )
// 			$page = $cache->fetch("RastyPage_$url");
// 		else{
	
// 			$page = self::myBuildFromUrl($url);
	
// 			$cache->save("RastyPage_$url", $page);
// 		}
	
		$page = self::myBuildFromUrl($url);
		return $page;
	}
	/**
	 * Construye una página a partir del nombre
	 * 
	 * @param strin $name
	 * @return RastyPage
	 */
	private static function myBuild( $name ){

		$map = new RastyMapHelper();
		$pageDescriptor = $map->getPageByName($name);
			
		$page = null;
			
		if( !empty($pageDescriptor)){
					
			//obtenemos la ubicaci�n del descriptor del page.
			//			$page_file = $pageDescriptor["location"];
			$page_file =  $pageDescriptor["app_path"] . "/" . $pageDescriptor["location"];
			//cargamos el descriptor de la p�gina (.page)
			$xml = simplexml_load_file( $page_file  );
		
			//construimos la p�gina dado su descriptor.
			$page = ComponentFactory::build( $xml );
		
				
		}else{
			//TODO manejar el error.
		}	
				
		return $page;
	}
	
	
	private static function myBuildFromUrl( $url){

		$map = new RastyMapHelper();
		$pageDescriptor = $map->getPage($url);
		
		$page = null;
		
		if( !empty($pageDescriptor)){
			
			//Logger::logObject($pageDescriptor);
			
			//obtenemos la ubicaci�n del descriptor del page.
//			$page_file = $pageDescriptor["location"];
			$page_file =  $pageDescriptor["app_path"] . "/" . $pageDescriptor["location"];		
			//cargamos el descriptor de la p�gina (.page)
			$xml = simplexml_load_file( $page_file  );
	
			//construimos la p�gina dado su descriptor.
			$page = ComponentFactory::build( $xml );
						
			
		}else{
			//TODO manejar el error.
		}
		
		return $page;
	}
	
	
}

?>