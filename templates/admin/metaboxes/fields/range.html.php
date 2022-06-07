<?php
  use Mashvp\Forms\Form;

  $value = Form::get($field, 'value.value');
  $min   = Form::get($field, 'value.min');
  $max   = Form::get($field, 'value.max');
  $step  = Form::get($field, 'value.step');

  $percent = (floatval($value) - floatval($min)) / (floatval($max) - floatval($min)) * 100;
?>

<dd data-type="<?= $field['type'] ?>">
  <div class="value-range">
    <span class="current-value" style="left: <?= $percent ?>%; transform: translateX(-<?= $percent ?>%)">
      <?= esc_html($value) ?>
    </span>
  </div>

  <input
    type="range"
    readonly

    value="<?= esc_attr($value) ?>"
    min="<?= esc_attr($min) ?>"
    max="<?= esc_attr($max) ?>"
    step="<?= esc_attr($step) ?>"

    onclick="return false"
    style="pointer-events: none"
  >

  <div class="value-range">
    <span class="min"><?= esc_html($min) ?></span>
    <span class="max"><?= esc_html($max) ?></span>
  </div>
</dd>
