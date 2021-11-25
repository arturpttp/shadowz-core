<?php

namespace Core\System;

class Bootstrap {

    public static string $appFolder = "";

    /**
     * @param string $appFolder
     */
    public function __construct(string $appFolder)
    {
        self::$appFolder = $appFolder;
    }


}