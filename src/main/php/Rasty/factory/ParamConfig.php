<?php
namespace Rasty\factory;
/**
 * Archivo de configuraci�n con la informaci�n necesaria
 * para la instanciaci�n de un par�metro.
 * 
 * @author bernardo
 *
 */
class ParamConfig{

	private $name;
	private $value;

	const PARAM_CONFIG_MSG = "msg";
	const PARAM_CONFIG_STR = "str";
	const PARAM_CONFIG_GET = "get";
	const PARAM_CONFIG_POST = "post";
	const PARAM_CONFIG_SESSION = "session";
	const PARAM_CONFIG_BEAN = "bean";
	const PARAM_CONFIG_BOOL = "boolean";
	const PARAM_CONFIG_INT = "integer";
	
	public function ParamConfig($name="", $value=""){
		$this->setName( $name );
		$this->setValue( $value );
	}
	
	public function setName($value){ $this->name = $value; }
	public function getName(){ return $this->name; }
	
	public function setValue($value){ $this->value = $value; }
	public function getValue(){ return $this->value; }
	
	
	/**
	 * recibe un xml de la forma:
	 *   	<param name='ccc' value='1'/>
	 *  
	 * y construye un ParamConfig.
	 * 
	 * @param unknown_type $xml
	 */
	public static function build( $xml ){
		
		//se instancia un nuevo param.
		$paramConfig = new ParamConfig();
		
		/*par�metro*/
		$param_attributes = array();
		foreach ($xml->attributes() as $key=>$value) {
			$param_attributes[$key] = $value . '';	
		}

		$paramConfig->setName( $param_attributes['name'] );
		$paramConfig->setValue( $param_attributes['value'] );
		
		return $paramConfig;
	}
		
}

?>