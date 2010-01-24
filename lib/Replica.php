<?php

class Replica
{
    /**
     * Macro registry
     */
    private static $_registry = array();


    /**
     * Static class
     */
    private function __construct() {}
    private function __clone() {}


    /**
     * Set macro
     *
     * @param  string                  $name - only [a-zA-Z0-9_]+
     * @param  Replica_Macro_Interface $macro
     * @return void
     */
    static public function setMacro($name, Replica_Macro_Interface $macro)
    {
        if (!preg_match('/^[a-z0-9_]+$/i', $name)) {
            throw new Replica_Exception(__METHOD__.": Invalid macro name `{$name}`");
        }

        self::$_registry[$name] = $macro;
    }


    /**
     * Get macro
     *
     * @param  string $name
     * @return Replica_Macro_Interface
     */
    static public function getMacro($name)
    {
        if (!isset(self::$_registry[$name])) {
            throw new Replica_Exception(__METHOD__.": Unknown macro `{$name}`");
        }
        return self::$_registry[$name];
    }


    /**
     * Apply macro
     *
     * @param  string          $name
     * @param  Replica_ImageGD $image
     * @return void
     */
    static public function applyMacro($name, Replica_ImageGD $image)
    {
        $macro = self::getMacro($name);
        $image->exceptionIfNotLoaded();
        $macro->run($image);
    }


    /**
     * Remove all macros
     *
     * @return void
     */
    static public function removeAll()
    {
        self::$_registry = array();
    }

}
