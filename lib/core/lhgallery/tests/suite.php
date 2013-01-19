<?php

require_once 'images.php';

class lh_gallery_test extends PHPUnit_Framework_TestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName("");
        
        $this->addTest( ezcImagesTest::suite() );
    }
    
    public static function suite()
    {
        return new lh_gallery_test();
    }
}
?>