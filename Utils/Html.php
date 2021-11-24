<?php

namespace Core\Utils;

class Html
{

    public static function links($paths = [])
    {
        foreach ($paths as $path)
            echo "<link rel='stylesheet' href='{$path}'>";
    }

    public static function input($type = "text", $options = [])
    {
        $options = self::options($options);
        $input = "<input type='{$type}'{$options}>";
        return $input;
    }

    public static function form($content = [], $options = [], $method = "post", $action = false)
    {
        $action = resumeIf($action, $action, "");
        $options = self::options($options);
        $contentString = "";
        $x = 1;
        foreach ($content as $item) {
            $contentString .= $item;
            if (count($content))
                $x++;
        }
        return "<form {$action} method='{$method}'{$options}>{$contentString}</form>";
    }

    public static function error($_error, $title = false): string
    {
        self::links([
            'css/style.css',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css'
        ]);
        $error = "<div class='alert alert-danger'>";
        if ($title)
            $error .= "<h4>{$title}</h4><br>";
        $error .= "{$_error}";
        $error .= "</div>";
        return $error;
    }


    private static function options($options): string
    {
        $optionsString = "";
        if (count($options) > 0)
            foreach ($options as $key => $value)
                $optionsString .= " {$key} = '{$value}'";
        return $optionsString;
    }

}