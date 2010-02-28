<?php
require_once dirname(__FILE__).'/../bootstrap.php';

/**
 * Macro: chain test
 *
 * @author  Maxim Oleinik <maxim.oleinik@gmail.com>
 */
class Replica_Macro_ChainTest extends ReplicaTestCase
{
    /**
     * Run chain
     */
    public function testRunChain()
    {
        $chain = new Replica_Macro_Chain;
        $chain->add(new Replica_Macro_ThumbnailFit(80, 80));
        $chain->add(new Replica_Macro_Overlay(10, 15, $imagePath = $this->getFileNameInput('png_transparent_60x60')));

        $expectedParams = array(
            'Replica_Macro_ThumbnailFit' => array(
                'maxWidth'  => 80,
                'maxHeight' => 80,
            ),
            'Replica_Macro_Overlay' => array(
                'posX'  => 10,
                'posY'  => 15,
                'image' => $imagePath,
            ),
        );
        $this->assertEquals($expectedParams, $chain->getParameters(), 'Chain parameters');

        $image = new Replica_Image_Gd;
        $image->loadFromFile($this->getFileNameInput('png_120x90'));
        $chain->run($image);

        $this->assertImage($image, 80, 60, 'image/png');
        $image->saveAs($path = $this->getFileNameActual(__METHOD__));
        $this->assertImageFile($this->getFileNameExpected(__METHOD__), $path);
    }

}
