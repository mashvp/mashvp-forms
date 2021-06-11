<?php
  use Mashvp\Forms\Form;
  use Mashvp\Forms\Renderer;
?>

<div
  class="mvpf mvpf__form-field <?= Form::get($field, 'attributes.className') ?>"
  data-field-id=<?= Form::get($field, 'id') ?>
>
  <?php
    $type = Form::get($field, 'attributes.type');

    Renderer::renderTemplate("front/form/fields/$type", ['field' => $field]);
  ?>
</div>
