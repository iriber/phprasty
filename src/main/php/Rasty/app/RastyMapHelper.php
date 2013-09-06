<?php
namespace Rasty\app;

/**
 * Colabora con el mapeo de los componentes.
 * 
 * @author bernardo
 * @since 02-03-2010
 * 
 */

class RastyMapHelper{
	
	private static $instance;
	
	//array con el mapeo de los componentes. 
	private $components_map =  array(); //( [component_name] = array( location_of_descriptor, app_path, web_path) )
	
	//array con el mapeo de las pages.
	private $pages_map =  array(); // ( [page] = array(location_of_descriptor, url, app_path, web_path) )
	
	//array con el mapeo de las actions.
	private $actions_map =  array(); // ( [action] = array(url, name, class) )
	
	
	//M�todo constructor
	
	public function __construct(){

		$composite = LoadRasty::getInstance();

		foreach ($composite->getComponents() as $component) {
			$this->setComponent($component['name'], $component['location'], $component['app_path'], $component['web_path']);
		}
		
		foreach ($composite->getPages() as $page) {
			$this->setPage($page['name'], $page['location'], $page['url'], $page['app_path'], $page['web_path']);
		}
		
		foreach ($composite->getActions() as $action) {
			$this->setAction($action['name'], $action['class'], $action['url']);
		}
		

	}

	public static function getInstance(){
		if (  !self::$instance instanceof self ) {
			self::$instance = new self;
		}
		
		return self::$instance;
	}
	
	function getComponent($name) {
		if( array_key_exists($name, $this->components_map) )
			return $this->components_map[$name];
		echo " no está!! ( $name )";	
	}
	
	/*
	function getPage($name) {
		return $this->pages_map[$name];
	}*/
	
	function getPage($url) {
		//var_dump($this->pages_map);
		return $this->pages_map[$url];
	}

	function getPageByName($name) {

		foreach ($this->pages_map as $value) {
			$current =  $value["name"];
			if( $current == $name )
				return $value;
		}
		
	}
	
	function getAction($url) {
		//var_dump($this->pages_map);
		return $this->actions_map[$url];
	}
	

	function setComponent($name,$location, $app_path, $web_path){
		$this->components_map[$name]= array( "location"=>$location, "app_path" => $app_path, "web_path" => $web_path );		
	}	
	
	function setPage($name,$location,$url, $app_path, $web_path){
		//$this->pages_map[$name]=array(location=>$location, url=>$url);
		$this->pages_map[$url]=array("name"=>$name, "location"=>$location, "app_path" => $app_path, "web_path" => $web_path );
	}
	
	function setAction($name,$class,$url){
		//$this->pages_map[$name]=array(location=>$location, url=>$url);
		$this->actions_map[$url]=array("name"=>$name, "class"=>$class );
	}
	
	function getComponentDescriptor( $type ){
		
		$component = $this->getComponent( $type );
		//$component_descriptor = APP_PATH . $component_location . "/$type.rasty";
		$component_descriptor = $component['app_path']. $component['location'] . "/$type.rasty";
		return $component_descriptor;
		
	}
	
	function getComponentTemplatePath( $type ){
		
		$component = $this->getComponent( $type );
		//$component_template = APP_PATH . $component_location ;
		$component_template = $component['app_path']. $component['location'] ;
		return $component_template;
	}

	function getComponentWebPath( $type ){
		$component = $this->getComponent( $type );
		$component_web_path = $component['web_path']. $component['location'] ;
		return $component_web_path;
	}
	
	function getPageAppPath( $type ){
		
		$page = $this->getPageByName( $type );
		//$component_template = APP_PATH . $component_location ;
		$page_app_path = $page['app_path'] ;
		return $page_app_path;
	}
	function getPageUrl($name) {

		foreach ($this->pages_map as $url => $value) {
			$current =  $value["name"];
			if( $current == $name )
				return $url;
		}
		
	}
	
	function getActionUrl($name) {

		foreach ($this->actions_map as $url => $value) {
			$current =  $value["name"];
			if( $current == $name )
				return $url;
		}
		
	}		
}