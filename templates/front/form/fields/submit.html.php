<?php use Mashvp\Forms\Form; ?>

<label class="mvpf__form-field--submit <?= Form::get($field, 'attributes.className') ?>" for="<?= Form::getIter($field, 'id') ?>">
  <input
    type="submit"
    name="<?= Form::get($field, 'id') ?>"
    id="<?= Form::getIter($field, 'id') ?>"
    value="<?= Form::get($field, 'attributes.value') ?>"
  >
</label>
