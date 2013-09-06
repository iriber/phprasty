<?php
namespace Rasty\app;

/**
 * Punto de entrada de la aplicaci�n para archivos pdf.
 * 
 *   
 * @author bernardo
 * @since 03-08-2011
 */
use Rasty\render\PDFRenderer;

class ApplicationPDF extends Application{

	

	protected function getRenderer(){
		//TODO filename.
		return new PDFRenderer('test');
	}
	
}