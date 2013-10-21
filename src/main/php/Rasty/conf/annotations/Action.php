<?php
namespace Rasty\conf\annotations;

use Addendum\Annotation;

/**
 * Annotation para la configuración de un action
 * 
 * @author bernardo
 * @since 01/10/2013
 */
class Action extends Annotation {

	public $name;
	
	public $url;
	
	public $className;
	
}