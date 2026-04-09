<?php

declare(strict_types=1);

namespace Mashvp\Forms;

abstract class Utils
{
  public const PLUGIN_BASE_PATH = WP_PLUGIN_DIR . '/mashvp-forms';

  public static function asset_uri(string $name)
  {
    $asset_dir = plugin_dir_url('mashvp-forms') . 'mashvp-forms/assets';

    return $asset_dir . ('/' . $name);
  }

  public static function asset_path(string $name)
  {
    $asset_dir = self::PLUGIN_BASE_PATH . '/assets';

    return $asset_dir . ('/' . $name);
  }

  public static function dist_uri(string $name)
  {
    $dist_dir = plugin_dir_url('mashvp-forms') . 'mashvp-forms/dist';

    return $dist_dir . ('/' . $name);
  }

  public static function dist_path(string $name)
  {
    $dist_dir = self::PLUGIN_BASE_PATH . '/dist';

    return $dist_dir . ('/' . $name);
  }

  public static function template_path(string $name)
  {
    $template_dir = self::PLUGIN_BASE_PATH . '/templates';

    return $template_dir . sprintf('/%s.html.php', $name);
  }

  public static function get($array, $prop, $default = null)
  {
    if ($array && is_array($array) && isset($array[$prop])) {
      return $array[$prop];
    }

    return $default;
  }

  public static function get_render_global($prop, $default = null)
  {
    if (
      isset($GLOBALS['__mvpf_render_globals'])
      && is_array($GLOBALS['__mvpf_render_globals'])
      && (isset($GLOBALS['__mvpf_render_globals']) && $GLOBALS['__mvpf_render_globals'] !== [])
    ) {
      $globals = $GLOBALS['__mvpf_render_globals'];

      return static::get($globals, $prop, $default);
    }

    return $default;
  }
}
