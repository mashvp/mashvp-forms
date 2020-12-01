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
        }

        return false;
    }

    public function renderTemplateToString($name, $locals = [])
    {
        ob_start();

        $content = $this->renderTemplate($name, $locals);

        return ob_get_clean();
    }
}
