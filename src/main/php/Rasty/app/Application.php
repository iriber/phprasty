<?php

namespace Rasty\app;

/**
 * Punto de entrada de la aplicación.
 * 
 * Es la clase responsable de atender todos los request,
 * construir los componentes y mostrarlos en pantallla.
 *   
 *   
 * @author bernardo
 * @since 03-03-2010
 */
use Rasty\factory\PageFactory;

use Rasty\exception\UserRequiredException;

use Rasty\security\RastySecurityContext;

use Rasty\actions\Forward;

use Rasty\render\HTMLRenderer;

use Rasty\factory\ComponentFactory;

use Rasty\utils\RastyUtils;

class Application{

	
	public function __construct(){
	}
	
	public function execute(Forward $forward=null){
			
		if($forward == null){
		
			$forward = new Forward();
			$forward->addError( RastyUtils::getParamGET( 'error' ) );
			
			$page = PageFactory::buildFromUrl( RastyUtils::getParamGET( 'path' ) );
			
		}else{
		
			$page = PageFactory::build( $forward->getPageName() );
		}
		
				
		
		
		if( !empty($page)){
			$page->setForward( $forward );
			
			try {

				RastySecurityContext::authorize($page);
				
			} catch (UserRequiredException $e) {
				
				$forward = new Forward();
				$forward->setPageName( "Login" );
				$forward->addError( $e->getMessage() );
				
				header ( 'Location: '.  $forward->buildForwardUrl() );
			}
			
			//renderizamos la p�gina.
			$render = $this->getRenderer();
			$render->render( $page->getLayout() );
			
		}else{
			
			echo "<h1>manejar error not found</h1>";
		}
		/*
		//este helper nos ayuda a recuperar el page component espec�fico.
		$map = new RastyMapHelper();
		$page = $map->getPage( $forward->getUrl() );
		
		if( !empty($page)){
			
			//obtenemos la ubicaci�n del descriptor del page.
			$page_file = $page["location"];
			
			//cargamos el descriptor de la p�gina (.page)
			$xml = simplexml_load_file( $page_file  );
	
			//construimos la p�gina dado su descriptor.
			$oPage = ComponentFactory::build( $xml );
			$oPage->setForward( $forward );
			
			try {

				RastySecurityContext::authorize($oPage);
				
			} catch (UserRequiredException $e) {
				
				$forward = new Forward();
				$forward->setUrl( "login" );
				$forward->addError("error", $e->getMessage() );
				
				header ( 'Location: '.  $forward->buildForwardUrl() );
			}
			
			//renderizamos la p�gina.
			$render = $this->getRenderer();
			$render->render( $oPage->getLayout() );			
			
		}else{
			echo "<h1>manejar error not found</h1>";
		}
		*/
	}

	/**
	 * Retorna el renderer: el encargado de renderizar el resultado.
	 */
	protected function getRenderer(){
		//por default ser� un html renderer.
		return new HTMLRenderer();
	}
	
}