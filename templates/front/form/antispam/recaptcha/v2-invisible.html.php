<?php $hidden = get_option('mvpf__antispam-recaptcha--hide-badge') ?>

<?php /* TODO: Try with enqueue */ ?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<span class="mvpf mvpf__form-row mvpf__recaptcha">
  <div class="mvpf mvpf__form-field mvpf__form-field--recaptcha">
    <div
      id="recaptcha"
      class="g-recaptcha <?= $hidden ? 'hidden' : '' ?>"
      data-sitekey="<?= get_option('mvpf__antispam-recaptcha--sitekey') ?>"
      data-size="invisible"
      data-mashvp-forms--recaptcha-target="recaptcha"
    ></div>

    <?php if ($hidden): ?>
      <div class="mvpf__form-field--recaptcha-hidden-disclaimer">
        <?=
          sprintf(
            /* translators: %1$s privacy and policy link, %2$s terms of service link */
            _x('This site is protected by reCAPTCHA and the Google %1$s and %2$s apply.', 'reCAPTCHA', 'mashvp-forms'),
            sprintf(
              '<a href="https://policies.google.com/privacy" target="_blank" rel="noopener noreferrer">%s</a>',
              _x('Privacy Policy', 'reCAPTCHA', 'mashvp-forms')
            ),
            sprintf(
              '<a href="https://policies.google.com/terms" target="_blank" rel="noopener noreferrer">%s</a>',
              _x('Terms of Service', 'reCAPTCHA', 'mashvp-forms')
            )
          )
        ?>
      </div>
    <?php endif ?>
  </div>
</span>
