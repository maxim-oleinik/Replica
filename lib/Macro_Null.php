<?php

class Replica_Macro_Null implements Replica_Macro_Interface
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
    public function run(Replica_Image_Abstract $image)
    {
    }

}
