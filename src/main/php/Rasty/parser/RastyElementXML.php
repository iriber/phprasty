<?php
namespace Rasty\parser;
/**
 *  
 * @author bernardo
 * @since 03-03-2010
 */
class RastyElementXML{

	private $text;
	private $startPos;
	private $endPos;
	private $attributes;
	private $id;
	
	public function __construct($text, $start, $end, $attributes){
		$this->startPos = $start;
		$this->text = $text;
		$this->endPos = $end;
		$this->attributes = $attributes;
		$this->id = $attributes['id'];
	}
			
	public function getText(){ return $this->text; }
	public function getStartPos(){ return $this->startPos; }
	public function getEndPos(){ return $this->endPos; }
	public function getAttributes(){ return $this->attributes; }
	public function getId(){ return $this->id; }
	
	public function getAttribute($name){ return $this->attributes[$name]; }

}