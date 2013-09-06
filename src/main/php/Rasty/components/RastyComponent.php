<?php

namespace Rasty\components;

use Rasty\utils\XTemplate;

abstract class RastyComponent extends AbstractComponent{

	public function getContent(){
		
		$xtpl = $this->getXTemplate();
		
		$xtpl->assign('WEB_PATH', $this->getWebPath());
		
		$this->parseXTemplate($xtpl);
		
		$xtpl->parse("main");
		$content = $xtpl->text("main");

		return  $this->enhanceObserver( $content );
	}
	
	protected function parseXTemplate(XTemplate $xtpl){
		
	}

	
	protected function enhanceObserver($content){
		
		$this->initObserverEventType();
		
		$result = $this->getObserverJs() . $content . "</span>"; 
		
		return $result;
	}
	/**
	 * retorna el javascript necesario para la implementación
	 * del patrón observer.
	 */
	protected function getObserverJs(){
		$componentId = $this->getId();
		$id = $this->getType() . $componentId;
		
		$fCallback = $id ."_update";


		$js = "<span id=\"$id\">";

		
		//escribimos solo si tiene algún tipo de evento definido a escuchar.
		
		if(count($this->eventTypes)>0){
			
			$strEventTypes =  "'" . implode("','" , $this->eventTypes) . "'";
			
			$url = $this->getWebPath() . $this->getType() . ".rasty";
			
			$js .= " 
			<script>
				function $fCallback(event){
	
					var interestedEventTypes = new Array($strEventTypes);
						
					if( contains( interestedEventTypes, event.type) ){
					
						var strParams = \"\";
						for ( var key in event.data) {
							strParams = strParams + key + \"=\" + encodeURI(event.data[key]) + \"&\";
						} 
						
						$.ajax({
				  			url: \"$url?componentId={$componentId}&\" + strParams,
				  			type: \"GET\",
				  			cache: false,
				  			success: function(content){
								$( \"#$id\" ).html(content);
				  			}
						});
					}
				}	
					
				$(document).ready(function(){
					observer$id = new Observer(\"$id\", $fCallback);
					subject.addObserver(observer$id);
				});
			</script>
			";	
		}
			
		
		
		return $js;
		
	}
	
	/**
	 * array con los tipos de eventos en los cuales
	 * está interesado el componente.
	 * @var array
	 */
	private $eventTypes = array();
	
	/**
	 * cada componente definie los tipos de eventos 
	 * que desea escuchar. Por ejemplo, si desea escuchar 
	 * cambios sobre "Cliente" debería agregarlo a la lista,
	 * de lo contrario, no será notificado.
	 * 
	 */
	protected function initObserverEventType(){
		
		
	}
	
	/**
	 * se agrega un tipo de evento del cual quiere
	 * ser notificado ante un cambio.
	 * @param $type tipo de evento (model class)
	 */
	protected function addEventType( $type ){
		
		$this->eventTypes[] = $type;
		
	}
	
	
}
?>