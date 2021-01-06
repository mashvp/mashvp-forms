<?php

namespace Mashvp\Forms;

use Mashvp\SingletonClass;

class Front extends SingletonClass
{
    public function register()
    {
        if (!is_admin()) {
            $css = Utils::dist_uri('front.css');
            $css_version = @filemtime(Utils::dist_path('front.css'));

            wp_register_style('mashvp-forms--front-styles', $css, [], $css_version);
        }
    }

    public function enqueueScripts()
    {
        if (!is_admin()) {
            $css = Utils::dist_uri('front.css');
            $css_version = @filemtime(Utils::dist_path('front.css'));

            wp_enqueue_style('mashvp-forms--front-styles', $css, [], $css_version);
        }
    }
}
