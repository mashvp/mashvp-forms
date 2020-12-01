<?php

namespace Mashvp\Forms;

use Mashvp\SingletonClass;
use Mashvp\Forms\Renderer;
use Mashvp\Forms\Utils;

class Admin extends SingletonClass
{
    private function should_init()
    {
        if (isset($_GET['post_type'])) {
            return $_GET['post_type'] === 'mashvp-form';
        }

        if (isset($_GET['post'])) {
            return get_post_type($_GET['post']) === 'mashvp-form';
        }

        return false;
    }

    public function register()
    {
        if (is_admin()) {
            $css = Utils::asset_uri('style.css');
            $css_version = @filemtime(Utils::asset_path('style.css'));

            $js = Utils::asset_uri('index.min.js');
            $js_version = @filemtime(Utils::asset_path('index.min.js'));

            wp_register_style('mashvp-forms--admin-styles', $css, [], $css_version);
            wp_register_script('mashvp-forms--admin-script', $js, ['wp-i18n'], $js_version);
            wp_set_script_translations(
                'mashvp-forms--admin-script',
                'mashvp-forms',
                MASHVP_FORMS__PATH . 'languages'
            );

            if ($this->should_init()) {
                $this->add_meta_boxes();

                add_action('save_post', [$this, 'save_post_data']);
                add_action('pre_post_update', [$this, 'save_post_data']);
            }
        }
    }

    public function enqueue_scripts()
    {
        if (is_admin()) {
            if ($this->should_init()) {
                $css = Utils::asset_uri('style.css');
                $css_version = @filemtime(Utils::asset_path('style.css'));

                $js = Utils::asset_uri('index.min.js');
                $js_version = @filemtime(Utils::asset_path('index.min.js'));

                wp_enqueue_style('mashvp-forms--admin-styles', $css, [], $css_version);
                wp_enqueue_script('mashvp-forms--admin-script', $js, ['wp-i18n'], $js_version);
            }
        }
    }

    private function add_meta_boxes()
    {
        add_meta_box(
            'post_metadata_mashvp-forms__fields',
            __('Form fields', 'mashvp-forms'),
            [$this, 'render_form_fields_metabox'],
            'mashvp-form',
            'normal',
            'high'
        );

        add_meta_box(
            'post_metadata_mashvp-forms__shortcode',
            __('Shortcode', 'mashvp-forms'),
            [$this, 'render_shortcode_metabox'],
            'mashvp-form',
            'side',
            'low'
        );

        add_meta_box(
            'post_metadata_mashvp-forms__field-options',
            __('Field options', 'mashvp-forms'),
            [$this, 'render_field_options_metabox'],
            'mashvp-form',
            'side',
            'low'
        );
    }

    public function save_post_data()
    {
        global $post;

        var_dump('VAR_DUMP IN save_post_data');
        var_dump($post);
        var_dump($_POST);

        if (
            !$post ||
            $post->post_type !== 'mashvp-forms' ||
            (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ||
            (get_post_status($post->ID) === 'auto-draft')
        ) {
            return;
        }

        update_post_meta(
            $post->ID,
            '_mashvp-forms__fields',
            sanitize_text_field($_POST['_mashvp-forms__fields'])
        );
    }

    public function render_form_fields_metabox()
    {
        global $post;
        $custom = get_post_custom($post->ID);

        $form_fields_json = $custom['_mashvp-forms__fields'][0] ?? '';

        echo '<pre><code>';
        var_dump($custom);
        echo '</code></pre>';

        Renderer::instance()->renderTemplate('admin/metaboxes/fields-editor');
    }

    public function render_shortcode_metabox()
    {
        global $post;

        Renderer::instance()->renderTemplate('admin/shortcode-input', ['id' => $post->ID]);
    }

    public function render_field_options_metabox()
    {
        Renderer::instance()->renderTemplate('admin/field-options');
    }
}
