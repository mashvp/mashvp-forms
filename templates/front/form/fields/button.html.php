<?php

use Mashvp\Forms\Form;

$type = Form::get($field, 'attributes.htmlType');
$klass = ["mvpf__form-field--button-type-$type", Form::get($field, 'attributes.className')];

$klass = implode(' ', array_filter($klass));

?>

<label class="mvpf__form-field--button <?= $klass ?>" for="<?= Form::get($field, 'id') ?>">
  <button
    type="<?= $type ?>"
    name="<?= Form::get($field, 'id') ?>"
    id="<?= Form::get($field, 'id') ?>"
  ><?= Form::get($field, 'attributes.value') ?></button>
</label>
