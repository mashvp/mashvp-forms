<?php

namespace Mashvp\Forms;

use Mashvp\Forms\Utils;

class Form
{
    public const FORM_FIELDS_META_NAME = '_mashvp-forms__fields';
    public const FORM_OPTIONS_META_NAME = '_mashvp-forms__options';

    public const SECURITY_CODE = 'AGs9jXb8dNHMEhh8CxJs6srBDqm5HH9p';

    private $id;
    private $options;
    private $post;

    public function __construct($form_id, $options = [])
    {
        $this->id = $form_id;
        $this->options = $options;

        $post = get_post($form_id);
        if (get_post_type($post) === 'mvpf-form') {
            $this->post = $post;
        }
    }

    public function getPost()
    {
        return $this->post;
    }

    public function getID()
    {
        return $this->id;
    }

    public function getTitle($submission_title = null)
    {
        $title = $this->post->post_title;

        if (!empty($submission_title)) {
            return $title . ' — ' . $submission_title;
        }

        return $title;
    }

    public function getAdminPermalink()
    {
        return get_edit_post_link($this->post);
    }

    public function getFormData()
    {
        if (!$this->post) {
            return null;
        }

        $raw_form_data = get_post_meta($this->post->ID, self::FORM_FIELDS_META_NAME, true);

        return json_decode($raw_form_data, true) ?? ['rows' => []];
    }

    public function getAllOptions()
    {
        if (!$this->post) {
            return null;
        }

        return get_post_meta($this->post->ID, self::FORM_OPTIONS_META_NAME, true);
        ;
    }

    public function getOption($name, $raw = false)
    {
        if ($raw) {
            return self::getRaw($this->getAllOptions(), $name);
        }

        return self::get($this->getAllOptions(), $name);
    }

    public function render()
    {
        if (!$this->post) {
            return <<<HTML
                <!-- [mashvp-form] No form with id {$this->id} was found -->
            HTML;
        }

        $form_data = $this->getFormData();

        Renderer::instance()->renderTemplate(
            'front/form',
            [
                'post' => $this->post,
                'form_data' => $form_data,
                'form' => $this,
                'is_admin_preview' => Utils::get($this->options, 'is_admin_preview'),
            ]
        );
    }

    public function getFields()
    {
        if (!$this->post) {
            return null;
        }

        $fields = [];
        $form_data = $this->getFormData();

        if (isset($form_data) && isset($form_data['rows'])) {
            foreach ($form_data['rows'] as $row) {
                if (isset($row['items'])) {
                    foreach ($row['items'] as $field) {
                        if (self::get($field, 'type') === 'group') {
                            if (isset($field['children'])) {
                                foreach ($field['children'] as $child) {
                                    $fields[] = $child;
                                }
                            }
                        } else {
                            $fields[] = $field;
                        }
                    }
                }
            }
        }

        return $fields;
    }

    public function getFieldByID($id)
    {
        $filtered = array_filter($this->getFields(), function ($field) use ($id) {
            return $field['id'] === $id;
        });

        if (!empty($filtered)) {
            return array_values($filtered)[0];
        }

        return null;
    }

    public function deleteAllMeta()
    {
        delete_post_meta($this->id, self::FORM_FIELDS_META_NAME);
        delete_post_meta($this->id, self::FORM_OPTIONS_META_NAME);
    }

    public static function getRaw($object, $properties, $default = '')
    {
        $properties = explode('.', $properties);

        if (!empty($properties)) {
            $prop = array_shift($properties);

            if (isset($object)) {
                $value = $default;

                if (is_array($object) && isset($object[$prop])) {
                    $value = $object[$prop];
                } elseif (is_object($object) && isset($object->$prop)) {
                    $value = $object->$prop;
                } else {
                    return $default;
                }

                if (!empty($properties)) {
                    return self::getRaw(
                        $value,
                        implode('.', $properties),
                        $default
                    );
                }

                return $value;
            }
        }

        return $default;
    }

    public static function get($object, $properties, $default = '')
    {
        return esc_html(self::getRaw($object, $properties, $default));
    }

    public static function required($field)
    {
        if (self::get($field, 'attributes.required')) {
            return 'required="required"';
        }

        return '';
    }

    public static function getAllForms()
    {
        $query = new \WP_Query([
            'post_type'      => 'mvpf-form',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
        ]);

        return array_map(function($form_id) {
            return new Form($form_id);
        }, wp_list_pluck($query->posts, 'ID'));
    }
}
