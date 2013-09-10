<?php

namespace Rasty\conf;

/**
 * Configuración para Rasty

 * @author bernardo
 * @since 05/09/2013
 */
class RastyConfig {

	/**
	 * singleton instance
	 * @var RastyConfig
	 */
	private static $instance;
	
	/**
	 * nombre de la aplicación.
	 * @var string
	 */
	private $appName;
	
	/**
	 * home de la app en el filesystem.
	 * @var string
	 */
	private $appHome;
	
	/**
	 * web path de la app
	 * @var string
	 */
	private $webPath;
	
	/**
	 * language para los mensajes.
	 * @var string
	 */
	private $language = "es";

	//extensiones de los templates.
	private $templateExtension = 'htm' ;

	/**
	 * url para websocket
	 * @var string
	 */
	private $websocketUrl ="";
	
	/**
	 * port para websocket
	 * @var string
	 */
	private $websocketPort ="8084";
	
	const RASTY_REQUEST_TYPE_PAGE = 1;
	const RASTY_REQUEST_TYPE_JSON = 2;
	const RASTY_REQUEST_TYPE_PDF = 3;
	const RASTY_REQUEST_TYPE_COMPONENT = 4 ;
	const RASTY_REQUEST_TYPE_ACTION = 5;

	public static function getInstance(){
		if (  !self::$instance instanceof self ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
    
    /**
     * inicializamos phprasty
     */
    public function initialize( $appHome, $appName ){
    	
    	$this->setAppHome( $appHome);
    	
    	$this->setAppName( $appName );
    	
    	$this->setWebPath( 'http://' . getenv ('HTTP_HOST') . "/" . $appName. "/" );

		$this->setLanguage("es");
		
		$this->setTemplateExtension("htm");
		
    }

	public static function setInstance($instance)
	{
	    self::$instance = $instance;
	}

	
	public function getAppPath()
	{
	    return $this->appHome . "/" . $this->appName  . "/";
	}
	
	public function getAppName()
	{
	    return $this->appName;
	}

	public function setAppName($appName)
	{
	    $this->appName = $appName;
	}

	public function getAppHome()
	{
	    return $this->appHome;
	}

	public function setAppHome($appHome)
	{
	    $this->appHome = $appHome;
	}

	public function getWebPath()
	{
	    return $this->webPath;
	}

	public function setWebPath($webPath)
	{
	    $this->webPath = $webPath;
	}

	public function getLanguage()
	{
	    return $this->language;
	}

	public function setLanguage($language)
	{
	    $this->language = $language;
	}

	public function getTemplateExtension()
	{
	    return $this->templateExtension;
	}

	public function setTemplateExtension($templateExtension)
	{
	    $this->templateExtension = $templateExtension;
	}

	public function getWebsocketUrl()
	{
	    return $this->websocketUrl;
	}

	public function setWebsocketUrl($websocketUrl)
	{
	    $this->websocketUrl = $websocketUrl;
	}

	public function getWebsocketPort()
	{
	    return $this->websocketPort;
	}

	public function setWebsocketPort($websocketPort)
	{
	    $this->websocketPort = $websocketPort;
	}
}