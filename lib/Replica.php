<?php

class Replica
{
    /**
     * Macro registry
     */
    private static $_registry = array();

    /**
     * Replica_Macro_CacheManager
     */
    private static $_cacheManager;


    // Macro
    // -------------------------------------------------------------------------

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
     * Has macro?
     *
     * @param  string $name
     * @return bool
     */
    static public function hasMacro($name)
    {
        return isset(self::$_registry[$name]);
    }


    /**
     * Apply macro
     *
     * @param  string                 $name
     * @param  Replica_Image_Abstract $image
     * @return void
     */
    static public function applyMacro($name, Replica_Image_Abstract $image)
    {
        $macro = self::getMacro($name);
        $image->exceptionIfNotInitialized();
        $macro->run($image);
    }


    /**
     * Remove all macro
     *
     * @return void
     */
    static public function removeAll()
    {
        self::$_registry = array();
    }


    // Cache
    // -------------------------------------------------------------------------


    /**
     * Get cache manager
     *
     * @return Replica_Macro_CacheManager
     */
    static public function cache()
    {
        if (!self::$_cacheManager) {
            throw new Replica_Exception(__METHOD__.": Cache manager not defined");
        }

        return self::$_cacheManager;
    }


    /**
     * Set cache manager
     *
     * @param  Replica_Macro_CacheManager $manager
     * @return void
     */
    static public function setCacheManager(Replica_Macro_CacheManager $manager = null)
    {
        self::$_cacheManager = $manager;
    }

}
