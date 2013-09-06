<?php

namespace Rasty\exception;
/**
 * Excepción genérica para duplicados.
 * 
 * @author bernardo
 * @since 21-08-2013
 */
use Rasty\i18n\Locale;

class RastyDuplicatedException extends RastyException{

	private $oid;
	
	public function __construct($msg="rasty.duplicated.exception.msg", $cod=0){
		$error=1;
		$msg = Locale::localize($msg);
		
		parent::__construct($msg, $cod);
		
	}
	

	public function getOid()
	{
	    return $this->oid;
	}

	public function setOid($oid)
	{
	    $this->oid = $oid;
	}
}
