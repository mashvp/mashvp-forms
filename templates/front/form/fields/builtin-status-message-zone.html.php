<?php use Mashvp\Forms\Form; ?>

<label class="mvpf__form-field--status-message-zone <?= Form::get($field, 'attributes.className') ?>">
  <p
    class="message"
    data-mashvp-forms--ajax-target="statusMessage"
    data-default-success-message="<?= Form::get($field, 'attributes.successMessage') ?>"
  ></p>
</label>
