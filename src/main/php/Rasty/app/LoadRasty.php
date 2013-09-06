<?php
namespace Rasty\app;

use Rasty\conf\RastyConfig;

use Rasty\utils\Logger;

/**
 * Carga el mapeo de componentes y p�ginas desde un xml.
 * 
 * @author bernardo
 * @since 26-04-2010
 * 
 */
class LoadRasty{

	private static $instance;
	
	private $libraries;
	private $components;
	private $pages;
	private $actions;

	private function __construct(){
		 $this->components = array();
		 $this->pages = array();
		 $this->libraries = array();
		 $this->actions = array();
		 
	}

	public static function getInstance(){
		if (  !self::$instance instanceof self ) {
			self::$instance = new self;
			//self::$instancia->load();
			
		}
		
		return self::$instance;
	}

	
	public function loadXml( $xmlPath, $app_path="", $web_path='' ){
	
		$xml = simplexml_load_file( $xmlPath );
		//Logger::log("loading Rasty from $xmlPath app_path $app_path  web_path $web_path");	
		self::load( $xml, $app_path, $web_path );
	}
	/**
	 * carga desde un xml las pages y los components.
	 * @return unknown_type
	 */
	public function load($xml=null, $app_path="", $web_path=''){

		if(empty($app_path))
			$app_path = RastyConfig::getInstance()->getAppPath();
			
		if(empty($web_path))
			$web_path = RastyConfig::getInstance()->getWebPath();
			
		if( empty($xml))
			$xml = simplexml_load_file($app_path .  'conf/rasty.xml');
		
			
		/* se cargan los componentes. */
		foreach ($xml->component as $component) {
			$current_component = array();
			foreach ($component->attributes() as $key=>$value) {
				$current_component[$key] = $value . '';
					
			}
			
			//le seteamos los path relativos y absolutos.
			$current_component['web_path'] = $web_path;
			$current_component['app_path'] = $app_path;
			
			
			$this->addComponent( $current_component );
			
		}
		
		/* se cargan las pages. */
		foreach ($xml->page as $page) {
			$current_page = array();
			foreach ($page->attributes() as $key=>$value) {
				
				$current_page[$key] = $value . '';	
			}
			//le seteamos los path relativos y absolutos.
			$current_page['web_path'] = $web_path;
			$current_page['app_path'] = $app_path;
			
			
			$this->addPage( $current_page );
		}

		
		/* se cargan las actions. */
		foreach ($xml->action as $action) {
			$current_action = array();
			foreach ($action->attributes() as $key=>$value) {
				
				$current_action[$key] = $value . '';	
			}
			//le seteamos los path relativos y absolutos.
			
			
			$this->addAction( $current_action );
		}
		
		/* se cargan las librerías. */
		/*
		foreach ($xml->library as $library) {
			$current_library = array();
			foreach ($library->attributes() as $key=>$value) {
				$current_library[$key] = $value . '';	
			}
			$xml = simplexml_load_file( $current_library['app_path'] .  '/conf/rasty.xml');
			$this->load( $xml , $current_library['app_path'], $current_library['web_path'] );			
		}
		*/
		
	}
	
	
	
	public function addComponent($component){
		//Logger::logObject($component);
		return $this->components[]=$component;
	}	
	
	public function getComponents(){
		return $this->components;
	}	
	
	public function getPages(){
		return $this->pages;
	}	
	
	public function addPage($page){
		return $this->pages[]=$page;
	}	
	
	
	public function addAction($action){
		return $this->actions[]=$action;
	}
	
	public function getActions(){
		return $this->actions;
	}
	
	public function getComponentByName( $name){
		foreach ($this->getComponents() as $component) {
			if( $component['name'] == $name )
				return $component;
		}
		return null;
	}

	
}