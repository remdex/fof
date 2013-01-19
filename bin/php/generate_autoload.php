<?php

ini_set("max_execution_time", "9600");
ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);

require_once "./ezcomponents/Base/src/base.php";

function __autoload( $className )
{
        ezcBase::autoload( $className );
}

ezcBase::addClassRepository( './', './lib/autoloads'); 


ezcBaseInit::setCallback(
 'ezcInitDatabaseInstance',
 'erLhcoreClassLazyDatabaseConfiguration'
);



require_once "lib/core/lhcore/lhautoloadgenerator.php";

$generator = new erLhcoreClassAutoloadGenerator();
$generator->buildAutoloadArrays();