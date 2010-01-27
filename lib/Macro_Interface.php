<?php

interface Replica_Macro_Interface
{
    /**
     * Run macro
     *
     * @param  Replica_ImageGD $image
     * @return void
     */
    public function run(Replica_ImageGD $image);


    /**
     * Get macro parameters
     *
     * @return array
     */
    public function getParameters();
}
