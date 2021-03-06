<?php
namespace Rasty\app;

use Rasty\actions\Forward;

use Rasty\utils\ReflectionUtils;

/**
 *  
 * @author bernardo
 * @since 03-03-2010
 */
class ApplicationJson{

	
	public function ApplicationJson(){
	}
	
	public function execute(Forward $forward=null){
			
		$path = $_GET['path'] ;
		
		$map = new RastyMapHelper();
		
		$action_description =  $map->getAction($path);
		
		$action = ReflectionUtils::newInstance( $action_description["class"] );
		
		echo json_encode( $action->execute() );
				
	}	
}