<?php

namespace Mashvp\Forms;

abstract class Utils
{
    public const PLUGIN_BASE_PATH = WP_PLUGIN_DIR . '/mashvp-forms';

    public static function asset_uri($name)
    {
        $asset_dir = plugin_dir_url('mashvp-forms') . 'mashvp-forms/assets';
        $file_path = $asset_dir . "/$name";

        return $file_path;
    }

    public static function asset_path($name)
    {
        $asset_dir = self::PLUGIN_BASE_PATH . '/assets';
        $file_path = $asset_dir . "/$name";

        return $file_path;
    }

    public static function dist_uri($name)
    {
        $dist_dir = plugin_dir_url('mashvp-forms') . 'mashvp-forms/dist';
        $file_path = $dist_dir . "/$name";

        return $file_path;
    }

    public static function dist_path($name)
    {
        $dist_dir = self::PLUGIN_BASE_PATH . '/dist';
        $file_path = $dist_dir . "/$name";

        return $file_path;
    }

    public static function template_path($name)
    {
        $template_dir = self::PLUGIN_BASE_PATH . '/templates';
        $file_path = $template_dir . "/$name.html.php";

        return $file_path;
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
            isset($GLOBALS['__mvpf_render_globals']) &&
            is_array($GLOBALS['__mvpf_render_globals']) &&
            !empty($GLOBALS['__mvpf_render_globals'])
        ) {
            $globals = $GLOBALS['__mvpf_render_globals'];

            return static::get($globals, $prop, $default);
        }

        return $default;
    }
}
