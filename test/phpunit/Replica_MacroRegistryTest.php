<?php
require_once dirname(__FILE__).'/../bootstrap.php';


class Replica_Replica_MacroRegistryTest extends ReplicaTestCase
{
    /**
     * TearDown
     */
    protected function _teardown()
    {
        Replica::removeAll();
    }


    /**
     * Set/Get macro
     */
    public function testSetGetMacro()
    {
        Replica::setMacro('macro_name', $macro = $this->getMock('Replica_Macro_Interface'));
        $this->assertSame($macro, Replica::getMacro('macro_name'));
    }


    /**
     * Set macro with invalid name
     */
    public function testSetMacroWithInvalidName()
    {
        $macro = $this->getMock('Replica_Macro_Interface');

        $str = ' :;\\/~`!@#$%^&*()-=+[]{}\'\"|?<>,.я';
        for ($i=0, $n=strlen($str); $i<$n; $i++) {
            try {
                Replica::setMacro($name = 'name_'.$str[$i], $macro);
                $this->fail("Expected exception for macro name `{$name}`");
            } catch (Replica_Exception $e) {}
        }
    }


    /**
     * Get unknown macro
     */
    public function testGetUnknownMacro()
    {
        $this->setExpectedException('Replica_Exception', 'Unknown macro');
        Replica::getMacro('unknown');
    }


    /**
     * Reset registry
     */
    public function testResetRegistry()
    {
        Replica::setMacro('macro_name', $macro = $this->getMock('Replica_Macro_Interface'));
        Replica::removeAll();
        $this->setExpectedException('Replica_Exception', 'Unknown macro');
        Replica::getMacro('macro_name');
    }


    /**
     * Apply macro
     */
    public function testApplyMacro()
    {
        $image = new Replica_ImageGD;
        $image->loadFromFile($this->getFileNameInput('gif_16x14'));

        $macro = $this->getMock('Replica_Macro_Interface', array('run'));
        $macro->expects($this->once())
              ->method('run')
              ->with($this->equalTo($image));

        Replica::setMacro('macro_name', $macro);
        Replica::applyMacro('macro_name', $image);
    }


    /**
     * Apply unknown macro
     */
    public function testApplyUnknownMacro()
    {
        $this->setExpectedException('Replica_Exception', 'Unknown macro');
        Replica::applyMacro('macro_name', new Replica_ImageGD);
    }


    /**
     * Apply macro with empty image
     */
    public function testApplyMacroWithEmptyImage()
    {
        Replica::setMacro('macro_name', $macro = $this->getMock('Replica_Macro_Interface'));

        $this->setExpectedException('Replica_Exception', 'Image NOT loaded');
        Replica::applyMacro('macro_name', new Replica_ImageGD);
    }

}
