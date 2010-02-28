<?php

/**
 * Macro chain
 *
 * Runs macro group
 * Implements composite pattern
 *
 * @package    Replica
 * @subpackage Macro
 * @author     Maxim Oleinik <maxim.oleinik@gmail.com>
 */
class Replica_Macro_Chain extends Replica_Macro_Abstract
{
    private
        $_macro      = array(),
        $_parameters = array();


    /**
     * Add macro
     *
     * @param  Replica_Macro_Abstract $macro
     * @return void
     */
    public function add(Replica_Macro_Abstract $macro)
    {
        $this->_macro[] = $macro;
        $this->_parameters[get_class($macro)] = $macro->getParameters();
    }


    /**
     * Get macro parameters
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->_parameters;
    }


    /**
     * Run
     *
     * @param  Replica_Image_Abstract $image
     * @return void
     */
    protected function _doRun(Replica_Image_Abstract $image)
    {
        foreach ($this->_macro as $macro) {
            $macro->run($image);
        }
    }

}
