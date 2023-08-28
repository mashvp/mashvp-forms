<?php
  use Mashvp\Forms\Form;
  use Mashvp\Forms\Renderer;
?>

<label class="mvpf__form-field--range <?= Form::get($field, 'attributes.className') ?>" for="<?= Form::getIter($field, 'id') ?>">
  <?php Renderer::renderTemplate('front/form/fields/partials/label', ['field' => $field]) ?>

  <input
    type="range"
    name="<?= Form::get($field, 'id') ?>[value]"
    id="<?= Form::getIter($field, 'id') ?>"
    value="<?= Form::get($field, 'attributes.defaultValue') ?>"
    min="<?= Form::get($field, 'attributes.min') ?>"
    max="<?= Form::get($field, 'attributes.max') ?>"
    step="<?= Form::get($field, 'attributes.step') ?>"
    placeholder="<?= Form::get($field, 'attributes.placeholder') ?>"
    autocomplete="<?= Form::get($field, 'attributes.autocomplete') ?>"
    <?= Form::required($field) ?>
  >

  <input type="hidden" name="<?= Form::get($field, 'id') ?>[min]" value="<?= Form::get($field, 'attributes.min') ?>">
  <input type="hidden" name="<?= Form::get($field, 'id') ?>[max]" value="<?= Form::get($field, 'attributes.max') ?>">
  <input type="hidden" name="<?= Form::get($field, 'id') ?>[step]" value="<?= Form::get($field, 'attributes.step') ?>">
</label>
