<?php
  use Mashvp\Forms\Form;
  use Mashvp\Forms\Renderer;
?>

<label class="mvpf__form-field--tel <?= Form::get($field, 'attributes.className') ?>" for="<?= Form::get($field, 'id') ?>">
  <?php Renderer::renderTemplate('front/form/fields/partials/label', ['field' => $field]) ?>

  <input
    type="tel"
    name="<?= Form::get($field, 'id') ?>"
    id="<?= Form::get($field, 'id') ?>"
    placeholder="<?= Form::get($field, 'attributes.placeholder') ?>"
    autocomplete="<?= Form::get($field, 'attributes.autocomplete') ?>"
    <?= Form::required($field) ?>
  >
</label>
