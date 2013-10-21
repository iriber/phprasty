<?php
namespace Rasty\security\annotations;

use Addendum\Annotation;

/**
 * Annotation para definir la seguridad sobre los componentes
 * 
 * @author bernardo
 * @since 01/10/2013
 */
class Secured extends Annotation {

	public $permission;
	
}