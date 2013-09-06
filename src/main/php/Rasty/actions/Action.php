<?php

namespace Rasty\actions;

use Rasty\exception\RastyException;
use Rasty\actions\Forward;

/**
 * Representa una acción a ejecutarse en rasty.
 * 
 * La idea del action es que se ejecute y retorne
 * a una página.
 * 
 * @author bernardo
 * @since 03/08/2013
 */

abstract class Action{

	/**
	 * se ejecuta el action.
	 * @throws RastyException
	 * @return Forward
	 */
	public abstract function execute();

	
	/**
	 * se inicializa el action.
	 * @throws RastyException
	 *
	
	public abstract function init();
	
	/**
	 * finaliza el action.
	 * @throws RastyException
	 *
	public abstract function finalize();
	*/
}

?>