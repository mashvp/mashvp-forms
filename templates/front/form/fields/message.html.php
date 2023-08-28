<?php use Mashvp\Forms\Form; ?>

<label class="mvpf__form-field--message <?= Form::get($field, 'attributes.className') ?>" for="<?= Form::getIter($field, 'id') ?>">
  <p class="message"><?= Form::get($field, 'attributes.value', Form::get($field, 'attributes.defaultValue')) ?></p>
</label>
