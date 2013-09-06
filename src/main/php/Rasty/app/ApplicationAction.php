<?php
namespace Rasty\app;

use Rasty\utils\ReflectionUtils;

/**
 *  
 * @author bernardo
 * @since 03-08-2012
 */
class ApplicationAction{

	
	public function __construct(){
	}
	
	public function execute(){
			
		$path = $_GET['path'] ;
		
		$map = new RastyMapHelper();
		
		$action_description =  $map->getAction($path);
		
		$action = ReflectionUtils::newInstance( $action_description["class"] );
		
		$forward = $action->execute();
				
		header ( 'Location: '.  $forward->buildForwardUrl() );
		/*
		$app = new Application();
		$app->execute( $forward );
		*/
	}		
	
}