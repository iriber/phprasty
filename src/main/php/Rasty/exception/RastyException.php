<?php

namespace Rasty\exception;
/**
 * Excepción genérica.
 * 
 * @author bernardo
 * @since 14-03-2010
 */
use Rasty\i18n\Locale;

class RastyException extends \Exception{
	
	public function RastyException($msg="rasty.exception.msg", $cod=0){
		$error=1;
		$msg = Locale::localize( $msg );
		
		parent::__construct($msg, $cod);
	}
	
}
