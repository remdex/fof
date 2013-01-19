<?php

class lhMysqlStore extends Auth_OpenID_DatabaseConnection {
    
    /**
     * Run an SQL query and return the first column of the first row
     * of the result set, if any.
     *
     * @param string $sql An SQL string with placeholders.  The
     * placeholders are assumed to be specific to the database engine
     * for this connection.
     *
     * @param array $params An array of parameters to insert into the
     * SQL string using this connection's escaping mechanism.
     *
     * @return mixed $result The value of the first column of the
     * first row of the result set.  False if no such result was
     * found.
     */
    function getOne($sql, $params = array())
    {
        foreach ($params as $key => $count)
        {
           $sql = preg_replace('/\?/', ':param'.$key, $sql, 1);              
        }
             
        $db = ezcDbInstance::get();
        $stmt = $db->prepare($sql);
        
        foreach ($params as $key => $param){
            $stmt->bindValue( ':param'.$key,$param); 
        }
                     
        $stmt->execute();
        $rows = $stmt->fetchColumn();  
                      
        return $rows;
    }

    /**
     * Run an SQL query and return the first row of the result set, if
     * any.
     *
     * @param string $sql An SQL string with placeholders.  The
     * placeholders are assumed to be specific to the database engine
     * for this connection.
     *
     * @param array $params An array of parameters to insert into the
     * SQL string using this connection's escaping mechanism.
     *
     * @return array $result The first row of the result set, if any,
     * keyed on column name.  False if no such result was found.
     */
    function getRow($sql, $params = array())
    {
        foreach ($params as $key => $count)
        {
           $sql = preg_replace('/\?/', ':param'.$key, $sql, 1);              
        }
             
        $db = ezcDbInstance::get();
        $stmt = $db->prepare($sql);
        
        foreach ($params as $key => $param){
            $stmt->bindValue( ':param'.$key,$param); 
        }
                     
        $stmt->execute();
        $rows = $stmt->fetch();
        
        if (count($rows) == 0) return false;
              
        return $rows;
        
    }

    /**
     * Run an SQL query with the specified parameters, if any.
     *
     * @param string $sql An SQL string with placeholders.  The
     * placeholders are assumed to be specific to the database engine
     * for this connection.
     *
     * @param array $params An array of parameters to insert into the
     * SQL string using this connection's escaping mechanism.
     *
     * @return array $result An array of arrays representing the
     * result of the query; each array is keyed on column name.
     */
    function getAll($sql, $params = array())
    {
        foreach ($params as $key => $count)
        {
           $sql = preg_replace('/\?/', ':param'.$key, $sql, 1);              
        }
             
        $db = ezcDbInstance::get();
        $stmt = $db->prepare($sql);
        
        foreach ($params as $key => $param){
            $stmt->bindValue( ':param'.$key,$param); 
        }
                     
        $stmt->execute();
        $rows = $stmt->fetchAll();  
            
        return $rows;
    }
    
     /**
     * Run an SQL query with the specified parameters, if any.
     *
     * @param string $sql An SQL string with placeholders.  The
     * placeholders are assumed to be specific to the database engine
     * for this connection.
     *
     * @param array $params An array of parameters to insert into the
     * SQL string using this connection's escaping mechanism.
     *
     * @return mixed $result The result of calling this connection's
     * internal query function.  The type of result depends on the
     * underlying database engine.  This method is usually used when
     * the result of a query is not important, like a DDL query.
     */
    function query($sql, $params = array())
    {      
        foreach ($params as $key => $count)
        {
           $sql = preg_replace('/\?|!/', ':param'.$key, $sql, 1);              
        }  
               
        $db = ezcDbInstance::get();
        $stmt = $db->prepare($sql);
        
        foreach ($params as $key => $param) {           
           
                $stmt->bindValue( ':param'.$key,$param,PDO::PARAM_STR);             
        }
                             
        $stmt->execute();
    }
    
}