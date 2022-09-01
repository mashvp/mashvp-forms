<?php

namespace Mashvp\Forms;

use Mashvp\StaticClass;
use Mashvp\Forms\Utils;

class Renderer extends StaticClass
{
    public static function renderTemplate($name, $locals = [])
    {
        $path = Utils::template_path($name);
        $path = apply_filters('mvpf/template_path', $path, $name, $locals);

        if (is_readable($path)) {
            extract($locals);

            include $path;

            return true;
        } else {
            echo "<!-- [mashvp-forms] Render error: Template \"$name\" not found -->";
        }

        return false;
    }

    public static function renderTemplateToString($name, $locals = [])
    {
        ob_start();

        self::renderTemplate($name, $locals);

        return ob_get_clean();
    }
}
