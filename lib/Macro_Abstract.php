<?php

/**
 * Base macro class
 */
abstract class Replica_Macro_Abstract
{
    /**
     * Get macro parameters
     *
     * @return array
     */
    abstract public function getParameters();


    /**
     * Run macro: concrete implementation
     *
     * @param  Replica_Image_Abstract $image
     * @return void
     */
    abstract protected function _doRun(Replica_Image_Abstract $image);


    /**
     * Run macro
     *
     * @param  Replica_Image_Abstract $image
     * @return void
     */
    public function run(Replica_Image_Abstract $image)
    {
        $image->exceptionIfNotInitialized();
        $this->_doRun($image);
    }

}
