<?php

namespace Rasty\components;

use Rasty\conf\RastyConfig;

use Rasty\app\RastyMapHelper;

use Rasty\utils\Logger;

use Rasty\factory\ComponentConfig;
use Rasty\utils\XTemplate;
use Rasty\factory\ComponentFactory;
use Rasty\actions\Forward;

abstract class RastyPage extends AbstractComponent{

	protected $layoutType = "RastyHeaderFooterLayout";

	/**
	 * @var Forward
	 */
	protected $forward;
	
	public abstract function getTitle();
	
	public function getLayout(){

		$config = new ComponentConfig();
		$config->setType($this->getLayoutType());
		
		$oLayout = ComponentFactory::buildByType( $config, $this );
		
		$oLayout->setPage( $this );
		
		return $oLayout;
	}

	/**
	 * retorna el error que viene por forward.
	 * Si la página trata el error y no quiere que el layout lo muestre,
	 * deberá redefinir para retornar vacío.
	 */
	public function getMsgError(){
		return $this->getForward()->getError();
	}
	
	
	public function getContent(){
		
		//contenido del componente..
				
		$xtpl = $this->getXTemplate(  );
		
		$xtpl->assign('WEB_PATH', RastyConfig::getInstance()->getWebPath());
		
		$this->parseXTemplate($xtpl);
		
		$xtpl->parse("main");
		
		$content = $xtpl->text("main");
		
		return $this->getObserverJs().$content ;
	}
		
	protected function getRootPageTemplate(){
		
		return RastyConfig::getInstance()->getAppPath();
	}
	
	public function getXTemplate(){
		//debe renderizarse el componente y los componentes que contiente.
		$file_template = $this->getRootPageTemplate() .  $this->getTemplateLocation();
		
		if( !file_exists( $file_template )){
			
			/*
			$path = $_GET['path'] ;
			//dado el path buscamos el template.
			$file_template = $path . "." . TEMPLATE_EXTENSION;
			*/
			//buscamos dada la ubicaci�n del .page.
			$map = new RastyMapHelper();
			$file_template = $map->getPageAppPath( $this->getType() ). "/"  .  $this->getTemplateLocation() ;
			
		}
		
		//Logger::log( " File Template $file_template <br />");
		
		$xtpl = new XTemplate( $file_template );
		return $xtpl;
	}
	

	public function setLayoutType($layoutType)
	{
	    $this->layoutType = $layoutType;
	}

	public function getLayoutType()
	{
	    return $this->layoutType;
	}
	
	protected function parseXTemplate(XTemplate $xtpl){
		
	}

	public function getForward()
	{
	    return $this->forward;
	}

	public function setForward($forward)
	{
	    $this->forward = $forward;
	}
	
	public function isSecure(){
		return true;
	}
	
	/**
	 * retorna el javascript necesario para la implementación
	 * del patrón observer.
	 */
	protected function getObserverJs(){
		$id = get_class($this);
		$js = "<script>";
		//$js .= "$(document).ready(function(){";
		$js .= "if( !(typeof(window['subject']) != \"undefined\") )";
		$js .= "subject = new Subject(\"$id\")";
		//$js .= "});";
		$js .= "</script>";
		return $js;
		
	}
}
?>