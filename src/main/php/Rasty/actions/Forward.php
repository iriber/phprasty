<?php
namespace Rasty\actions;

use Rasty\utils\LinkBuilder;

use Rasty\app\RastyMapHelper;

use Rasty\exception\RastyException;

/**
 * Representa un forward de una acción.
 * 
 * @author bernardo
 * @since 03/08/2013
 */

class Forward{
	
	//private $url;
	
	/**
	 * nombre de la página a la cual redireccionar.
	 * @var string
	 */
	private $pageName;

	/**
	 * parámetros para armar la url del forward.
	 * @var array
	 */
	private $params;
	
	public function __construct(){
		$this->params = array();
	}

	public function buildForwardUrl(){
		/*
		$params = array();
		
		if( count( $this->params ) > 0){
			foreach ($this->getParams() as $name => $value) {
				$params[] = "$name=" . urlencode($value);
			}
		}

		$strParams = "";
		if( count($params) > 0 ){
			$strParams = "?".  implode("&", $params ) ;
		}
		*/
			
		$forwardUrl =  LinkBuilder::getPageUrl( $this->pageName, $this->params );
		
		return  $forwardUrl;
	}
	
	public function addParam( $name, $value ){
		
		$this->params[$name] = $value;
	}

	public function addError( $value ){
		
		$this->addParam("error", $value);
	}
	
	
	public function getParams()
	{
	    return $this->params;
	}

	public function setParams($params)
	{
	    $this->params = $params;
	}

	public function getPageName()
	{
	    return $this->pageName;
	}

	public function setPageName($pageName)
	{
	    $this->pageName = $pageName;
	}
	
	public function hasError(){
		return array_key_exists("error", $this->params) && !empty($this->params["error"]);
	}
	
	public function getError(){
		return $this->params["error"];
	}
}

?>