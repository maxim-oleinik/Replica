<?php
require_once dirname(__FILE__).'/../bootstrap.php';

/**
 * ImageGd: save test
 *
 * @author  Maxim Oleinik <maxim.oleinik@gmail.com>
 */
class Replica_ImageGd_SaveTest extends ReplicaTestCase
{
    /**
     * Save image
     */
    public function testSave()
    {
        $files = array(
            'png_120x90',
            'gif_16x14',
            'jpg_8x16',
        );

        $image = new Replica_Image_Gd;
        foreach ($files as $file) {

            $image->loadFromFile($originPath = $this->getFileNameInput($file));
            $actualPath = $this->getFileNameActual(__METHOD__.'_'.$file);
            $image->saveAs($actualPath);

            $this->assertImageFile($image, $actualPath, $file);
            if ($image->getType() != Replica_Image_Abstract::TYPE_JPEG) {
                $this->assertImageFile($originPath, $actualPath, $file);
            }
        }
    }


    /**
     * Save with type
     */
    public function testSaveWithType()
    {
        $image = new Replica_Image_Gd;
        $image->loadFromFile($this->getFileNameInput('gif_16x14'));

        $path = $this->getFileNameActual(__METHOD__);
        $image->saveAs($path, 'image/png');

        $this->assertImage($path, 16, 14, 'image/png');
    }


    /**
     * Invalid type exception
     */
    public function testInvalidTypeException()
    {
        $image = new Replica_Image_Gd;
        $image->loadFromFile($this->getFileNameInput('gif_16x14'));

        $this->setExpectedException('Replica_Exception', 'Unknown image type');
        $image->saveAs('/some/path', 'unknown type');
    }

}
