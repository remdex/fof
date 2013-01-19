<?php

class ezcImagesTest extends ezcTestCase
{
    public function testImagesCount()
    {
        $session = erLhcoreClassGallery::getSession();
        $q = $session->database->createSelectQuery();  
        $q->select( "COUNT(pid)" )->from( "lh_gallery_images" );  
        $stmt = $q->prepare();       
        $stmt->execute();
        $result = $stmt->fetchColumn(); 
                      
        $this->assertEquals($result,erLhcoreClassModelGalleryImage::getImageCount());
    }
    
    public static function suite()
    {
         return new PHPUnit_Framework_TestSuite( "ezcImagesTest" );
    }
}

?>
