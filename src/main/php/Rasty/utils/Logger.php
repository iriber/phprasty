<?php

namespace Rasty\utils;

/**
 * Para manejar el log de la app
 * 
 * 
 * @author Bernardo Iribarne (bernardo.iribarne@codnet.com.ar)
 * @since 05-08-2013
 */



class Logger{
    
	const LEVEL_DEBUG = 1;
	const LEVEL_INFO = 2;
	const LEVEL_ERROR = 3;
	
	const LEVEL_WARNING = 4;
	
	public static function configure( $xmlfilePath ){
		\Logger::configure( $xmlfilePath );
	}
	
	
	public static function logObject( $object, $clazz = __CLASS__, $level = self::LEVEL_INFO ){
		ob_start();	
		echo "<pre>";
   		var_dump($object);
	   	echo "</pre>";
	   	
	   	$msg = ob_get_clean();
	   	
	   	self::log($msg, $clazz, $level);
	}
	
	public static function log( $msg, $clazz = __CLASS__, $level = self::LEVEL_INFO ){
		
		switch ($level) {
			case self::LEVEL_DEBUG: 
				\Logger::getLogger($clazz)->debug( $msg );
			;
			break;
			case self::LEVEL_INFO: 
				\Logger::getLogger($clazz)->info( $msg );
			;
			break;
			case self::LEVEL_ERROR: 
				\Logger::getLogger($clazz)->error( $msg );
			;
			break;
			case self::LEVEL_WARNING: 
				\Logger::getLogger($clazz)->warn( $msg );
			;
			break;
			
			default:
				\Logger::getLogger($clazz)->info( $msg );
			break;
		}
		
	}
}
	