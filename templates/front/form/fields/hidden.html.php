<?php use Mashvp\Forms\Form ?>

<label class="mvpf__form-field--hidden <?= Form::get($field, 'attributes.className') ?>">
  <input
    type="hidden"
    name="<?= Form::get($field, 'id') ?>"
    id="<?= Form::get($field, 'id') ?>"
    value="<?= Form::get($field, 'attributes.value', Form::get($field, 'attributes.defaultValue')) ?>"
  >
</label>
