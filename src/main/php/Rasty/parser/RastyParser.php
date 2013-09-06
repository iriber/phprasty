<?php
namespace Rasty\parser;
/**
 *  
 * @author bernardo
 * @since 03-03-2010
 */
class RastyParser{

	/**
	 * dado un html toma todos los tags "rasty", y reemplaza
	 * los tags por el contenido correspondiente.
	 * 
	 * @param unknown_type $html
	 */
	public static function parse($html, $components, $parent){
		
		/* buscamos todos los tags rasty */
		
		$start_rasty = strpos($html,  "<rasty");
		
		while($start_rasty){
		
			//buscamos el tag de cierre.
			$end_rasty = strpos($html,  "/>", $start_rasty) + 2;	
			
			//leemos el tag entero.
			$tag_rasty = substr( $html, $start_rasty, ($end_rasty-$start_rasty) );
		
			$xml = simplexml_load_string( $tag_rasty );
			/* se cargan las acciones. */
			$attributes = array();
			foreach ($xml->attributes() as $key=>$value) {
				$attributes[$key] = $value . '';	
			}
			
			//cargamos la info en un objeto.
			$rastyElementXML = new RastyElementXML($tag_rasty, $start_rasty, $end_rasty, $attributes);
			
			//obtenemos el contenido del componente.			
			$content = RastyParser::getContent( $rastyElementXML , $components, $parent ); 	

			//reemplazamos el tag por el contenido correspondiente.
			$html = str_replace( $tag_rasty, $content, $html);
			
			
			//$this->components[] = $rastyElementXML;
			
			//seguimos buscando.
			if( strlen( $html) >= ($start_rasty+7))
				$start_rasty = strpos($html,  "<rasty", $start_rasty+7);
			else
				$start_rasty = false; 
		}
		
		
		return $html;
	}		

	public static function getContent( $rastyElementXML, $components, $parent){
		//dado un componente, retorna el contenido asociado.
		
		$id = $rastyElementXML->getId();
		
		
		if( !empty($id) && $components[ $id ]!=null )
			$content =  $components[ $id ]->render();
		else
			$content = "<font color='red'><u>Error de parseo:</u> El componente <b> $id </b> no se encuentra en el descriptor de " . get_class( $parent ) . "</font>";
			
		return $content;		
	}
}