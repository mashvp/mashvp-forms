<?php
  use Mashvp\Forms\Form;
  use Mashvp\Forms\Renderer;
?>

<label class="mvpf__form-field--datetime-local <?= Form::get($field, 'attributes.className') ?>" for="<?= Form::getIter($field, 'id') ?>">
  <?php Renderer::renderTemplate('front/form/fields/partials/label', ['field' => $field]) ?>

  <input
    type="<?= Form::get($field, 'attributes.dateTimeType', 'datetime-local') ?>"
    name="<?= Form::get($field, 'id') ?>"
    id="<?= Form::getIter($field, 'id') ?>"
    value="<?= Form::get($field, 'attributes.defaultValue') ?>"
    placeholder="<?= Form::get($field, 'attributes.placeholder') ?>"
    autocomplete="<?= Form::get($field, 'attributes.autocomplete') ?>"
    <?= Form::required($field) ?>
  >
</label>
