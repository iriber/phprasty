<?php
namespace Rasty\i18n;

/**
 * @author bernardo
 */
use Rasty\conf\RastyConfig;

class Locale{

	/**
	 * la manera de buscar mensajes ser�a a partir del path actual hacia atr�s.
	 * 
	 * p.ej:  /pages/prueba/listar buscar� en:
	 * 
	 * 	       1- /pages/prueba/listar.lang_es
	 * 	       2- /pages/prueba/prueba.lang_es
	 * 	       3- /pages/pages.lang_es
	 * 	       4- /languages/application.lang_es
	 * 
	 * @param unknown_type $msg
	 * @param unknown_type $component
	 * @return Ambigous <mixed, NULL>
	 */
	static function localize($msg, $component=null) {

		/*
        $path = $_GET['path'] ;
		$lang_file = RASTY_APP_PATH . "/" . $path . ".lang_". LANGUAGE;
		
		$value = Locale::getMsg( $lang_file, $msg );
        */
        if( empty($value) ){
			
        	//TODO pasos intermedios.
        	$setup = RastyConfig::getInstance();
        	$lang_file = $setup->getAppPath() . '/languages/application.lang_' . $setup->getLanguage();
			$value = Locale::getMsg( $lang_file, $msg );

			
        }
        
        if( empty($value) ){
        	$value = "/$msg/";
        } 

        
        
        return $value;
	}
	
	static function getMsg($lang_file, $key){
		$value = null;
		if (file_exists( $lang_file) ){
			$lang_file_content = file_get_contents($lang_file);
	        $translations = json_decode($lang_file_content, true);
	        
	        if( array_key_exists($key, $translations) )
	        	$value = $translations[$key];
		}
        return $value;
        
	}
	
}

?>