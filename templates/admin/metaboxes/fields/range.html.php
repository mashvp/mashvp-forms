<?php
  use Mashvp\Forms\Form;

  $value = Form::get($field, 'value.value');
  $min = Form::get($field, 'value.min');
  $max = Form::get($field, 'value.max');
  $step = Form::get($field, 'value.step');

  $percent = (floatval($value) - floatval($min)) / (floatval($max) - floatval($min)) * 100;
?>

<dd data-type="<?= $field['type'] ?>">
  <div class="value-range">
    <span class="current-value" style="left: <?= $percent ?>%; transform: translateX(-<?= $percent ?>%)">
      <?= $value ?>
    </span>
  </div>

  <input
    type="range"
    readonly

    value="<?= $value ?>"
    min="<?= $min ?>"
    max="<?= $max ?>"
    step="<?= $step ?>"

    onclick="return false"
    style="pointer-events: none"
  >

  <div class="value-range">
    <span class="min"><?= $min ?></span>
    <span class="max"><?= $max ?></span>
  </div>
</dd>
