<?php
  $ID = $post->ID;
  $callback_name = "mvpf__recaptcha_callback__$ID";

  $hidden = get_option('mvpf__antispam-recaptcha--hide-badge');
?>

<script class="mvpf mvpf__recaptcha mvpf__recaptcha--callback">
  (() => {
    const form = document.getElementById('mvpf-form--<?= $post->ID ?>');
    const buttons = form.querySelectorAll('[type="submit"]');

    Array.from(buttons).forEach(button => {
      button.addEventListener('click', (event) => {
        if (!form.dataset.token) {
          event.preventDefault();
          grecaptcha.execute();
        }
      });
    });

    window.<?= $callback_name ?> = (token) => {
      form.dataset.token = token;

      if (buttons && buttons.length > 0) {
        buttons[buttons.length - 1].click();
      }
    };
  })();
</script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<?php if ($hidden): ?>
  <style class="mvpf mvpf__recaptcha mvpf__recaptcha--styles">
    .grecaptcha-badge {
      visibility: hidden;
    }
  </style>
<?php endif ?>

<span class="mvpf mvpf__form-row mvpf__recaptcha">
  <div class="mvpf mvpf__form-field mvpf__form-field--recaptcha">
    <div
      id="recaptcha"
      class="g-recaptcha"
      data-sitekey="<?= get_option('mvpf__antispam-recaptcha--sitekey') ?>"
      data-callback="<?= $callback_name ?>"
      data-size="invisible"
    ></div>

    <?php if ($hidden): ?>
      <div class="mvpf__form-field--recaptcha-hidden-disclaimer">
        <?=
          sprintf(
            /* translators: %1%s privacy and policy link, %2$s terms of service link */
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
