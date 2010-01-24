<?php
require_once dirname(__FILE__).'/../bootstrap.php';


class Replica_ImageGd_LoadTest extends ReplicaTestCase
{
    /**
     * Assert image is loaded
     *
     * @param  Replica_ImageGd $image
     * @param  bool            $loaded
     * @param  string          $message
     * @return void
     */
    private function assertLoaded(Replica_ImageGd $image, $loaded, $message)
    {
        if ($loaded) {
            $this->assertTrue($image->isLoaded(), $message.": Image is loaded");
            $res = $image->getResource();
            $this->assertTrue($res && is_resource($res) && get_resource_type($res) == 'gd', $message.': is GD resource');

        } else {
            $this->assertFalse($image->isLoaded(), $message.": Image is NOT loaded");
            $this->assertNull($image->getResource(), $message.": Resource is NULL");
        }
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

        $image = new Replica_ImageGd;
        foreach ($plan as $item) {
            list($name, $width, $height, $type) = $item;

            $this->assertTrue($image->loadFromFile($this->getFileNameInput($name)), "{$name}: Load successful");
            $this->assertImage($image, $width, $height, $type, $name);
            $this->assertLoaded($image, $loaded = true, $name);
        }
    }


    /**
     * Load from file not image
     */
    public function testLoadFromFileNotImage()
    {
        $image = new Replica_ImageGd;
        $this->assertFalse($image->loadFromFile(__FILE__), 'Load failed');
        $this->assertLoaded($image, $loaded = false, 'Not Image');
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

        $image = new Replica_ImageGd;
        foreach ($plan as $item) {
            list($name, $width, $height, $type) = $item;

            $this->assertTrue($image->loadFromString(file_get_contents($this->getFileNameInput($name)), $type), "{$name}: Load successful");
            $this->assertImageInfo($image, $width, $height, $type, $name);
            $this->assertLoaded($image, $loaded = true, $name);
        }
    }


    /**
     * Load from string not image
     */
    public function testLoadFromStringNotImage()
    {
        $image = new Replica_ImageGd;
        $this->assertFalse($image->loadFromString(file_get_contents(__FILE__), 'image/png'), 'Load failed');
        $this->assertLoaded($image, $loaded = false, 'Not Image');
    }


    /**
     * Reset image
     */
    public function testResetImage()
    {
        $image = new Replica_ImageGd;
        $image->loadFromFile($this->getFileNameInput('gif_16x14'));

        $image->reset();
        $this->assertLoaded($image, $loaded = false, 'Reset image');
        $this->assertImageInfo($image, null, null, null, 'Reset image');
    }

}
