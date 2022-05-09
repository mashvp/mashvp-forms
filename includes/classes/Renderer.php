<?php

namespace Mashvp\Forms;

use Mashvp\SingletonClass;
use Mashvp\Forms\Utils;

class Renderer extends SingletonClass
{
    public function renderTemplate($name, $locals = [])
    {
        $path = Utils::template_path($name);

        if (is_readable($path)) {
            extract($locals);

            include $path;

            return true;
        } else {
            echo "<!-- [mashvp-forms] Render error: Template \"$name\" not found -->";
        }

        return false;
    }

    public function renderTemplateToString($name, $locals = [])
    {
        ob_start();

        $this->renderTemplate($name, $locals);

        return ob_get_clean();
    }
}
