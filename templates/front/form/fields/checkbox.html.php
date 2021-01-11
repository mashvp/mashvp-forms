<?php use Mashvp\Forms\Form ?>

<label class="mvpf__form-field--checkbox <?= Form::get($field, 'attributes.className') ?>" for="<?= Form::get($field, 'id') ?>">
  <div class="mvpf__form-field--checkbox-wrapper">
    <input
      type="checkbox"
      name="<?= Form::get($field, 'name', Form::get($field, 'id')) ?>"
      id="<?= Form::get($field, 'id') ?>"
      value="<?= Form::get($field, 'attributes.defaultValue') ?>"
      <?= Form::required($field) ?>
    >
  </div>

  <span class="mvpf__form-field--checkbox-label"><?= Form::get($field, 'attributes.label') ?></span>
</label>
