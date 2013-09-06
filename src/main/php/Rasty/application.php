<?php


$type = \Rasty\utils\RastyUtils::getParamGET('type') ;

$oApp = \Rasty\factory\ApplicationFactory::build( $type );

$oApp->execute();


?>
