<?php

namespace Mashvp\Forms;

use Mashvp\SingletonClass;
use Mashvp\Forms\Renderer;
use Mashvp\Forms\Utils;
use Mashvp\Forms\Submission;

use Mashvp\Forms\CSVExporter;

class Admin extends SingletonClass
{
    public const GLOBAL_OPTIONS_NAME = '_mashvp-forms__global-options';

    public const FORM_FIELDS_META_NAME = '_mashvp-forms__fields';
    public const FORM_OPTIONS_META_NAME = '_mashvp-forms__options';

    public const SECURITY_CODE = 'qdCHbdMLD8U62WXDPSwfgGBzKN5WLR5r';

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

            $js = Utils::dist_uri('admin.min.js');
            $js_version = @filemtime(Utils::dist_path('admin.min.js'));

            wp_register_style('mashvp-forms--admin-styles', $css, [], $css_version);
            wp_register_script('mashvp-forms--admin-script', $js, ['wp-i18n'], $js_version);
            wp_set_script_translations(
                'mashvp-forms--admin-script',
                'mashvp-forms',
                MASHVP_FORMS__PATH . 'languages'
            );

            $this->registerOptions();
            $this->registerAdminFormHandlers();
            $this->registerAjaxAction();

            if ($this->shouldInit()) {
                $this->addMetaBoxes();
            }
        }
    }

    private function registerAdminFormHandlers()
    {
        add_action('wp_ajax_mvpfadmin__export_data', [$this, 'handleExportFormData']);
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

    private function registerAjaxAction()
    {
        add_action(
            'wp_ajax_mvpf__get_exporter_settings',
            [$this, 'getExporterSettings'],
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
            980
        );

        add_submenu_page(
            'edit.php?post_type=mvpf-form',
            _x('Export data', 'Menu page title', 'mashvp-forms'),
            _x('Export', 'Menu page title', 'mashvp-forms'),
            'manage_options',
            'mvpf-export-data',
            [$this, 'formExportDataRenderContent'],
            990
        );
    }

    public function enqueueScripts()
    {
        if (is_admin()) {
            if ($this->shouldInit()) {
                $css = Utils::dist_uri('admin.css');
                $css_version = @filemtime(Utils::dist_path('admin.css'));

                $js = Utils::dist_uri('admin.min.js');
                $js_version = @filemtime(Utils::dist_path('admin.min.js'));

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
            'submission' => [
                'ajax' => [
                    'enabled' => $this->getFormOptionFromPost('submission__ajax--enabled', null, true)
                ]
            ],

            'notifications' => [
                'email' => [
                    'enabled' => $this->getFormOptionFromPost('notification__email--enabled', null, true),
                    'settings' => $this->getFormOptionFromPost('notification__email--values')
                ]
            ],

            'antispam' => [
                'honeypot' => [
                    'enabled' => $this->getFormOptionFromPost('antispam__honeypot--enabled', null, true),
                ],
                'recaptcha' => [
                    'enabled'  => $this->getFormOptionFromPost('antispam__recaptcha--enabled', null, true),
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
        Renderer::instance()->renderTemplate('admin/general-options');
    }

    public function formExportDataRenderContent()
    {
        Renderer::instance()->renderTemplate('admin/export-data');
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

        $fields = maybe_unserialize(
            get_post_meta($post->ID, self::FORM_FIELDS_META_NAME, true)
        );

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

    private function getExporterClass($export_format)
    {
        if ($export_format) {
            switch ($export_format) {
                case 'csv':
                    return 'Mashvp\Forms\CSVExporter';

                default:
                    return null;
            }
        }

        return null;
    }

    private function getExportData()
    {
        $query = new \WP_Query([
            'post_type'      => 'mvpf-submission',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'post_parent'    => Utils::get($_REQUEST, 'form_id'),
        ]);

        return array_map(function ($submission_id) {
            return new Submission($submission_id);
        }, wp_list_pluck($query->posts, 'ID'));
    }

    private function collectExporterSettings()
    {
        $settings = [];

        foreach ($_REQUEST as $key => $value) {
            $matches = [];

            if (preg_match("/^mvpf_es__(.+)$/", $key, $matches)) {
                $settingKey = $matches[1];

                $settings[$settingKey] = $value;
            }
        }

        return $settings;
    }

    public function handleExportFormData()
    {
        if (
            current_user_can('export') &&
            wp_verify_nonce(
                $_REQUEST[self::SECURITY_CODE],
                'mvpfadmin__export_data'
            )
        ) {
            $exporter_class = $this->getExporterClass(
                Utils::get($_REQUEST, 'export_format')
            );

            if ($exporter_class) {
                $exporter_settings = $this->collectExporterSettings();
                $exporter          = new $exporter_class($exporter_settings);
                $export_data       = $this->getExportData();

                $exporter->generateFile($export_data);
                die();
            }
        }
    }

    public function getExporterSettings()
    {
        header('Content-Type: application/json');

        $format = Utils::get($_REQUEST, 'export_format');

        if (!$format) {
            die(json_encode([
                'success' => false,
                'message' => 'Missing required parameter `export_format`',
                'data'    => [],
            ]));
        }

        $exporter_class = $this->getExporterClass($format);

        if (!$exporter_class || !class_exists($exporter_class)) {
            die(json_encode([
                'success' => false,
                'message' => "Unhandled export format `{$format}`",
                'data'    => [],
            ]));
        }

        $settings = $exporter_class::getAvailableExporterSettings();

        die(json_encode([
            'success' => true,
            'data'    => $settings,
        ]));
    }
}
