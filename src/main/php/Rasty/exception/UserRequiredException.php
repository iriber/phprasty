<?php

namespace Rasty\exception;
/**
 * Excepción para usuario requerido.
 * 
 * @author bernardo
 * @since 06-08-2013
 */
use Rasty\i18n\Locale;

class UserRequiredException extends RastyException{
	
	public function __construct($msg=""){

		if(empty($msg))
			$msg = Locale::localize("user.required.exception.msg");
		
		parent::__construct($msg);
	}
	
}
