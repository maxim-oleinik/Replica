<?php

class Replica_Macro_Null implements Replica_Macro_Interface
{
    public function getParameters()
    {
        return array();
    }


    /**
     * Run
     *
     * @param  Replica_ImageGD $image
     * @return void
     */
    public function run(Replica_ImageGD $image)
    {
    }

}
