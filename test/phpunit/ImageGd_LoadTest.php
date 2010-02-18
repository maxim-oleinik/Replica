<?php
require_once dirname(__FILE__).'/../bootstrap.php';

/**
 * ImageGd: load test
 *
 * @author  Maxim Oleinik <maxim.oleinik@gmail.com>
 */
class Replica_ImageGd_LoadTest extends ReplicaTestCase
{
    /**
     * Assert image is loaded
     *
     * @param  Replica_Image_Gd $image
     * @param  string          $message
     * @return void
     */
    private function assertLoaded(Replica_Image_Gd $image, $message)
    {
        $this->assertTrue($image->isInitialized(), $message.": Expected image IS initialized");
        $res = $image->getResource();
        $this->assertTrue($res && is_resource($res) && get_resource_type($res) == 'gd', $message.': is GD resource');
    }


    /**
     * Load from file
     */
    public function testLoadFromFile()
    {
        $plan = array(
            array('png_120x90', 120, 90, 'image/png'),
            array('gif_16x14',   16, 14, 'image/gif'),
            array('jpg_8x16',     8, 16, 'image/jpeg'),
        );

        $image = new Replica_Image_Gd;
        foreach ($plan as $item) {
            list($name, $width, $height, $type) = $item;

            $this->assertTrue($image->loadFromFile($this->getFileNameInput($name)), "{$name}: Load successful");
            $this->assertImage($image, $width, $height, $type, $name);
            $this->assertLoaded($image, $name);
        }
    }


    /**
     * Load from file not image
     */
    public function testLoadFromFileNotImage()
    {
        $image = new Replica_Image_Gd;
        $this->assertFalse($image->loadFromFile(__FILE__), 'Load failed');
        $this->assertFalse($image->isInitialized(), "Expected image is NOT initialized");
    }


    /**
     * Load from string
     */
    public function testLoadFromString()
    {
        $plan = array(
            array('png_120x90', 120, 90, 'image/png'),
            array('gif_16x14',   16, 14, 'image/gif'),
            array('jpg_8x16',     8, 16, 'image/jpeg'),
        );

        $image = new Replica_Image_Gd;
        foreach ($plan as $item) {
            list($name, $width, $height, $type) = $item;

            $this->assertTrue($image->loadFromString(file_get_contents($this->getFileNameInput($name)), $type), "{$name}: Load successful");
            $this->assertImage($image, $width, $height, $type, $name);
            $this->assertLoaded($image, $name);
        }
    }


    /**
     * Load from string not image
     */
    public function testLoadFromStringNotImage()
    {
        $image = new Replica_Image_Gd;
        $this->assertFalse($image->loadFromString(file_get_contents(__FILE__), 'image/png'), 'Load failed');
        $this->assertFalse($image->isInitialized(), "Expected image is NOT initialized");
    }

}
