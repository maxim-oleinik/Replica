<?php
require_once dirname(__FILE__).'/../bootstrap.php';


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

        $image = new Replica_ImageGd;
        foreach ($files as $file) {

            $image->loadFromFile($originPath = $this->getFileNameInput($file));
            $actualPath = $this->getFileNameActual(__METHOD__.'_'.$file);
            $image->saveAs($actualPath);

            $this->assertImageFile($image, $actualPath, $file);
            if ($image->getType() != Replica_ImageGD::TYPE_JPEG) {
                $this->assertImageFile($originPath, $actualPath, $file);
            }
        }
    }

}
