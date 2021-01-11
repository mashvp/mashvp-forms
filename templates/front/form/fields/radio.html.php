<?php use Mashvp\Forms\Form ?>

<label class="mvpf__form-field--radio <?= Form::get($field, 'attributes.className') ?>" for="<?= Form::get($field, 'id') ?>">
  <div class="mvpf__form-field--radio-wrapper">
    <input
      type="radio"
      name="<?= Form::get($field, 'id') ?>"
      id="<?= Form::get($field, 'id') ?>"
      value="<?= Form::get($field, 'attributes.defaultValue') ?>"
      <?= Form::required($field) ?>
    >
  </div>

  <span class="mvpf__form-field--radio-label"><?= Form::get($field, 'attributes.label') ?></span>
</label>
