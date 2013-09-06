<?php

namespace Rasty\factory;


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
	
	
	public static function buildFromUrl( $url){

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