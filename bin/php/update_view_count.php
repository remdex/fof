<?php

ini_set("max_execution_time", "9600");
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

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


$input = new ezcConsoleInput();

$helpOption = $input->registerOption(
    new ezcConsoleOption(
        's',
        'siteaccess',
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

$siteAccessName = 'site_admin';
if ( !$helpOption->value === false )
{
    $siteAccessName = $helpOption->value;
} 


try
{
    $input->process();
}
catch ( ezcConsoleOptionException $e )
{
    die( $e->getMessage() );
}

$instance = erLhcoreClassSystem::instance();
$instance->SiteAccess = $siteAccessName; 
$instance->SiteDir = './';
$cfgSite = erConfigClassLhConfig::getInstance();    
$defaultSiteAccess = $cfgSite->getSetting( 'site', 'default_site_access' );
$optionsSiteAccess = $cfgSite->getSetting('site_access_options',$siteAccessName);                      
$instance->Language = $optionsSiteAccess['locale'];                         
$instance->ThemeSite = $optionsSiteAccess['theme'];                         
$instance->WWWDirLang = '/'.$siteAccessName;   


// Main part of code taken from eZ Publish CMS

$logHitSettings = $cfgSite->getSetting( 'site', 'delay_image_hit_log_settings' );


$logFilePath = $logHitSettings['log_path'];
$domainMatch = $logHitSettings['host'];


$startLine = "";
$hasStartLine = false;
$pidIDArray = array();

$updateViewLogPath = "cache/updateview.log";
if ( is_file( $updateViewLogPath ) )
{
    $fh = fopen( $updateViewLogPath, "r" );
    if ( $fh )
    {
        while ( !feof ( $fh ) )
        {
            $line = fgets( $fh, 1024 );
            if ( preg_match( "/\[/", $line ) )
            {
                $startLine = $line;
                $hasStartLine = true;
            }
        }
        fclose( $fh );
    }
}


if ( is_file( $logFilePath ) )
{
    $handle = fopen( $logFilePath, "r" );
    if ( $handle )
    {
        $startParse = false;
        $stopParse = false;
        while ( !feof ($handle) and !$stopParse )
        {
            $line = fgets($handle, 1024);
            if ( !empty( $line ) )
            {
                if ( $line != "" )
                    $lastLine = $line;

                if ( $startParse or !$hasStartLine )
                {
                    $logPartArray = preg_split( "/[\"]+/", $line );
                    $timeIPPart = $logPartArray[0];
                    list( $ip, $timePart ) = explode( '[', $timeIPPart );
                    list( $time, $rest ) = explode( ' ', $timePart );
                    
                    if (strpos($ip,' ')) {                   // Domain is present
                        list($ip, $host) = explode(' ',$ip); // If domain is present
                    }  else {
                        $host = '';
                    }
                    
                    $requirePart = $logPartArray[1];

                    list( $requireMethod, $url ) = explode( ' ', $requirePart );
                    $url = preg_replace( "/\?.*/", "", $url);
                                     
                    if ($domainMatch === false || $host == '' || in_array($host,$domainMatch) ) {
                        
                        $timeDayParts = explode(':',substr($time,-8));
                        $timeStamp = mktime($timeDayParts[0],$timeDayParts[1],$timeDayParts[2]);

                        if ($timeStamp > time()) {
                            $timeStamp = $timeStamp - 24*3600; //In same cases during log rotate etc.
                        }
                    
                                               
                        $imageID = array();              
                        if ( strpos( $url, 'gallery/image/' ) !== false )
                        {                            
                            $url = str_replace( "gallery/image/", "", $url );
                            $url = str_replace( "/", "", $url );
                            preg_match('/^[0-9]{1,}/',$url,$imageID);

                            if (isset($imageID[0]) && is_numeric($imageID[0]))
                            {                           
                                if (isset($pidIDArray[$imageID[0]])) {
                                    $pidIDArray[$imageID[0]]['hits'] = $pidIDArray[$imageID[0]]['hits'];
                                    $pidIDArray[$imageID[0]]['time'] = $timeStamp;                                 
                                } else {
                                    $pidIDArray[$imageID[0]]['hits'] = 1;
                                    $pidIDArray[$imageID[0]]['time'] = $timeStamp;
                                }   
                            }                                                  
                            
                        } elseif ( preg_match('/\-[0-9]{1,}p\.html/',$url,$imageID) ) {
                            $pid = str_replace(array('-','p.html'),array(),$imageID[0]);
                            
                            if (is_numeric($pid))
                            {
                                if (isset($pidIDArray[$pid])) {                                
                                    $pidIDArray[$pid]['hits'] = $pidIDArray[$pid]['hits']+1;
                                    $pidIDArray[$pid]['time'] = $timeStamp;                                
                                } else {
                                    $pidIDArray[$pid]['hits'] = 1;
                                    $pidIDArray[$pid]['time'] = $timeStamp;
                                }
                            };
                        } 
                    }
                }
                if ( $line == $startLine )
                {
                    $startParse = true;
                }
            }
        }
        fclose( $handle );
    }    
}

$db = ezcDbInstance::get();
$totalItems = 0;

$partsInsert = array();
foreach ($pidIDArray as $pid => $hitImage)
{
    $insert = "($pid,{$hitImage['time']})";
    $totalItems += $hitImage['hits'];
    for($i = 0; $i < $hitImage['hits'];$i++) {
        $partsInsert[] = $insert;
    }   
    
    if (count($partsInsert) > 500) {
        $sql = 'INSERT INTO lh_delay_image_hit VALUES '.implode(',',$partsInsert).';';
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $partsInsert = array();
    }    
}

if (count($partsInsert) > 0) {
    $sql = 'INSERT INTO lh_delay_image_hit VALUES '.implode(',',$partsInsert).';';
    $stmt = $db->prepare($sql);
    $stmt->execute();
}

echo "Total updated - ".$totalItems,"\n";

$stmt = $db->prepare("DELETE FROM lh_delay_image_hit WHERE pid NOT IN (SELECT pid FROM lh_gallery_images WHERE approved = 1)");
$stmt->execute();

$fh = fopen( $updateViewLogPath, "w" );
if ( $fh )
{
    fwrite( $fh, "# Finished at " . date('Y-m-d H:i:s') . "\n" );
    fwrite( $fh, "# Last updated entry:" . "\n" );
    fwrite( $fh, $lastLine . "\n" );
    fclose( $fh );
}