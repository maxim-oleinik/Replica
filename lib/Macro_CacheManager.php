<?php

class Replica_Macro_CacheManager
{
    /**
     * Save dir
     */
    private static $_dir;


    /**
     * Set cache dir
     *
     * @param  string $dir
     * @return void
     */
    static public function setDir($dir)
    {
        self::$_dir = (string) $dir;
    }


    /**
     * Get macro result
     *
     * @param  string|Replica_Macro_Interface $macro
     * @param  Replica_ImageProxy             $imageProxy
     * @param  string                         $mimeType
     * @return void
     */
    static public function get($macro, Replica_ImageProxy $imageProxy, $mimeType = Replica_ImageGD::TYPE_PNG)
    {
        // Get macro
        if ($macro instanceof Replica_Macro_Interface) {
            $macroName = get_class($macro);
        } else {
            $macroName = (string) $macro;
            $macro = Replica::getMacro((string)$macroName);
        }

        // Make UID for macro result
        $fileName = md5(
              $imageProxy->getUid()
            . $macroName
            . get_class($macro)
            . serialize($macro->getParameters())
        );
        $fileName .= self::_getExtension($mimeType);

        // Define image save path
        $relativeDir = $macroName   . DIRECTORY_SEPARATOR
                     . $fileName[0] . DIRECTORY_SEPARATOR
                     . $fileName[1] . DIRECTORY_SEPARATOR
                     . $fileName[2];
        $fileDir  = self::$_dir . DIRECTORY_SEPARATOR . $relativeDir;
        $filePath = $fileDir . DIRECTORY_SEPARATOR . $fileName;

        // Run macro and cache
        if (!file_exists($filePath)) {
            $image = $imageProxy->getImage();
            $macro->run($image);

            self::_checkDir($fileDir);
            $image->saveAs($filePath, $mimeType);
        }

        return $relativeDir . DIRECTORY_SEPARATOR . $fileName;
    }


    /**
     * Get file extension by mime type
     *
     * @param  string $mimeType
     * @return string
     */
    static private function _getExtension($mimeType)
    {
        switch ($mimeType) {
            case Replica_ImageGD::TYPE_PNG:
                return '.png';
                break;

            case Replica_ImageGD::TYPE_GIF:
                return '.gif';
                break;

            case Replica_ImageGD::TYPE_JPEG:
                return '.jpg';
                break;

            default:
                throw new Replica_Exception(__METHOD__.": Unknown image type `{$mimeType}`");
        }
    }


    /**
     * Check/create dir to save result
     *
     * @param  string $dir
     * @return void
     */
    static private function _checkDir($dir)
    {
        if (!self::$_dir) {
            throw new Replica_Exception(__CLASS__.": Save dir not defined");
        }

        $errorLevel = error_reporting(0);
        try {
            if (!file_exists($dir)) {
                if (!mkdir($dir, 0777, true)) {
                    throw new Replica_Exception(__CLASS__.": Failed to create directory `{$dir}`");
                }
            }
        } catch (Exception $e) {}
        error_reporting($errorLevel);

        if (isset($e)) {
            throw $e;
        }
    }

}
