<?php

declare(strict_types=1);

namespace Mashvp\Forms;

use Mashvp\Forms\Utils;

class Form
{
  public const FORM_FIELDS_META_NAME = '_mashvp-forms__fields';

  public const FORM_OPTIONS_META_NAME = '_mashvp-forms__options';

  public const SECURITY_CODE = 'AGs9jXb8dNHMEhh8CxJs6srBDqm5HH9p';

  private $post;

  public function __construct(private $id, private $options = [])
  {
    $post = get_post($this->id);
    if (get_post_type($post) === 'mvpf-form') {
      $this->post = $post;
    }
  }

  public function exists(): bool
  {
    return $this->post !== null;
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

  public function render(): ?string
  {
    if (!$this->post) {
      return sprintf('<!-- [mashvp-form] No form with id %s was found -->', $this->id);
    }

    $form_data = $this->getFormData();
    $iteration = Utils::get($GLOBALS, '__mvpf_form_iteration', 1);

    Renderer::renderTemplate(
      'front/form',
      [
        'post' => $this->post,
        'form_data' => $form_data,
        'form' => $this,
        'form_attributes' => Utils::get($this->options, 'form_attributes', []),
      ],
      [
        'form_iteration' => $iteration,
        'is_admin_preview' => Utils::get($this->options, 'is_admin_preview'),
        'hidden_data' => Utils::get($this->options, 'hidden_data', []),
      ],
    );

    $GLOBALS['__mvpf_form_iteration'] = $iteration + 1;
    return null;
  }

  public function getFields(): ?array
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
    $filtered = array_filter($this->getFields(), fn (array $field): bool => $field['id'] === $id);

    if ($filtered !== []) {
      return array_values($filtered)[0];
    }

    return null;
  }

  public function deleteAllMeta(): void
  {
    delete_post_meta($this->id, self::FORM_FIELDS_META_NAME);
    delete_post_meta($this->id, self::FORM_OPTIONS_META_NAME);
  }

  public static function getRaw($object, $properties, $default = '')
  {
    $properties = explode('.', $properties);

    if ($properties !== []) {
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

        if ($properties !== []) {
          return self::getRaw(
            $value,
            implode('.', $properties),
            $default,
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

  public static function getIter($object, $properties, $default = ''): string
  {
    $iter = Utils::get($GLOBALS, '__mvpf_form_iteration', 1);
    $value = static::get($object, $properties, $default);

    return sprintf('%s--%s', $value, $iter);
  }

  public static function required($field): string
  {
    if (self::get($field, 'attributes.required')) {
      return 'required="required"';
    }

    return '';
  }

  public static function getAllForms(): array
  {
    $query = new \WP_Query([
      'post_type' => 'mvpf-form',
      'post_status' => 'publish',
      'posts_per_page' => -1,
    ]);

    return array_map(fn ($form_id): Form => new Form($form_id), wp_list_pluck($query->posts, 'ID'));
  }
}
