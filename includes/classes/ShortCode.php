<?php

namespace Mashvp\Forms;

use Mashvp\SingletonClass;
use Mashvp\Forms\Form;

class ShortCode extends SingletonClass
{
    private const DEFAULT_ATTRIBUTES = [ 'id' => false ];

    public function register()
    {
        add_shortcode('mashvp-form', [$this, 'renderShortCode']);
    }

    public function renderShortCode($attrs = [])
    {
        $attributes = shortcode_atts(self::DEFAULT_ATTRIBUTES, $attrs);

        if ($attributes['id'] === false) {
            error_log('[mashvp-forms] Shortcode called incorrectly. Missing mandatory parameter `id`');

            return <<<HTML
                <!-- [mashvp-form] shortcode called incorrectly. Missing mandatory parameter `id` -->
            HTML;
        }

        $form = new Form($attributes['id']);

        return $form->render();
    }
}
