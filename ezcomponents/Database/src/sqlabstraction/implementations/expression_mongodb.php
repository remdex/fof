<?php
/**
 * File containing the ezcQueryExpressionPgsql class.
 *
 * @package Database
 * @version 1.4.7
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * The ezcQueryExpressionPgsql class is used to create SQL expression for PostgreSQL.
 *
 * This class reimplements the methods that have a different syntax in postgreSQL.
 *
 * @package Database
 * @version 1.4.7
 */
class ezcQueryExpressionMongoDb
{
    
    public $db = null;
    
    /**
     * Constructs an pgsql expression object using the db $db.
     *
     * @param PDO $db
     */
    public function __construct( $db )
    {        
        $this->db = $db;
    }
    
    public function eqId( $value1, $value2 )
    {
        return array('_id' => $value2);
    }
    
    public function eq( $value1, $value2 )
    {
        return array($value1 => $value2);
    }
    
    public function neq( $value1, $value2 )
    {
        return array($value1 => array('$ne' => $value2));
    }
    
    public function gt( $value1, $value2 )
    {
        return array($value1 => array( '$gt' => $value2 ) );
    }
    
    public function gte( $value1, $value2 )
    {
        return array($value1 => array( '$gte' => $value2 ) );
    }
    
    public function lt( $value1, $value2 )
    {
        return array($value1 => array( '$lt' => $value2 ) );
    }
    
    public function lte( $value1, $value2 )
    {
        return array($value1 => array( '$lte' => $value2 ) );
    }
    
    public function in( $value1, $value2 )
    {
        return array($value1 => array( '$in' => $value2 ) );
    }
    
    public function allin( $value1, $value2 )
    {
        return array($value1 => array( '$all' => $value2 ) );
    }
    
    public function nin( $value1, $value2 )
    {
        return array($value1 => array( '$nin' => $value2 ) );
    }
    
    public function like( $value1, $value2 )
    {                
        return array($value1 => array( '$regex' => $value2, '$options' => 'i' ) );
    }
    
    /**
     * Returns the SQL to bind logical expressions together using a logical and.
     *
     * lAnd() accepts an arbitrary number of parameters. Each parameter
     * must contain a logical expression or an array with logical expressions.
     *
     * Example:
     * <code>
     * $q = ezcDbInstance::get()->createSelectQuery();
     * $e = $q->expr;
     * $q->select( '*' )->from( 'table' )
     *                  ->where( $e->lAnd( $e->eq( 'id', $q->bindValue( 1 ) ),
     *                                     $e->eq( 'id', $q->bindValue( 2 ) ) ) );
     * </code>
     *
     * @throws ezcDbAbstractionException if called with no parameters.
     * @return string a logical expression
     */
    public function lAnd()
    {
        $args = func_get_args();
        if ( count( $args ) < 1 )
        {
            throw new ezcQueryVariableParameterException( 'lAnd', count( $args ), 1 );
        }

        $elements = ezcQuerySelect::arrayFlatten( $args );
        if ( count( $elements ) == 1 )
        {
            return $elements[0];
        }
        else
        {
            return $elements;
        }
    }
    
    
    /**
     * Sets the aliases $aliases for this object.
     *
     * The aliases can be used to substitute the column and table names with more
     * friendly names. E.g PersistentObject uses it to allow using property and class
     * names instead of column and table names.
     *
     * @param array(string=>string) $aliases
     * @return void
     */
    public function setAliases( array $aliases )
    {
        $this->aliases = $aliases;
    }

    /**
     * Returns true if this object has aliases.
     *
     * @return bool
     */
    public function hasAliases()
    {
        return $this->aliases !== null ? true : false;
    }
    
}
?>
