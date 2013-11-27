<?php
namespace Rasty\app;

use Rasty\factory\ComponentDescriptor;

use Rasty\utils\RastyCache;

/**
 *  
 * @author bernardo
 * @since 03-03-2010
 */
use Rasty\factory\ComponentFactory;

use Rasty\utils\ReflectionUtils;

use Rasty\utils\RastyUtils;

use Rasty\exception\UserRequiredException;

use Rasty\security\RastySecurityContext;

use Rasty\actions\Forward;


class ApplicationComponent extends Application{

	
	public function ApplicationComponent(){
	}
	

	public function execute(Forward $forward=null){
			
		//tomamos el name ingresado
		$name = RastyUtils::getParamGET( 'name' ) ;

		//tomamos el id del component
		$id = RastyUtils::getParamGET( 'componentId' ) ;
		
		
		//este helper nos ayuda a recuperar el component específico.
		$map = new RastyMapHelper();
		$component = $map->getComponent($name);
		
		
		if( !empty($component)){
			
			//obtenemos el descriptor xml
// 			$xml = $this->getXml($component, $name);
	
			$component_file = $component["app_path"] ."/" . $component["location"] . "/$name.rasty" ;
			
			//construimos el component dado su descriptor.
// 			$oComponent = ComponentFactory::build( $xml, null, null);
			$descriptor = ComponentDescriptor::buildFromFile($component_file);
			$oComponent = ComponentFactory::buildByDescriptor($descriptor);
			$oComponent->setId($id);
			
		
			try {

				RastySecurityContext::authorizeComponent($oComponent);
				
			} catch (UserRequiredException $e) {
				
				$forward = new Forward();
				$forward->setPageName( "Login" );
				$forward->addError( $e->getMessage() );
				
				header ( 'Location: '.  $forward->buildForwardUrl() );
			}
			
			
			
			//lo renderizamos.
			$render = $this->getRenderer();
			$render->render( $oComponent );			
			
		}else{
			echo "<h1>manejar error not found</h1>";
		}
		
	}
	
	private function getXml($component, $name){

// 		//chequeamos si está en caché
// 		$cache = RastyCache::getInstance();
// 		if($cache->contains("RastyComponentXML_$name") )
// 			$xml = $cache->fetch("RastyComponentXML_$name");
// 		else{
		
// 			//obtenemos la ubicación del descriptor del component.
// 			$component_file = $component["app_path"] ."/" . $component["location"] . "/$name.rasty" ;
				
// 			//cargamos el descriptor
// 			$xml = simplexml_load_file( $component_file  );
			
// 			$cache->save("RastyComponentXML_$name", $xml);
// 		}
		
		
		//obtenemos la ubicación del descriptor del component.
		$component_file = $component["app_path"] ."/" . $component["location"] . "/$name.rasty" ;
		
		//cargamos el descriptor
		$xml = simplexml_load_file( $component_file  );
		
		return $xml;
	}
	
}