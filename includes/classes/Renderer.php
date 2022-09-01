<?php

namespace Mashvp\Forms;

use Mashvp\StaticClass;
use Mashvp\Forms\Utils;

class Renderer extends StaticClass
{
    public static function renderTemplate($name, $locals = [], $globals = [])
    {
        $path = Utils::template_path($name);
        $path = apply_filters('mvpf/template_path', $path, $name, $locals);

        if (is_readable($path)) {
            $has_provided_globals = is_array($globals) && !empty($globals);

            extract($locals);

            if ($has_provided_globals) {
                $GLOBALS['__mvpf_render_globals'] = $globals;
            }

            include $path;

            if ($has_provided_globals) {
                unset($GLOBALS['__mvpf_render_globals']);
            }

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
