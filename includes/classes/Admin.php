<?php

namespace Mashvp\Forms;

use Mashvp\SingletonClass;
use Mashvp\Forms\Renderer;
use Mashvp\Forms\Utils;

class Admin extends SingletonClass
{
    public const GLOBAL_OPTIONS_NAME = '_mashvp-forms__global-options';

    public const FORM_FIELDS_META_NAME = '_mashvp-forms__fields';
    public const FORM_OPTIONS_META_NAME = '_mashvp-forms__options';

    private function shouldInit()
    {
        if (isset($_GET['post_type'])) {
            return in_array(
                $_GET['post_type'],
                ['mvpf-form', 'mvpf-submission']
            );
        }

        if (isset($_GET['post'])) {
            return in_array(
                get_post_type($_GET['post']),
                ['mvpf-form', 'mvpf-submission']
            );
        }

        return false;
    }

    public function register()
    {
        if (is_admin()) {
            $css = Utils::dist_uri('admin.css');
            $css_version = @filemtime(Utils::dist_path('admin.css'));

            $js = Utils::dist_uri('index.min.js');
            $js_version = @filemtime(Utils::dist_path('index.min.js'));

            wp_register_style('mashvp-forms--admin-styles', $css, [], $css_version);
            wp_register_script('mashvp-forms--admin-script', $js, ['wp-i18n'], $js_version);
            wp_set_script_translations(
                'mashvp-forms--admin-script',
                'mashvp-forms',
                MASHVP_FORMS__PATH . 'languages'
            );

            $this->registerOptions();

            if ($this->shouldInit()) {
                $this->addMetaBoxes();
            }
        }
    }

    private function registerOptions()
    {
        register_setting(
            'mvpf-general-options',
            'mvpf__antispam-recaptcha--version',
            [
                'type' => 'string',
                'description' => _x('reCAPTCHA version', 'Global settings option description', 'mashvp-forms'),
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );

        register_setting(
            'mvpf-general-options',
            'mvpf__antispam-recaptcha--hide-badge',
            [
                'type' => 'boolean',
                'description' => _x('Hide badge? (for v2 invisible only)', 'Global settings option description', 'mashvp-forms'),
            ]
        );

        register_setting(
            'mvpf-general-options',
            'mvpf__antispam-recaptcha--sitekey',
            [
                'type' => 'string',
                'description' => _x('reCAPTCHA site key', 'Global settings option description', 'mashvp-forms'),
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );

        register_setting(
            'mvpf-general-options',
            'mvpf__antispam-recaptcha--secretkey',
            [
                'type' => 'string',
                'description' => _x('reCAPTCHA secret key', 'Global settings option description', 'mashvp-forms'),
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );
    }

    public function registerAdminMenu()
    {
        add_submenu_page(
            'edit.php?post_type=mvpf-form',
            _x('General form settings', 'Menu page title', 'mashvp-forms'),
            _x('Settings', 'Menu page title', 'mashvp-forms'),
            'manage_options',
            'mvpf-general-options',
            [$this, 'formGeneralOptionsRenderContent'],
            99
        );
    }

    public function enqueueScripts()
    {
        if (is_admin()) {
            if ($this->shouldInit()) {
                $css = Utils::dist_uri('admin.css');
                $css_version = @filemtime(Utils::dist_path('admin.css'));

                $js = Utils::dist_uri('index.min.js');
                $js_version = @filemtime(Utils::dist_path('index.min.js'));

                wp_enqueue_style('mashvp-forms--admin-styles', $css, [], $css_version);
                wp_enqueue_script('mashvp-forms--admin-script', $js, ['wp-i18n'], $js_version);
            }
        }
    }

    private function addMetaBoxes()
    {
        // Form
        add_meta_box(
            'post_metadata_mashvp-forms__fields',
            __('Form fields', 'mashvp-forms'),
            [$this, 'renderFormFieldsMetabox'],
            'mvpf-form',
            'normal',
            'high'
        );

        add_meta_box(
            'post_metadata_mashvp-forms__options',
            __('Form options', 'mashvp-forms'),
            [$this, 'renderFormOptionsMetabox'],
            'mvpf-form',
            'normal',
            'low'
        );

        add_meta_box(
            'post_metadata_mashvp-forms__shortcode',
            __('Shortcode', 'mashvp-forms'),
            [$this, 'renderShortcodeMetabox'],
            'mvpf-form',
            'side',
            'low'
        );

        add_meta_box(
            'post_metadata_mashvp-forms__field-options',
            __('Field options', 'mashvp-forms'),
            [$this, 'renderFieldOptionsMetabox'],
            'mvpf-form',
            'side',
            'low'
        );

        // Submissions
        add_meta_box(
            'post_metadata_mashvp-forms__submission-fields',
            __('Form fields', 'mashvp-forms'),
            [$this, 'renderSubmissionFieldsMetabox'],
            'mvpf-submission',
            'normal',
            'high'
        );

        add_meta_box(
            'post_metadata_mashvp-forms__submission-info',
            __('Info', 'mashvp-forms'),
            [$this, 'renderSubmissionInfoMetabox'],
            'mvpf-submission',
            'side',
            'low'
        );
    }

    private function getFormOptionFromPost($name, $default = null, $boolean = false)
    {
        if ($boolean) {
            return Utils::get($_POST, "mvpf_options--{$name}", false) === 'on';
        }

        return Utils::get($_POST, "mvpf_options--{$name}", $default);
    }

    private function getFormOptionValues()
    {
        $values = [
            'notifications' => [
                'email' => [
                    'enabled' => $this->getFormOptionFromPost('notification__email--enabled', null, true),
                    'settings' => $this->getFormOptionFromPost('notification__email--values')
                ]
            ],

            'antispam' => [
                'honeypot' => [
                    'enabled' => $this->getFormOptionFromPost('antispam_honeypot--enabled', null, true),
                ],
                'recaptcha' => [
                    'enabled'  => $this->getFormOptionFromPost('antispam_recaptcha--enabled', null, true),
                ]
            ]
        ];

        return $values;
    }

    public function savePostData($post_id)
    {
        if (
            !$post_id ||
            get_post_type($post_id) !== 'mvpf-form' ||
            (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ||
            (get_post_status($post_id) === 'auto-draft')
        ) {
            return;
        }

        if (
            isset($_REQUEST['fields_nonce']) &&
            wp_verify_nonce($_REQUEST['fields_nonce'], 'update_mashvp-forms__fields')
        ) {
            update_post_meta(
                $post_id,
                self::FORM_FIELDS_META_NAME,
                sanitize_text_field($_REQUEST[self::FORM_FIELDS_META_NAME])
            );
        }

        if (
            isset($_REQUEST['options_nonce']) &&
            wp_verify_nonce($_REQUEST['options_nonce'], 'update_mashvp-forms__options')
        ) {
            update_post_meta(
                $post_id,
                self::FORM_OPTIONS_META_NAME,
                $this->getFormOptionValues()
            );
        }
    }

    public function formGeneralOptionsRenderContent()
    {
        Renderer::instance()->renderTemplate('admin/general-options', []);
    }

    public function renderFormFieldsMetabox()
    {
        global $post;

        $form_fields_json = get_post_meta($post->ID, self::FORM_FIELDS_META_NAME, true);

        Renderer::instance()->renderTemplate(
            'admin/metaboxes/fields-editor',
            [
                'form_fields_json' => $form_fields_json
            ]
        );
    }

    public function renderFormOptionsMetabox()
    {
        global $post;

        $form_options = get_post_meta($post->ID, self::FORM_OPTIONS_META_NAME, true);

        Renderer::instance()->renderTemplate(
            'admin/metaboxes/options-editor',
            [
                'form_options' => $form_options
            ]
        );
    }

    public function renderShortcodeMetabox()
    {
        global $post;

        Renderer::instance()->renderTemplate('admin/shortcode-input', ['id' => $post->ID]);
    }

    public function renderFieldOptionsMetabox()
    {
        Renderer::instance()->renderTemplate('admin/metaboxes/field-options');
    }

    public function renderSubmissionFieldsMetabox()
    {
        global $post;

        $fields = get_post_meta($post->ID, self::FORM_FIELDS_META_NAME, true);

        Renderer::instance()->renderTemplate(
            'admin/metaboxes/submission-fields',
            [
                'id' => $post->ID,
                'post' => $post,
                'fields' => $fields
            ]
        );
    }

    public function renderSubmissionInfoMetabox()
    {
        global $post;

        $submission = new Submission($post->ID);

        Renderer::instance()->renderTemplate(
            'admin/metaboxes/submission-info',
            [
                'id' => $post->ID,
                'post' => $post,
                'submission' => $submission,
            ]
        );
    }
}
