<?php

declare(strict_types=1);

namespace Mashvp\Forms;

use Mashvp\StaticClass;
use Mashvp\Forms\Utils;

class Renderer extends StaticClass
{
  public static function renderTemplate(string $name, $locals = [], $globals = []): bool
  {
    $path = Utils::template_path($name);
    $path = apply_filters('mvpf/template_path', $path, $name, $locals);

    if (is_readable($path)) {
      $has_provided_globals = is_array($globals) && $globals !== [];

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
      echo sprintf('<!-- [mashvp-forms] Render error: Template "%s" not found -->', $name);
    }

    return false;
  }

  public static function renderTemplateToString(string $name, $locals = []): string|false
  {
    ob_start();

    self::renderTemplate($name, $locals);

    return ob_get_clean();
  }
}
