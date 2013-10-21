<?php
namespace Rasty\conf\annotations;

use Addendum\Annotation;

/**
 * Annotation para la configuración de un componente
 * 
 * @author bernardo
 * @since 01/10/2013
 */
class Component extends Annotation {

	public $name;
	
	public $location;
	
}