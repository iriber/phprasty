<?php

namespace Rasty\components;

use Rasty\conf\RastyConfig;

use Rasty\app\RastyMapHelper;

use Rasty\utils\XTemplate;

use Rasty\parser\RastyParser;

use Rasty\i18n\Locale;

abstract class AbstractComponent{

	protected $id;
	
	protected $templateLocation;
	
	protected $config;

	protected $components=array();

	protected $componentPath;
	
	public function render(){
		
		//contenido propio del componente.
		$content = $this->getContent();
		
		//obtenemos los contenidos de cada uno de los subcomponentes.
		
		//reemplazamos los tags <component ... /> por el contenido correspondiente.
		$content = RastyParser::parse( $content, $this->components, $this);
		
		return $content;
	}
	
	/**
	 * dado el nombre de una página retorna el link
	 * Enter description here ...
	 * @param unknown_type $name
	 */
	public function getLinkToPage($name){
		
	}
	
	public abstract function getContent();
	
	public function getXTemplate($file_template=null){
		
		$map = new RastyMapHelper();
		
		if(empty( $file_template ) ){
			//debe renderizarse el componente y los componentes que contiente.
			$file_template = RastyConfig::getInstance()->getAppPath() . "/" .  $this->getTemplateLocation();
	
			if( !file_exists( $file_template )){
				
				//buscamos dada la ubicaci�n del .composite.
				$file_template = $map->getComponentTemplatePath( $this->getType() ). "/"  .  $this->getTemplateLocation() ;
				
			}
		}
		$xtpl = new XTemplate( $file_template );
		
		$xtpl->assign('WEB_PATH', RastyConfig::getInstance()->getWebPath());
		
		$this->componentPath = $map->getComponentWebPath( $this->getType() );
		$xtpl->assign('COMPONENT_PATH',   $this->componentPath);
		
		return $xtpl;
		
	}
	
	public abstract  function getType();
	
	
	public function getId(){ return $this->id; }
	public function setId($id){ $this->id = $id; }
			
	public function getConfig(){ return $this->config; }
	public function setConfig($config){ $this->config = $config; }
			
	public function getTemplateLocation(){ return $this->templateLocation; }
	public function setTemplateLocation($template){ $this->templateLocation = $template; }
	
	public function getComponents(){ return $this->components; }
	public function setComponents($components){ $this->components = $components; }
	
	public function addComponent(RastyComponent  $component){
		$this->components[$component->getId()] = $component;
	}
	
	public function getComponentById( $id  ){
		if(array_key_exists($id, $this->components))
		return $this->components[$id];
	}
	
		
	public function localize($keyMessage){
		return Locale::localize( $keyMessage );
	}
	
	public function getWebPath(){
		
		return RastyConfig::getInstance()->getWebPath();
	}
}
?>