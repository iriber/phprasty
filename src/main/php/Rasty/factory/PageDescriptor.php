<?php
namespace Rasty\factory;
/**
 * Archivo de configuraci�n que describe c�mo est� compuesta
 * una p�gina.
 * 
 * @author bernardo
 *
 */
class PageDescriptor{

	private $specificationClass;
	private $templateLocation;
	private $components;
	
	public function PageDescriptor($class="", $template="", $components=array()){
		$this->setSpecificationClass( $class );
		$this->setTemplateLocation( $template );
		$this->setComponents( $components );
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
	 * y construye un PageDescriptor.
	 * 
	 * @param unknown_type $xml
	 */
	public static function build( $xml ){
		
		//se instancia un nuevo composite.
		$pageDescriptor = new PageDescriptor();
		
		/*atributos del page*/
		$page_attributes = array();
		foreach ($xml->attributes() as $key=>$value) {
			$page_attributes[$key] = $value . '';	
		}
		
		$pageDescriptor->setSpecificationClass( $page_attributes['specificationClass'] );
		$pageDescriptor->setTemplateLocation( $page_attributes['templateLocation'] );
		
		/*componentes del page*/
		foreach ($xml->component as $component) {
			$pageDescriptor->addComponentConfig( ComponentConfig::build( $component ));
		}
		
		return $pageDescriptor;
	}	
}

?>