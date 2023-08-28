<?php

  use Mashvp\Forms\Form;
  use Mashvp\Forms\Utils;

  $hidden_data = Utils::get_render_global('hidden_data');
  $hidden_value = Utils::get($hidden_data, Form::get($field, 'attributes.id'));

  $value = $hidden_value;
  if (!$value) {
    $value = Form::get(
      $field,
      'attributes.value',
      Form::get(
        $field,
        'attributes.defaultValue',
        ''
      )
    );
  }

?>

<label class="mvpf__form-field--hidden <?= Form::get($field, 'attributes.className') ?>">
  <input
    type="hidden"
    name="<?= Form::get($field, 'id') ?>"
    id="<?= Form::getIter($field, 'id') ?>"
    data-id="<?= Form::get($field, 'attributes.id') ?>"
    value="<?= esc_attr($value) ?>"
  >
</label>
