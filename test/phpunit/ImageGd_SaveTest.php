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
            if ($image->getMimeType() != Replica_Image_Abstract::TYPE_JPEG) {
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
     * Save with compression
     */
    public function testSaveWithCompession()
    {
        $image = new Replica_Image_Gd;

        // PNG
        $image->loadFromFile($this->getFileNameInput('png_120x90'));

        $image->saveAs($path1 = $this->getFileNameActual(__METHOD__.'1'), 'image/png', 0);
        $image->saveAs($path2 = $this->getFileNameActual(__METHOD__.'2'), 'image/png', 50);
        $image->saveAs($path3 = $this->getFileNameActual(__METHOD__.'3'), 'image/png', 100);

        $this->assertGreaterThan(filesize($path1), filesize($path2), 'PNG image1 less than image2');
        $this->assertGreaterThan(filesize($path2), filesize($path3), 'PNG image2 less than image3');

        // JPG
        $image->saveAs($path1 = $this->getFileNameActual(__METHOD__.'1'), 'image/jpeg', 0);
        $image->saveAs($path2 = $this->getFileNameActual(__METHOD__.'2'), 'image/jpeg', 50);
        $image->saveAs($path3 = $this->getFileNameActual(__METHOD__.'3'), 'image/jpeg', 100);

        $this->assertGreaterThan(filesize($path1), filesize($path2), 'JPEG image1 less than image2');
        $this->assertGreaterThan(filesize($path2), filesize($path3), 'JPEG image2 less than image3');
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
