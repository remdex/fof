<?php

class erLhcoreClassLhAPC
{
    public function set($key, $value, $compress, $ttl = 0)
    {
        apc_store($key,$value,$ttl);
    }
    
    public function get($var)
    {
        return apc_fetch($var);
    }
    
    public function increment($var,$version)
    {
        apc_inc($var);
    }
}


?>