<?php

namespace Rasty\factory;

use Rasty\cache\RastyCache;

/**
 * Archivo de configuraci�n que describe como est� compuesto
 * un componente.
 * 
 * @author bernardo
 *
 */
class ComponentDescriptor{

	private $specificationClass;
	private $templateLocation;
	private $components;
	private $params;
	
	public function __construct($class="", $template="", $components=array(), $params=array()){
		$this->setSpecificationClass( $class );
		$this->setTemplateLocation( $template );
		$this->setComponents( $components );
		$this->setParams( $params );
	}
	
	public function setParams($value){ $this->params = $value; }
	public function getParams(){ return $this->params; }
	
	public function addParamConfig( ParamConfig $param ){
		$this->params[] = $param;
	}
	
	public function setSpecificationClass($value){ $this->specificationClass = $value; }
	public function getSpecificationClass(){ return $this->specificationClass; }
	
	public function setTemplateLocation($value){ $this->templateLocation = $value; }
	public function getTemplateLocation(){ return $this->templateLocation; }
	
	public function setComponents($value){ $this->components = $value; }
	public function getComponents(){ return $this->components; }
	
	public function addComponentConfig( ComponentConfig $component ){
		$this->components[] = $component;
	}
	
	/**
	 * recibe un xml de la forma:
	 *   <composite specificationClass='' templateLocation=''>
	 *   	<component id='xx' type='yyy' >
	 *   		<param name='ccc' value='1'/>
	 *     		 ...
	 *      	<param name='nnn' value='3'/> 
	 *  	</component>
	 *  	...
	 *  	m�s componetes....
	 *   </composite>
	 *  
	 * y construye un ComponentDescriptor.
	 * 
	 * @param unknown_type $xml
	 */
	public static function build( $xml ){
		
		//se instancia un nuevo descriptor de componente.
		$descriptor = new ComponentDescriptor();
		
		/*atributos del componente*/
		$attributes = array();
		foreach ($xml->attributes() as $key=>$value) {
			$attributes[$key] = $value . '';	
		}
		
		$descriptor->setSpecificationClass( $attributes['specificationClass'] );
		
		if(array_key_exists('templateLocation', $attributes))
			$descriptor->setTemplateLocation( $attributes['templateLocation'] );
		else{
			$descriptor->setTemplateLocation( "" );
		}
		
		/*par�metros del componente*/
		foreach ($xml->param as $param) {
			$descriptor->addParamConfig( ParamConfig::build( $param ));
		}
		
		/*componentes del componente*/
		foreach ($xml->component as $component) {
			$descriptor->addComponentConfig( ComponentConfig::build( $component ));
		}
		
		return $descriptor;
	}

	
	public static function buildFromFile( $xmlPath ){
		
		//chequeamos si está en caché
		$cache = RastyCache::getInstance();
		$cacheKey = "RastyComponentDescriptor_$xmlPath";
		if($cache->contains( $cacheKey ) ){

			$descriptor = $cache->fetch($cacheKey);
	
		}else{
	
			//cargamos el descriptor del componente
			$xml = simplexml_load_file( $xmlPath );
			//construimos el componente contenido.
			$descriptor = self::build($xml);
				
			$cache->save($cacheKey, $descriptor);
		}

		
		return $descriptor;
		
	}
	
}

?>