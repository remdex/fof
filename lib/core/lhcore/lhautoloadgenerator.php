<?php

/**
 * File containing the eZAutoloadGenerator class.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version 4.3.0
 * @package kernel
 * 
 * @author Remigijus Kiminas, Only adopted to fit my needs original author eZ Publish
 */


class erLhcoreClassAutoloadGenerator
{
    protected $autoloadArrays;
    
    
    /**
     * Bitmask for searhing in kernel files
     */
    const MODE_KERNEL = 0;

    /**
     * Bitmask for search in extension files
     */
    const MODE_EXTENSION = 1;
    
    /**
     * Bitmask for search in test files
     */
    const MODE_TEST = 2;
        
    /**
     * Nametable for each of the MODE_* modes defined in the class.
     *
     * @var array
     */
    protected $modeName = array(
                                    self::MODE_KERNEL => "Kernel",
                                    self::MODE_EXTENSION => "Extension",
                                    self::MODE_TEST => "Test"
                               );
                               
    /**
     * Constructs class to generate autoload arrays.
     */
    function __construct(  )
    {       
        // Set up arrays for existing autoloads, used to check for class name
        // collisions.
        $this->existingAutoloadArrays = array();
        $this->existingAutoloadArrays[self::MODE_KERNEL] = @include 'lib/autoloads/lhcore_autoload.php';
        $this->existingAutoloadArrays[self::MODE_TEST] = @include 'lib/autoloads/lhtest_autoload.php';
        $this->existingAutoloadArrays[self::MODE_EXTENSION] = @include 'var/autoloads/lhextension_autoload.php';
       
        $this->messages = array();
        $this->warnings = array();        
    }
    
    /**
     * Searches specified directories for classes, and build autoload arrays.
     *
     * @throws Exception if desired output directory is not a directory, or if
     *         the autoload arrays are not writeable by the script.
     * @return void
     */
    public function buildAutoloadArrays()
    {
      
        $phpFiles = $this->fetchFiles();

        
        
        $phpClasses = array();
        foreach ( $phpFiles as $mode => $fileList )
        {
            $phpClasses[$mode] = $this->getClassFileList( $fileList, $mode );
        }

                
        $maxClassNameLength = $this->checkMaxClassLength( $phpClasses );
        $this->autoloadArrays = $this->dumpArray( $phpClasses, $maxClassNameLength );

        
        $this->writeAutoloadFiles();
        
    }
    
    /**
     * Calculates the length of the longest class name present in $depdata
     *
     * @param array $depData
     * @return mixed
     */
    protected function checkMaxClassLength( $depData )
    {
        $max = array();
        foreach( array_keys( $depData) as $key )
        {
            $max[$key] = 0;
        }

        foreach( $depData as $location => $locationBundle )
        {
            foreach ( $locationBundle as $className => $path )
            {
                if ( strlen( $className ) > $max[$location] )
                {
                    $max[$location] = strlen( $className );
                }
            }
        }
        return $max;
    }
    
    /**
     * Provides a look-up for which base directory to use depending on mode.
     *
     * @param int $lookup
     * @return string
     */
    protected function targetTable( $lookup )
    {
        $targets = array(
                            self::MODE_EXTENSION => "var/autoloads",
                            self::MODE_TEST      => "lib/autoloads",
                            self::MODE_KERNEL    => "lib/autoloads"
                        );

        if ( array_key_exists( $lookup, $targets ) )
        {
            return $targets[$lookup];
        }
        return false;
    }
    
    
    /**
     * Build string version of the autoload array with correct indenting.
     *
     * @param array $sortedArray
     * @param int $length
     * @return string
     */
    protected function dumpArray( $sortedArray, $length )
    {
        $retArray = array();
        foreach ( $sortedArray as $location => $sorted )
        {
            $ret = '';
            $offset = $length[$location] + 2;
            foreach( $sorted as $class => $path )
            {
                $ret .= sprintf( "      %-{$offset}s => '%s'," . PHP_EOL, "'{$class}'", $path );
            }
            $retArray[$location] = $ret;
        }
        return $retArray;
    }
    
    
    /**
     * Writes the autoload data in <var>$data</var> for the mode/location
     * <var>$location</var> to actual files.
     *
     * This method also make sure that the target directory exists, and directs
     * the different autoload arrays to different destinations, depending on
     * the operation mode.
     *
     * @param int $location
     * @param string $data
     * @return void
     */
    protected function writeAutoloadFiles()
    {
        $directorySeparators = "/\\";

        foreach( $this->autoloadArrays as $location => $data )
        {
            
            $targetBasedir = $this->targetTable( $location );
            
            if( file_exists( $targetBasedir ) && !is_dir( $targetBasedir ) )
            {
                throw new Exception( "Specified target: {$targetBasedir} is not a directory." );
            }
            else if ( !file_exists( $targetBasedir ) )
            {               
                mkdir( $targetBasedir, 0777, true );                
            }

            $filename = $this->nameTable( $location );
            $filePath = $targetBasedir . '/' . $filename;

             $file = fopen( $filePath, "w+" );
             if ( $file )
             {
                 fwrite( $file, $this->dumpArrayStart( $location ) );
                 fwrite( $file, $data );
                 fwrite( $file, $this->dumpArrayEnd() );
                 fclose( $file );                 
                 chmod( $filePath, 0777 );  
             }
             else
             {
                 throw new Exception( __METHOD__ . ": The file {$filePath} is not writable by the system." );
             }
         }
    }
    
    /**
     * Prints generated code used for the autoload files
     *
     * @param string $part
     * @return string
     */
    protected function dumpArrayStart( $part )
    {
        $description = "";
        switch( $part )
        {          
            case self::MODE_EXTENSION:
            case self::MODE_KERNEL:
            case self::MODE_TEST:        
                $description = $this->modeName[$part];
                break;
        }
        return <<<ENDL
<?php
/**
 * Autoloader definition for HPPG $description files.
 *
 */

return array(

ENDL;
    }
      
      
    /**
     * Prints generated code for end of the autoload files
     *
     * @return void
     */
    protected function dumpArrayEnd()
    {
        return <<<END
    );

?>
END;
    }
    
    /**
     * Table to look up file names to use for different run modes.
     *
     * @param string $lookup Mode to look up, can be extension, or kernel.
     * @return string
     */
    protected function nameTable( $lookup )
    {
        $names = array( self::MODE_EXTENSION => "lhextension_autoload.php",                     
                        self::MODE_TEST      => "lhtest_autoload.php",
                        self::MODE_KERNEL    => "lhcore_autoload.php",
                      );

        if ( array_key_exists( $lookup, $names ) )
        {
            return $names[$lookup];
        }
        return false;
    }
    
    
    /**
     * Builds a filelist of all PHP files in $path.
     *
     * @param string $path
     * @param array $extraFilter
     * @return array
     */
    protected function buildFileList( $path, $extraFilter = null )
    {
        $dirSep = '/';
        $exclusionFilter = array( "@^{$path}{$dirSep}(var|settings|benchmarks|bin|autoload|port_info|update|templates|tmp|UnitTest|lib{$dirSep}ezc){$dirSep}@" );
        if ( !empty( $extraFilter ) and is_array( $extraFilter ) )
        {
            foreach( $extraFilter as $filter )
            {
                $exclusionFilter[] = $filter;
            }
        }

        if (!empty( $path ) )
        {
            return self::findRecursive( $path, array( '@\.php$@' ), $exclusionFilter );
        }
        return false;
    }
    
    /**
     * Returns an array indexed by location for classes and their filenames.
     *
     * @param string $path The base path to start the search from.
     * @param string $mask A binary mask which instructs the function whether to fetch kernel-related or extension-related files.
     * @return array
     */
    protected function fetchFiles()
    {
        $path = 'extension';
           
        $sanitisedBasePath = $path;
               
        $retFiles = array(); 
             
        $retFiles[self::MODE_EXTENSION] = $this->buildFileList( "extension" );

        //Make all the paths relative to $path
        foreach ( $retFiles as &$fileBundle )
        {
            foreach ( $fileBundle as $key => &$file )
            {               
                $fileBundle[$key] = ezcBaseFile::calculateRelativePath( $file, $path );
            }
        }
        unset( $file, $fileBundle );
        return $retFiles;
    }
    
    
    /**
     * Uses the walker in ezcBaseFile to find files.
     * 
     * This also uses the callback to get progress information about the file search.
     *
     * @param string $sourceDir 
     * @param array $includeFilters 
     * @param array $excludeFilters
     * @param eZAutoloadGenerator $gen 
     * @return array
     */
    public static function findRecursive( $sourceDir, array $includeFilters = array(), array $excludeFilters = array() )
    {        
        return ezcBaseFile::findRecursive( $sourceDir, $includeFilters );     
    }
    
    
    /**
     * Extracts class information from PHP sourcecode.
     * @return array (className=>filename)
     */
    protected function getClassFileList( $fileList, $mode )
    {
        $retArray = array();
        
        foreach( $fileList as $file )
        {       

            $file = "extension/".$file;
            
            $tokens = @token_get_all( file_get_contents( $file ) );
            foreach( $tokens as $key => $token )
            {
                if ( is_array( $token ) )
                {
                    switch( $token[0] )
                    {
                        case T_CLASS:
                        case T_INTERFACE:
                           
                            // CLASS_TOKEN - WHITESPACE_TOKEN - TEXT_TOKEN (containing class name)
                            $className = $tokens[$key+2][1];

                            $filePath = $file;
                            
                            $retArray[$className] = $filePath;
                            
                            break;
                    }
                }
            }
        }
       
        ksort( $retArray );
        return $retArray;
    }
}