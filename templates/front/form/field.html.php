<?php
  use Mashvp\Forms\Form;
  use Mashvp\Forms\Renderer;

  $type = Form::get($field, 'attributes.type');
?>

<div
  class="mvpf mvpf__form-field mvpf__form-field-wrapper mvpf__form-field-wrapper--<?= esc_attr($type) ?> <?= Form::get($field, 'attributes.className') ?>"
  data-field-id=<?= Form::get($field, 'id') ?>
>
  <?php
    Renderer::renderTemplate("front/form/fields/$type", ['field' => $field]);
  ?>
</div>
