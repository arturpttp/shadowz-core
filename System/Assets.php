<?php

namespace Core\System;

class Assets
{
    private static array $paths = [
        'css' => 'public/css',
        'js' => 'public/js',
        'javascript' => 'public/js',
        'images' => 'public/images',
    ];

    public array $css = [], $js = [], $images = [];

    /**
     * @param array $css
     * @param array $js
     * @param array $images
     */
    public function __construct(array $css, array $js, array $images)
    {
        $this->css = $css;
        $this->js = $js;
        $this->images = $images;
        $this->css[] = 'style.css';
        $this->images['logo.png'] = 'Logo';

    }


    public function load($autoLoad = true)
    {

        $rootPath = dirname(__DIR__, 2) . '/';
        $cssPath = $rootPath . self::$paths['css'];
        $jsPath = $rootPath . self::$paths['js'];
        $imagesPath = $rootPath . self::$paths['images'];
        $css = [];
        $js = [];
        $images = [];

        foreach ($this->css as $item)
            $css[] = "<link rel='stylesheet' href='$cssPath/$item'>";
        foreach ($this->js as $item)
            $js[] = "<script src='$jsPath/$item'></script>";
        foreach ($this->images as $item => $alt)
            $images[] = "<img src='$imagesPath/$item' alt='$alt'>";
        $assets = Arrays::merge($css, $js, $images);
        foreach ($assets as $asset)
            if ($autoLoad)
                echo $asset;
    }

}
