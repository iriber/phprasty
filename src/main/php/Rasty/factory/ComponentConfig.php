<?php
namespace Rasty\factory;

/**
 * Archivo de configuraci�n con la informaci�n necesaria
 * para la instanciaci�n de un componente.
 * 
 * @author bernardo
 *
 */
class ComponentConfig{

	private $id;
	private $type;
	private $params;
	private $instance;
	
	public function __construct($id="", $type="", $params=array(), $instance=""){
		$this->setId( $id );
		$this->setType( $type );
		$this->setParams( $params );
		$this->setInstance($instance);
	}
		
	public function setId($value){ $this->id = $value; }
	public function getId(){ return $this->id; }
	
	public function setType($value){ $this->type = $value; }
	public function getType(){ return $this->type; }
	
	public function setParams($value){ $this->params = $value; }
	public function getParams(){ return $this->params; }
	
	public function addParamConfig( ParamConfig $param ){
		$this->params[] = $param;
	}
	
	/**
	 * recibe un xml de la forma:
	 *   <component id='xx' type='yyy' >
	 *   	<param name='ccc' value='1'/>
	 *      ...
	 *      <param name='nnn' value='3'/> 
	 *  </component>
	 *  
	 * y construye un ComponentConfig.
	 * 
	 * @param unknown_type $xml
	 */
	public static function build( $xml ){
		
		//se instancia un nuevo componente.
		$componentConfig = new ComponentConfig();
		
		/*atributos del componente*/
		$component_attributes = array();
		foreach ($xml->attributes() as $key=>$value) {
			$component_attributes[$key] = $value . '';	
		}
		
		$componentConfig->setId( $component_attributes['id'] );
		
		if( array_key_exists('type', $component_attributes) )
			$componentConfig->setType( $component_attributes['type'] );
		
		if( array_key_exists('instance', $component_attributes) )
			$componentConfig->setInstance( $component_attributes['instance'] );
		
			
		/*par�metros del componente*/
		foreach ($xml->param as $param) {
			$componentConfig->addParamConfig( ParamConfig::build( $param ));
		}
		
		return $componentConfig;
	}
	

	public function getInstance()
	{
	    return $this->instance;
	}

	public function setInstance($instance)
	{
	    $this->instance = $instance;
	}
}

?>