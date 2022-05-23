<?php

namespace Mashvp\Forms;

use Mashvp\SingletonClass;

use Mashvp\Forms\Form;
use Mashvp\Forms\Utils;

class ShortCode extends SingletonClass
{
    private const DEFAULT_ATTRIBUTES = [ 'id' => false, 'is_admin_preview' => false ];

    public function register()
    {
        add_shortcode('mashvp-form', [$this, 'renderShortCode']);
    }

    public function renderShortCode($attrs = [])
    {
        $attributes = shortcode_atts(self::DEFAULT_ATTRIBUTES, $attrs);

        if ($attributes['id'] === false) {
            error_log('[mashvp-forms] Shortcode called incorrectly. Missing mandatory parameter `id`');

            return "<!-- [mashvp-form] shortcode called incorrectly. Missing mandatory parameter `id` -->";
        }

        $form = new Form($attributes['id'], [
            'is_admin_preview' => !!Utils::get($attributes, 'is_admin_preview', false),
        ]);

        ob_start();
        $form->render();

        return ob_get_clean();
    }
}
