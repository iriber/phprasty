<?php

namespace Rasty\factory;

use Rasty\cache\RastyCache;

/**
 * @author bernardo
 */
use Rasty\utils\Logger;

use Rasty\utils\RastyUtils;

use Rasty\app\RastyMapHelper;

use Rasty\i18n\Locale;

use Rasty\utils\ReflectionUtils;

class ComponentFactory{

	
	public function ComponentFactory(){
	}
	
	/**
	 * Construye una componente
	 *
	 */
	public static function build( $xml, $config=null, $oParentComponent=null ){
	
// 		//chequeamos si está en caché
// 		$cache = RastyCache::getInstance();
		
// 		$cacheKey = "RastyComponent_$xml";
		
		
// 		if($cache->contains($cacheKey) ){

// 			$component = $cache->fetch($cacheKey);
		
// 			//TODO setear parámetros al componente.
//  			self::mergeComponentParams($component, $component->getDescriptor(), $config, $oParentComponent );
			
// 		}else{
	
// 			$component = self::myBuild($xml, $config, $oParentComponent);
	
// 			$cache->save($cacheKey, $component);
// 		}
	
		$component = self::myBuild($xml, $config, $oParentComponent);
		
		return $component;
	}
	
	
		
	private static function myBuild( $xml, $config=null, $oParentComponent=null ){
		
		//construimos el objeto a descriptor a partir del xml.
		$descriptor = ComponentDescriptor::build( $xml );
		
		//obtenemos la clase de especificaci�n del componente..
		$className = $descriptor->getSpecificationClass();
		
		//instanciamos el componente por reflection.
		$oClass = new \ReflectionClass($className);
		$oComponent = $oClass->newInstance();
		
		//inicializamos el componente con el objeto de configuraci�n.
		$oComponent->setDescriptor($descriptor);
		$oComponent->setTemplateLocation( $descriptor->getTemplateLocation() );

		//seteamos los parámetros del componente..
		//tomamos los del descriptor y pisamos los que se redefinan en el config del parent.
		
		self::mergeComponentParams($oComponent, $descriptor, $config, $oParentComponent );
		
		
		//vamos a construir cada uno de los componentes que contiene el componente.
		
		$map = new RastyMapHelper();
		
		//primero buscamos en el descriptor (.rasty)
		$components = $descriptor->getComponents();
		if( $components != null )
		foreach ($descriptor->getComponents() as $componentConfig) {

			//construimos el subcomponente.
			$oSubcomponent = ComponentFactory::buildByType( $componentConfig, $oComponent  );
			
			//agregamos el componente al componente.
			$oComponent->addComponent( $oSubcomponent );
		}
		
		//TODO buscamos componentes definidos directamente en el template.
		
		return $oComponent;
	}
	
	/**
	 * Al componente $componentTo se le setea el parámetro $paramName. El valor
	 * que se setea es $paramValue tomado del componente $componentFrom.
	 * Sería algo así:
	 * 
	 *  $componentTo.setParamName( $componentFrom->getParamValue() );
	 *  
	 *  
	 * @param $paramName nombre del par�metro a setear
	 * @param $paramValue valor descriptivo del par�metro
	 * @param $componentFrom componente que inicializa el par�metro
	 * @param $componentTo componente al cual se le setea el par�metro.
	 */
	public static function parseParam( $paramName, $paramValue, $componentFrom, $componentTo){
		/*
		 * hay que chequear cómo está armado el $paramValue:
		 * 		
		 *  1- "msg: un_valor" : significa que hay que ir a buscar el contenido a los properties de localización (idiomas).
		 *  2: "1": si es un número se deja ese valor.
		 *  3: "unMetodo": significa es el un método de $componentFrom
		 *  4: "UnaClase.unMetodo": significa que unMetodo es un método de la clase UnaClase.
		 *  5: "str: algo por aqui": directamente el string ingresado.
		 *  6: "get: nombre": $_GET["nombre"]
		 *  7: "post: nombre": $_POST["nombre"]
		 *  8: "session: nombre": $_SESSION["nombre"]
		 *  9: "bool: true|false"
		 */
		
		//formamos el seter
		//$paramName =   'set' . ucfirst( $paramName );
		
		

		if( self::isParamFrom(ParamConfig::PARAM_CONFIG_MSG, $paramValue)){//caso 1
			
			//buscar el valor en un archivo de mensajes.
			$value =  self::getParamFrom(ParamConfig::PARAM_CONFIG_MSG, $paramValue);
			
			//puede ser un arreglo -> msg:{nombre, apellido}.
			//en este caso retorno un array con las traducciones de nombre y de apellido.
			if( substr( $value, 0,1) == "{" ){
				$value = substr( $value, 1, strlen( $value )-2 );
				$values = explode( ", ", $value );
				
				$value = array();
				foreach ($values as $item) {
					$value[] = Locale::localize( $item );
				}
				
			}else{
				$value =  Locale::localize( $value );
			}		

		}elseif( self::isParamFrom(ParamConfig::PARAM_CONFIG_STR, $paramValue)){//caso 5
			
			$value =  self::getParamFrom(ParamConfig::PARAM_CONFIG_STR, $paramValue);
			
			//puede ser un arreglo -> msg:{nombre, apellido}.
			//en este caso retorno un array con las traducciones de nombre y de apellido.
			if( substr( $value, 0,1) == "{" ){
				$value = substr( $value, 1, strlen( $value )-2 );
				$values = explode( ",", $value );
				
				$value = array();
				foreach ($values as $item) {
					$value[] = trim( $item );
				}
				
			}
				
		}elseif( self::isParamFrom(ParamConfig::PARAM_CONFIG_GET, $paramValue)){//caso 6
			
			$value =  self::getParamFrom(ParamConfig::PARAM_CONFIG_GET, $paramValue);
			
			$value = RastyUtils::getParamGET( $value );
				
		}elseif( self::isParamFrom(ParamConfig::PARAM_CONFIG_POST, $paramValue)){//caso 7
			
			$value =  self::getParamFrom(ParamConfig::PARAM_CONFIG_POST, $paramValue);
			
			$value = RastyUtils::getParamPOST( $value );
				
		}elseif( self::isParamFrom(ParamConfig::PARAM_CONFIG_SESSION, $paramValue)){//caso 8
			
			$value =  self::getParamFrom(ParamConfig::PARAM_CONFIG_SESSION, $paramValue);
			
			$value = RastyUtils::getParamSESSION( $value );
				
		}elseif( self::isParamFrom(ParamConfig::PARAM_CONFIG_BEAN, $paramValue)){//caso 9
			
			$value =  self::getParamFrom(ParamConfig::PARAM_CONFIG_BEAN, $paramValue);
			
			//instanciamos por reflection.
			$value = ReflectionUtils::newInstance($value);
				
		}elseif( self::isParamFrom(ParamConfig::PARAM_CONFIG_BOOL, $paramValue)){//caso 10
			
			$value = strtoupper( self::getParamFrom(ParamConfig::PARAM_CONFIG_BOOL, $paramValue) );
			$value = $value == "TRUE";
			
		}else{ //caso 3

			/*
			//formamos el getter.
			$paramValue =   'get' . ucfirst( $paramValue );
			
			$reflectionMethodFrom = new \ReflectionMethod( get_class( $componentFrom ) , $paramValue);
			$value = $reflectionMethodFrom->invoke( $componentFrom );
			*/
			
			$value = ReflectionUtils::doGetter($componentFrom, $paramValue);
	 			
		}
		
		/*
		$reflectionMethodTo = new \ReflectionMethod( get_class( $componentTo ) , $paramName);
		$reflectionMethodTo->invoke( $componentTo , $value);
		*/
		ReflectionUtils::doSetter($componentTo, $paramName, $value);
	}
	
	public static function buildByType( ComponentConfig $componentConfig, $parent=null ){
		
		$id =  $componentConfig->getId() ;
		$type =  $componentConfig->getType();
		$instance =  $componentConfig->getInstance();
		
		//si es una instancia se la pedimos al parent
		if( !empty($instance) ){
			
			$oComponent = self::getValueFromDescriptor($instance, $parent);	
			
		}else{ //sino, construimos el componente dado su type.
			$map = new RastyMapHelper();
			$xmlPath = $map->getComponentDescriptor( $type );

			//Logger::logObject($map);
			
//			//cargamos el descriptor del componente.
// 			$xml = simplexml_load_file( $xmlPath );
// 			//construimos el componente contenido.
// 			$oComponent = ComponentFactory::build( $xml, $componentConfig, $parent );

			//obtenemos el descriptor del componente.
			$descriptor = self::getDescriptor( $xmlPath );
			//construimos el componente contenido.
			$oComponent = ComponentFactory::buildByDescriptor($descriptor, $componentConfig, $parent );
					
		}
		
		$oComponent->setId( $id );
			
		return $oComponent;
	}
	
	public static function setParams( $params, $oComponent, $parent ){
		if($params!=null)
		//seteamos los par�metros del componente.		
		foreach ($params as $paramConfig) {
			$paramName = $paramConfig->getName() ;
			$paramValue =  $paramConfig->getValue();
			ComponentFactory::parseParam( $paramName, $paramValue, $parent, $oComponent);
		}
	}


	public static function isParamFrom( $type, $value ){
		
		return substr( $value, 0, strlen($type)) == $type ;
			
	}

	public static function getParamFrom( $type, $paramValue ){
		
		return trim( substr( $paramValue, strlen($type)+1, strlen($paramValue) ) ) ;
			
	}
	
	
	public static function getValueFromDescriptor($paramValue, $componentFrom=null){

		$value = null; 
		
		if( self::isParamFrom(ParamConfig::PARAM_CONFIG_MSG, $paramValue)){//caso 1
			
			//buscar el valor en un archivo de mensajes.
			$value =  self::getParamFrom(ParamConfig::PARAM_CONFIG_MSG, $paramValue);
			
			//puede ser un arreglo -> msg:{nombre, apellido}.
			//en este caso retorno un array con las traducciones de nombre y de apellido.
			if( substr( $value, 0,1) == "{" ){
				$value = substr( $value, 1, strlen( $value )-2 );
				$values = explode( ", ", $value );
				
				$value = array();
				foreach ($values as $item) {
					$value[] = Locale::localize( $item );
				}
				
			}else{
				$value =  Locale::localize( $value );
			}		

		}elseif( self::isParamFrom(ParamConfig::PARAM_CONFIG_STR, $paramValue)){//caso 5
			
			$value =  self::getParamFrom(ParamConfig::PARAM_CONFIG_STR, $paramValue);
			
			//puede ser un arreglo -> msg:{nombre, apellido}.
			//en este caso retorno un array con las traducciones de nombre y de apellido.
			if( substr( $value, 0,1) == "{" ){
				$value = substr( $value, 1, strlen( $value )-2 );
				$values = explode( ",", $value );
				
				$value = array();
				foreach ($values as $item) {
					$value[] = trim( $item );
				}
				
			}
				
		}elseif( self::isParamFrom(ParamConfig::PARAM_CONFIG_GET, $paramValue)){//caso 6
			
			$value =  self::getParamFrom(ParamConfig::PARAM_CONFIG_GET, $paramValue);
			
			$value = RastyUtils::getParamGET( $value );
				
		}elseif( self::isParamFrom(ParamConfig::PARAM_CONFIG_POST, $paramValue)){//caso 7
			
			$value =  self::getParamFrom(ParamConfig::PARAM_CONFIG_POST, $paramValue);
			
			$value = RastyUtils::getParamPOST( $value );
				
		}elseif( self::isParamFrom(ParamConfig::PARAM_CONFIG_SESSION, $paramValue)){//caso 8
			
			$value =  self::getParamFrom(ParamConfig::PARAM_CONFIG_SESSION, $paramValue);
			
			$value = RastyUtils::getParamSESSION( $value );
				
		}elseif( self::isParamFrom(ParamConfig::PARAM_CONFIG_BEAN, $paramValue)){//caso 9
			
			$value =  self::getParamFrom(ParamConfig::PARAM_CONFIG_BEAN, $paramValue);
			
			//instanciamos por reflection.
			$value = ReflectionUtils::newInstance($value);
				
		}elseif( self::isParamFrom(ParamConfig::PARAM_CONFIG_BOOL, $paramValue)){//caso 9
			
			$value = strtoupper( self::getParamFrom(ParamConfig::PARAM_CONFIG_BOOL, $paramValue) );
			$value = $value == "TRUE";
		
		}else{ //caso 3

			/*
			//formamos el getter.
			$paramValue =   'get' . ucfirst( $paramValue );
			
			$reflectionMethodFrom = new \ReflectionMethod( get_class( $componentFrom ) , $paramValue);
			$value = $reflectionMethodFrom->invoke( $componentFrom );
			*/
			
			$value = ReflectionUtils::doGetter($componentFrom, $paramValue);
	 			
		}
		
		return $value;
		
	}
	
	public static function mergeComponentParams($component, $descriptor, $config=null, $parent=null){
		
		/*
		$descriptorParams = $descriptor->getParams();	
		
		if( $config !=null ){
			//array_merge($params, $config->getParams());
			
			$configParams = $config->getParams();
			
			foreach ($descriptorParams as $descriptorParam) {
				foreach ($configParams as $configParam) {
					//$paramName = $paramConfig->getName() ;
					//$paramValue =  $paramConfig->getValue();				
					if( $configParam->getName() == $descriptorParam->getName() ){
						$descriptorParam->setValue( $configParam->getValue() );
					}
				}
				
			}
			
		}
		$params = $descriptorParams;
		self::setParams( $params, $component, $parent );	
		*/
		if( $config !=null ){
			ComponentFactory::setParams( $descriptor->getParams(), $component, $parent);
			ComponentFactory::setParams( $config->getParams(), $component, $parent);
		}else{
			
			ComponentFactory::setParams( $descriptor->getParams(), $component, $parent);
		}
	}
	

	/**
	 * esto lo creamos para utiilzar los descriptores almacenados en la caché.
	 * 
	 * @param unknown_type $descriptor
	 * @param unknown_type $config
	 * @param unknown_type $oParentComponent
	 * @return object
	 */
	public static function buildByDescriptor( $descriptor, $config=null, $oParentComponent=null ){
	
		//obtenemos la clase de especificaci�n del componente..
		$className = $descriptor->getSpecificationClass();
		
		//instanciamos el componente por reflection.
		$oClass = new \ReflectionClass($className);
		$oComponent = $oClass->newInstance();
		
		//inicializamos el componente con el objeto de configuraci�n.
		$oComponent->setDescriptor($descriptor);
		$oComponent->setTemplateLocation( $descriptor->getTemplateLocation() );

		//seteamos los parámetros del componente..
		//tomamos los del descriptor y pisamos los que se redefinan en el config del parent.
		
		self::mergeComponentParams($oComponent, $descriptor, $config, $oParentComponent );
		
		
		//vamos a construir cada uno de los componentes que contiene el componente.
		
		$map = new RastyMapHelper();
		
		//primero buscamos en el descriptor (.rasty)
		$components = $descriptor->getComponents();
		if( $components != null )
		foreach ($descriptor->getComponents() as $componentConfig) {

			//construimos el subcomponente.
			$oSubcomponent = ComponentFactory::buildByType( $componentConfig, $oComponent  );
			
			//agregamos el componente al componente.
			$oComponent->addComponent( $oSubcomponent );
		}
		
		//TODO buscamos componentes definidos directamente en el template.
		
		return $oComponent;
	}
	
	public static function getDescriptor( $xmlPath ){
		
		$descriptor = ComponentDescriptor::buildFromFile( $xmlPath );
		return $descriptor;
	}
	
}

?>