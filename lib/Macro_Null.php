<?php

class Replica_Macro_Null extends Replica_Macro_Abstract
{
    /**
     * Get macro parameters
     *
     * @return array
     */
    public function getParameters()
    {
        return array();
    }


    /**
     * Run
     *
     * @param  Replica_Image_Abstract $image
     * @return void
     */
    protected function _doRun(Replica_Image_Abstract $image)
    {
    }

}
