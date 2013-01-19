<?php
/**
 * File containing the ezcQuerySelectMssql class.
 *
 * @package Database
 * @version 1.0
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * SQL Server specific implementation of ezcQuery.
 *
 * This class reimplements the LIMIT method in which the
 * SQL Server differs from the standard implementation in ezcQuery.
 *
 * @see ezcQuery
 * @package Database
 * @version 1.4.7
 */
class ezcQuerySelectMongoDb extends ezcQueryMongoDb
{
    protected $fromString = null;
    
    public $cursor = null;
    
    /**
     * Sort the result ascending.
     */
    const ASC = 1;

    /**
     * Sort the result descending.
     */
    const DESC = -1;
    
    /**
     * If a limit and/or offset has been set for this query.
     *
     * @var bool
     */
    private $hasLimit = false;

    /**
     * The limit set.
     *
     * @var int
     */
    private $limit = 0;

    /**
     * The offset set.
     *
     * @var int
     */
    private $offset = 0;

    /**
     * Stores the ORDER BY part of the SQL.
     *
     * @var string
     */
    protected $orderString = null;
    
    
    /**
     * Resets the query object for reuse.
     *
     * @return void
     */
    public function reset()
    {
        $this->hasLimit = false;
        $this->limit = 0;
        $this->offset = 0;
        $this->orderColumns = array();
        parent::reset();
    }
        
    /**
     * Returns SQL that limits the result set.
     *
     * $limit controls the maximum number of rows that will be returned.
     * $offset controls which row that will be the first in the result
     * set from the total amount of matching rows.
     *
     * @param int $limit integer expression
     * @param int $offset integer expression
     * @return void
     */
    public function limit( $limit, $offset = 0 )
    {
        $this->hasLimit = true;
        $this->limit = $limit;
        $this->offset = $offset;
        return $this;
    }

    /**
     * Saves the ordered columns in an internal array so we can invert that order
     * if we need to in the limit() workaround
     *
     * @param string $column a column name in the result set
     * @param string $type if the column should be sorted ascending or descending.
     *        you can specify this using ezcQuerySelect::ASC or ezcQuerySelect::DESC
     * @return ezcQuery a pointer to $this
     */
    public function orderBy( $column, $type = self::ASC )
    {        
        $this->orderString = $column;    
        return $this;    
    }
        
    public function from()
    {
        $args = func_get_args();
                              
        $this->fromString = current($args);
        return $this;
    }
    
    public $whereString = array();       
        
    public function where()
    {
        $args = func_get_args();
        $expressions = self::arrayFlatten( $args );               
        $this->whereString = $expressions;
        
        return $this;
    } 
    
    public function select()
    {        
        return $this;
    }
    
    private $selectFields = array();
    
    public function setSelectFields(array $fields) {                
        $this->selectFields = $fields;
    }
    
    public function execute() {
        
        // select a collection (analogous to a relational database's table)       
        $collection = $this->db->dbInstance->{$this->fromString};
                
        $this->cursor = $collection->find($this->whereString,$this->selectFields);  
      
        if ($this->hasLimit) {
            $this->cursor->limit($this->limit);
        }
        
        if ($this->offset) {
            $this->cursor->skip($this->offset);
        }
        
        if (is_array($this->orderString)){
            $this->cursor->sort($this->orderString);
        }            
               
        return $this->cursor;
    }
    
}

?>
