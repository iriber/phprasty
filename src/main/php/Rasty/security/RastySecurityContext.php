<?php

namespace Rasty\security;

use Rasty\components\RastyComponent;

use Rasty\exception\UserRequiredException;

use Rasty\components\RastyPage;
use Rasty\exception\RastyException;
use Rasty\i18n\Locale;

use Cose\Security\exception\InvalidPasswordException;
use Cose\Security\exception\UserNotFoundException;
use Cose\Security\service\SecurityContext;

use Cose\exception\ServiceException;

/**
 * Contexto de seguridad para la app.
 * 
 * @author bernardo
 * @since 06/08/2013
 * 
 */


class RastySecurityContext {
	
	static function getUser() {
		$securityContext =  SecurityContext::getInstance();
		return $securityContext->getUser();
	}
	
	/**
	 * determina si se puede consultar la página dada.
	 * @param RastyPage $page página a consultar.
	 * @return boolean
	 */
	static function authorize( RastyPage $page ) {

		if( !$page->isSecure() )
			return true;
		
		$user = self::getUser();
		
		if( $user == null )
			throw new UserRequiredException();

		//TODO ver cómo determinar que el usuario puede consultar la página.
		
	}

	/**
	 * determina si se puede consultar el component dado.
	 * @param RastyComponent $component componente a consultar.
	 * @return boolean
	 */
	static function authorizeComponent( RastyComponent $component ) {

		if( !$component->isSecure() )
			return true;
		
		$user = self::getUser();
		
		if( $user == null )
			throw new UserRequiredException();

		
	}
	/**
	 * se loguea el usuario en el contexto de seguridad
	 * @param $username nombre de usuario
	 * @param $password clave
	 * @throws RastyException
	 */
	static function login( $username, $password ){
		
		try {

			$securityContext =  SecurityContext::getInstance();
			$securityContext->login( $username, $password );
			
		} catch (UserNotFoundException $e) {
		
			throw new RastyException(Locale::localize("login.user_not_found"));
			
		} catch (InvalidPasswordException $e) {
		
			throw new RastyException(Locale::localize("login.invalid_password"));
			
		} catch (ServiceException $e) {
			
			throw new RastyException($e->getMessage());
		}		
		
	}

	/**
	 * se desloguea el usuario en el contexto de seguridad
	 * @throws RastyException
	 */
	static function logout(){
		
		try {

			$securityContext =  SecurityContext::getInstance();
			$securityContext->logout();
			
			session_destroy();
			
		} catch (ServiceException $e) {
			
			throw new RastyException($e->getMessage());
		}		
		
	}
	

}

?>