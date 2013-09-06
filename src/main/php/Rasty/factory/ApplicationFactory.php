<?php

namespace Rasty\factory;
/**
 * Factory para Application.
 * 
 * Ser� construido de acuerdo al request type: pdf, xls, page (html), ajax (json or html) 
 *  
 * @author bernardo
 * @since 13/09/2011
 *
 */
use Rasty\conf\RastyConfig;

use Rasty\utils\ReflectionUtils;

class ApplicationFactory{

	public function ApplicationFactory(){
				
	}
		
	public static function build( $requestType ){

		$hash = array();
		$hash[ RastyConfig::RASTY_REQUEST_TYPE_PAGE ] = "\Rasty\app\Application";
		$hash[ RastyConfig::RASTY_REQUEST_TYPE_JSON ] = "\Rasty\app\ApplicationJson";
		$hash[ RastyConfig::RASTY_REQUEST_TYPE_PDF ] = "\Rasty\app\ApplicationPDF";
		$hash[ RastyConfig::RASTY_REQUEST_TYPE_COMPONENT ] = "\Rasty\app\ApplicationComponent";
		$hash[ RastyConfig::RASTY_REQUEST_TYPE_ACTION ] = "\Rasty\app\ApplicationAction";
		
		$oApplication = ReflectionUtils::newInstance(  $hash[$requestType]  );
		
		return $oApplication;
	}
	

}

?>