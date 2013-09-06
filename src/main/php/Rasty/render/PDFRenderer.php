<?php

namespace Rasty\render;

use Rasty\components\AbstractComponent;

class PDFRenderer implements IRenderer{

	private $filename='';

	public function PDFRenderer($filename='filename'){
		$this->filename = $filename;
	}
	
	public function render(AbstractComponent $component){
		
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		header ( "Content-type: application/pdf" );
		header("Content-Disposition: attachment; filename=". $this->filename .".pdf");
		
		echo $component->render();
	}
}
?>