<?php

ini_set('error_reporting', E_ALL | E_STRICT);
ini_set('display_errors', 1);

require_once  dirname(__FILE__)."/ezcomponents/Base/src/base.php"; // dependent on installation method, see below

function __autoload( $className )
{   
        ezcBase::autoload( $className );
}

ezcBase::addClassRepository( dirname(__FILE__).'/',dirname(__FILE__).'/lib/autoloads'); 

$input = new ezcConsoleInput();

$helpOption = $input->registerOption(
    new ezcConsoleOption(
        's',
        'siteaccess',
        ezcConsoleInput::TYPE_STRING 
    )
); 

$cronjobPartOption = $input->registerOption(
    new ezcConsoleOption(
        'm',
        'module',
        ezcConsoleInput::TYPE_STRING 
    )
); 

try
{
    $input->process();
}
catch ( ezcConsoleOptionException $e )
{
    die( $e->getMessage() );
} 

ezcBaseInit::setCallback(
 'ezcInitDatabaseInstance',
 'erLhcoreClassLazyDatabaseConfiguration'
);

$instance = erLhcoreClassSystem::instance();
$instance->SiteAccess = $helpOption->value; 
$instance->SiteDir = dirname(__FILE__).'/';
$cfgSite = erConfigClassLhConfig::getInstance();                                                           
$defaultSiteAccess = $cfgSite->getSetting( 'site', 'default_site_access' );
$optionsSiteAccess = $cfgSite->getSetting('site_access_options',$helpOption->value);                      
$instance->Language = $optionsSiteAccess['locale'];                         
$instance->ThemeSite = $optionsSiteAccess['theme'];                         
$instance->WWWDirLang = '/'.$helpOption->value;  

require_once 'PHPUnit/Runner/Version.php';
require_once 'PHPUnit/Util/Filter.php';
$version = PHPUnit_Runner_Version::id();

if ( version_compare( $version, '3.4.0' ) == -1 && $version !== '@package_version@' )
{
    die( "PHPUnit 3.4.0 (or later) is required to run this test suite.\n" );
}

PHPUnit_Util_Filter::addFileToFilter( __FILE__, 'PHPUNIT' );

$modulesTest = explode(' ',trim($cronjobPartOption->value));

// php cron.php -s site_admin -m gallery

$runner = new erLhtestRunner($modulesTest);
$runner->run();

?>