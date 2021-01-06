<?php use Mashvp\Forms\Form ?>

<div id="mashvp-forms--options" data-controller="form--options">
  <fieldset>
    <legend><?= _x('Submission notifications', 'Form options section', 'mashvp-forms') ?></legend>

    <div class="field" data-controller="form--option-field">
      <label class="row">
        <input
          type="checkbox"
          name="mvpf_options--notification__email--enabled"
          data-target="form--option-field.toggle"
          data-action="input->form--option-field#toggleAccordion"

          <?php if (Form::get($form_options, 'notifications.email.enabled')): ?>
            checked
          <?php endif ?>
        >

        <span class="label"><?= _x('Email', 'Form options', 'mashvp-forms') ?></span>
      </label>

      <div
        class="row expand"
        data-controller="form--email-settings"
        data-target="form--option-field.accordion"
        data-form--email-settings-initial-data="<?= Form::get($form_options, 'notifications.email.settings') ?>"
      >
        <input type="hidden" name="mvpf_options--notification__email--values" data-target="form--email-settings.output">

        <div class="row--inner">
          <ul class="emails" data-target="form--email-settings.list"></ul>

          <div class="actions">
            <button type="button" class="button" data-action="form--email-settings#add">
              <?= _x('Add email', 'Form settings email', 'mashvp-forms') ?>
            </button>
          </div>
        </div>
      </div>
    </div>
  </fieldset>

  <fieldset>
    <legend><?= _x('Spam protection', 'Form options section', 'mashvp-forms') ?></legend>

    <label class="field" data-controller="form--option-field">
      <div class="row">
        <input
          type="checkbox"
          name="mvpf_options--antispam_honeypot--enabled"
          data-target="form--option-field.toggle"
          data-action="input->form--option-field#toggleAccordion"

          <?php if (Form::get($form_options, 'antispam.honeypot.enabled')): ?>
            checked
          <?php endif ?>
        >

        <span class="label"><?= _x('Honeypot', 'Form options', 'mashvp-forms') ?></span>
      </div>
    </label>

    <label class="field" data-controller="form--option-field">
      <div class="row">
        <input
          type="checkbox"
          name="mvpf_options--antispam_recaptcha--enabled"
          data-target="form--option-field.toggle"
          data-action="input->form--option-field#toggleAccordion"

          <?php if (Form::get($form_options, 'antispam.recaptcha.enabled')): ?>
            checked
          <?php endif ?>
        >

        <span class="label"><?= _x('reCAPTCHA', 'Form options', 'mashvp-forms') ?></span>
      </div>
    </label>
  </fieldset>

  <textarea
    style="
      display: none;

      margin-top: 40px;
      width: 100%;
      font-family: monospace;
      resize: none;
    "
    readonly
  ><?= json_encode($form_options) ?></textarea>

  <?php wp_nonce_field('update_mashvp-forms__options', 'options_nonce') ?>
</div>
