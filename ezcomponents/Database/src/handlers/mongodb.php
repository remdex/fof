<?php
/**
 * File containing the ezcDbHandlerSqlite class.
 *
 * @package Database
 * @version 1.4.7
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * SQLite driver implementation
 *
 * @see ezcDbHandler
 * @package Database
 * @version 1.4.7
 */
class ezcDbHandlerMongoDb extends Mongo/*extends ezcDbHandler*/
{
    
    public $dbInstance = null;
        
    public $lastInsertID = null;
    
    /**
     * Constructs a handler object from the parameters $dbParams.
     *
     * Supported database parameters are:
     * - dbname|database: Database name
     * - port:            If "memory" is used then the driver will use an
     *                    in-memory database, and the database name is ignored.
     *
     * @throws ezcDbMissingParameterException if the database name was not specified.
     * @param array $dbParams Database connection parameters (key=>value pairs).
     */
    public function __construct( $dbParams )
    {
        $database = null;
        $charset  = null;
        $host     = null;
        $port     = null;
        $socket   = null;

        foreach ( $dbParams as $key => $val )
        {
            switch ( $key )
            {
                case 'database':
                case 'dbname':
                    $database = $val;
                    break;

                case 'charset':
                    $charset = $val;
                    break;

                case 'host':
                case 'hostspec':
                    $host = $val;
                    break;

                case 'port':
                    $port = $val;
                    break;
                    
                case 'username':
                    $username = $val;
                    break;
                    
                case 'password':
                    $password = $val;
                    break;

                case 'socket':
                    $socket = $val;
                    break;
            }
        }

        if ( !isset( $database ) )
        {
            throw new ezcDbMissingParameterException( 'database', 'dbParams' );
        }

        $dsn = "mongodb://";

        if ( isset( $username ) && $username && isset($password) && $password)
        {
            $dsn .= "{$username}:{$password}@";
        }
                
        if ( isset( $host ) && $host )
        {
            $dsn .= "$host";
        }

        if ( isset( $port ) && $port )
        {
            $dsn .= ":$port";
        }
      
        $dsn .= "/{$database}";        
                
        parent::__construct( $dsn );
               
        $this->dbInstance = $this->{$database};
   }

    public function beginTransaction(){
        
    }
    
    public function endTransaction(){
        
    }
    
    public function prepare()
    {

    }
    
    public function errorCode() 
    {
        return 0;
    } 
    
    public function commit() 
    {
       
    }
    
    public function lastInsertId()
    {
        return $this->lastInsertID;
    }    
    
    
    
    /**
     * Returns 'sqlite'.
     *
     * @return string
     */
    static public function getName()
    {
        return 'mongodb';
    }
    /**
     * Returns a new ezcQuerySelect derived object for the correct database type.
     *
     * @return ezcQuerySelect
     */
    public function createSelectQuery()
    {
        return new ezcQuerySelectMongoDb( $this );
    }

    /**
     * Returns a new ezcQueryUpdate derived object for the correct database type.
     *
     * @return ezcQueryUpdate
     */
    public function createUpdateQuery()
    {
        return new ezcQueryUpdateMongoDb( $this );
    }

    /**
     * Returns a new ezcQueryInsert derived object for the correct database type.
     *
     * @return ezcQueryInsert
     */
    public function createInsertQuery()
    {
        return new ezcQueryInsertMongoDb( $this );
    }

    
    public function rollback()
    {
        
    }
    
    /**
     * Returns a new ezcQueryDelete derived object for the correct database type.
     *
     * @return ezcQueryDelete
     */
    public function createDeleteQuery()
    {
        return new ezcQueryDeleteMongoDb( $this );
    }

    /**
     * Returns a new ezcQueryExpression derived object for the correct database type.
     *
     * @return ezcQueryExpression
     */
    public function createExpression()
    {
        return new ezcQueryExpressionMongoDb( $this );
    }

    /**
     * Returns a new ezcUtilities derived object for the correct database type.
     *
     * @return ezcDbUtilities
     */
    public function createUtilities()
    {
        return new ezcDbUtilities( $this );
    }

    /**
     * Returns the quoted version of an identifier to be used in an SQL query.
     * This method takes a given identifier and quotes it, so it can safely be
     * used in SQL queries.
     * 
     * @param string $identifier The identifier to quote.
     * @return string The quoted identifier.
     */
    public function quoteIdentifier( $identifier )
    {
        /*if ( sizeof( $this->identifierQuoteChars ) === 2 )
        {
            $identifier = 
                $this->identifierQuoteChars["start"]
                . str_replace( 
                    $this->identifierQuoteChars["end"],
                    $this->identifierQuoteChars["end"].$this->identifierQuoteChars["end"],
                    $identifier
                  )
                . $this->identifierQuoteChars["end"];
        }*/
        return $identifier;
    }   

}
?>
