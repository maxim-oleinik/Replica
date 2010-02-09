<?php

interface Replica_Macro_Interface
{
    /**
     * Get macro parameters
     *
     * @return array
     */
    public function getParameters();


    /**
     * Run macro
     *
     * @param  Replica_Image_Abstract $image
     * @return void
     */
    public function run(Replica_Image_Abstract $image);

}
