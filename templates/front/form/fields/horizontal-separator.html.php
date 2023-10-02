<?php
  use Mashvp\Forms\Form;
?>

<label class="mvpf__form-field--horizontal-separator <?= Form::get($field, 'attributes.className') ?>" for="<?= Form::getIter($field, 'id') ?>">
  <hr
    id="<?= Form::getIter($field, 'id') ?>"

    <?php if (Form::get($field, 'attributes.value')): ?>
      data-value="<?= Form::get($field, 'attributes.value') ?>"
    <?php endif ?>
  >
</label>
