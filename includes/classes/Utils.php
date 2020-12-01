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

    public static function template_path($name)
    {
        $template_dir = self::PLUGIN_BASE_PATH . '/templates';
        $file_path = $template_dir . "/$name.html.php";

        return $file_path;
    }
}
