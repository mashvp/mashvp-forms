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

            $js = Utils::dist_uri('front.min.js');
            $js_version = @filemtime(Utils::dist_path('front.min.js'));

            wp_register_style('mashvp-forms--front-styles', $css, [], $css_version);
            wp_register_script('mashvp-forms--front-script', $js, ['wp-i18n'], $js_version);
            wp_set_script_translations(
                'mashvp-forms--front-script',
                'mashvp-forms',
                MASHVP_FORMS__PATH . 'languages'
            );

            $GLOBALS['__mvpf_form_iteration'] = 1;
        }
    }

    public function enqueueScripts()
    {
        if (!is_admin()) {
            $css = Utils::dist_uri('front.css');
            $css_version = @filemtime(Utils::dist_path('front.css'));

            $js = Utils::dist_uri('front.min.js');
            $js_version = @filemtime(Utils::dist_path('front.min.js'));

            wp_enqueue_style('mashvp-forms--front-styles', $css, [], $css_version);
            wp_enqueue_script('mashvp-forms--front-script', $js, ['wp-i18n'], $js_version);
            wp_localize_script('mashvp-forms--front-script', '__mvpf', [
                'adminAjax' => [
                    'url' => admin_url('admin-ajax.php')
                ]
            ]);
        }
    }
}
