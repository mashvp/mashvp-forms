<?php use Mashvp\Forms\Form; ?>

<label class="mvpf__form-field--radio <?= Form::get($field, 'attributes.className') ?>" for="<?= Form::getIter($field, 'id') ?>">
  <div class="mvpf__form-field--radio-wrapper">
    <input
      type="radio"
      name="<?= Form::get($field, 'name', Form::get($field, 'id')) ?>"
      id="<?= Form::getIter($field, 'id') ?>"
      value="<?= Form::get($field, 'attributes.defaultValue') ?>"
      <?= Form::required($field) ?>
    >

    <div class="mvpf__ui mvpf__form-field--radio-ui"></div>
  </div>

  <span class="mvpf__form-field--radio-label"><?= Form::get($field, 'attributes.label') ?></span>
</label>
