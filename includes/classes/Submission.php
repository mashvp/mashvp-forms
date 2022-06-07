<?php

namespace Mashvp\Forms;

use Mashvp\Forms\Form;
use Mashvp\Forms\Utils;

class Submission
{
    public const FORM_FIELDS_META_NAME = '_mashvp-forms__fields';
    public const SUBMISSION_MAIL_SENT = '_mashvp-forms__mail-sent';

    private $form;
    private $fields;

    private $id;
    private $post;
    private $title;

    public function __construct($form_or_submission_id, $fields = null)
    {
        if (isset($fields)) {
            $this->form = $form_or_submission_id;
            $this->fields = $fields;
        } else {
            if (get_post_type($form_or_submission_id) === 'mvpf-submission') {
                $this->id = $form_or_submission_id;
                $this->post = get_post($form_or_submission_id);
            }
        }
    }

    public function getPost()
    {
        return $this->post;
    }

    private function getSubmissionCount()
    {
        $count = 0;

        foreach (wp_count_posts('mvpf-submission', '') as $key => $value) {
            $count += intval($value);
        }

        return $count;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getAdminPermalink()
    {
        return get_edit_post_link($this->id);
    }

    public function getFields()
    {
        if ($this->fields) {
            return $this->fields;
        }

        $fields = get_post_meta($this->id, self::FORM_FIELDS_META_NAME, true);
        $this->fields = $fields;

        return $fields;
    }

    public function getPrintableFields()
    {
        return array_filter($this->getFields(), function ($field) {
            return !in_array(Utils::get($field, 'type'), [
                'submit',
                'reset',
                'button',
                'message',
                'horizontal-separator',
                'builtin-status-message-zone'
            ]);
        });
    }

    public function getFieldById($id)
    {
        $results = array_values(
            array_filter(
                $this->getFields(),
                function ($field) use ($id) {
                    return $field['id'] === $id;
                }
            )
        );

        if (isset($results[0])) {
            return $results[0];
        }

        return null;
    }

    public function createAndRunHooks()
    {
        $post_count = $this->getSubmissionCount() + 1;
        $title = $this->form->getTitle(
            sprintf(
                "%s #$post_count",
                _x('Form submission', 'Post type UI', 'mashvp-forms')
            )
        );

        $this->title = $title;

        $this->id = wp_insert_post([
            'post_type' => 'mvpf-submission',
            'post_title' => $title,
            'post_status' => 'publish',
            'comment_status' => 'closed',
            'post_parent' => $this->form->getID(),
        ]);

        if ($this->id === 0 || is_wp_error($this->id)) {
            error_log("[mashvp-forms] Cannot create submission ($title)");
            $this->id = 0;

            return false;
        }

        $this->updateMeta(self::FORM_FIELDS_META_NAME, $this->fields);

        do_action('mvpf__submission_created', $this, $this->form);

        return true;
    }

    public function updateMeta($name, $value)
    {
        if ($this->id) {
            update_post_meta($this->id, $name, $value);
        }
    }

    public function getMeta($name, $single = true)
    {
        if ($this->id) {
            return get_post_meta($this->id, $name, $single);
        }

        return null;
    }

    public function deleteAllMeta()
    {
        delete_post_meta($this->id, self::SUBMISSION_MAIL_SENT);
    }

    public function getEmailNotificationStatus()
    {
        $mail_status = $this->getMeta(Submission::SUBMISSION_MAIL_SENT);

        if (!$mail_status) {
            $mail_status = 'indeterminate';
        }

        if ($mail_status === '1') {
            $mail_status = 'success';
        }

        return $mail_status;
    }

    public static function renderField($field, $form_post_id = null, $args = [])
    {
        $args = wp_parse_args($args, [
            'context' => 'admin'
        ]);

        $form = null;

        if ($form_post_id) {
            $form = new Form($form_post_id);
        }

        if ($field) {
            switch (Utils::get($field, 'type')) {
                case 'checkbox':
                    return Renderer::renderTemplateToString(
                        'admin/metaboxes/fields/checkbox',
                        ['field' => $field, 'args' => $args]
                    );
                case 'radio':
                    return Renderer::renderTemplateToString(
                        'admin/metaboxes/fields/radio',
                        ['field' => $field, 'args' => $args]
                    );
                case 'choice-list':
                    return Renderer::renderTemplateToString(
                        'admin/metaboxes/fields/choice-list',
                        ['field' => $field, 'form' => $form, 'args' => $args]
                    );
                case 'url':
                    return Renderer::renderTemplateToString(
                        'admin/metaboxes/fields/link',
                        ['field' => $field, 'args' => $args]
                    );
                case 'email':
                    return Renderer::renderTemplateToString(
                        'admin/metaboxes/fields/link',
                        [
                            'field' => $field,
                            'url'   => sprintf('mailto:%s', $field['value']),
                            'args'  => $args
                        ]
                    );
                case 'tel':
                    return Renderer::renderTemplateToString(
                        'admin/metaboxes/fields/link',
                        [
                            'field' => $field,
                            'url'   => sprintf('tel:%s', $field['value']),
                            'args'  => $args
                        ]
                    );
                case 'range':
                    return Renderer::renderTemplateToString(
                        'admin/metaboxes/fields/range',
                        ['field' => $field, 'args' => $args]
                    );
                default:
                    return Renderer::renderTemplateToString(
                        'admin/metaboxes/fields/generic',
                        ['field' => $field, 'args' => $args]
                    );
            }
        }
    }

    public static function getPrintableFieldValue($field)
    {
        if ($field) {
            $value = Utils::get($field, 'value');

            switch (Utils::get($field, 'type')) {
                case 'checkbox':
                    return (
                        $value ?
                        _x('Yes', 'Checkbox field value', 'mashvp-forms') :
                        _x('No', 'Checkbox field value', 'mashvp-forms')
                    );

                case 'choice-list':
                    $values = array_filter($value, 'is_int', ARRAY_FILTER_USE_KEY);

                    return implode(', ', array_map(function($key) use ($value) {
                        return Utils::get($value, "__label--$key");
                    }, $values));

                case 'range':
                    $current = Utils::get($value, 'value');
                    $max = Utils::get($value, 'max');

                    return sprintf(
                        /* translators: %1$s current range value, %2$s max range value */
                        _x('%1$s/%2$s', 'Range field value', 'mashvp-forms'),
                        $current,
                        $max,
                    );

                default:
                    return $value;
            }
        }
    }
}
