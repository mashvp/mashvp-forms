<?php use Mashvp\Forms\Form ?>

<p class="label">
  <span class="label-value"><?= Form::get($field, 'attributes.label') ?></span>

  <?php if (Form::get($field, 'attributes.required')): ?>
    <span
      class="label-required"
      aria-label="<?= _x('Required', 'ARIA label', 'mashvp-forms') ?>"
      role="presentation"
    >*</span>
  <?php endif ?>
</p>
