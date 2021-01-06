<div id="poststuff" class="wrap mvpf__general-options">
  <div class="postbox">
    <div class="postbox-header">
      <h1><?= _x('General form settings', 'Menu page title', 'mashvp-forms') ?></h1>
    </div>

    <div class="inside">
      <form action="options.php" method="post">
        <?php settings_fields('mvpf-general-options') ?>
        <?php do_settings_sections('mvpf-general-options') ?>

        <fieldset>
          <legend><?= _x('Spam protection', 'Global settings section', 'mashvp-forms') ?></legend>

          <div class="row">
            <div class="row-label"><?= _x('reCAPTCHA', 'Form options', 'mashvp-forms') ?></div>

            <label class="intrinsic" for="mvpf__antispam-recaptcha--version">
              <span class="label"><?= _x('Version', 'reCAPTCHA', 'mashvp-forms') ?></span>

              <?php $cur = get_option('mvpf__antispam-recaptcha--version') ?>
              <select name="mvpf__antispam-recaptcha--version">
                <option value="v3" disabled>
                  <?= _x('reCAPTCHA v3', 'reCAPTCHA', 'mashvp-forms') ?>
                </option>

                <option value="v2:checkbox" <?= $cur === 'v2:checkbox' ? 'selected' : '' ?>>
                  <?= _x('reCAPTCHA v2 ("I\'m not a robot" Checkbox)', 'reCAPTCHA', 'mashvp-forms') ?>
                </option>

                <option value="v2:invisible" <?= $cur === 'v2:invisible' ? 'selected' : '' ?>>
                  <?= _x('reCAPTCHA v2 (Invisible reCAPTCHA badge)', 'reCAPTCHA', 'mashvp-forms') ?>
                </option>
              </select>
            </label>

            <label class="intrinsic horizontal" for="mvpf__antispam-recaptcha--hide-badge">
              <span class="label"><?= _x('Hide badge? (for v2 invisible only)', 'reCAPTCHA', 'mashvp-forms') ?></span>

              <?php $cur = get_option('mvpf__antispam-recaptcha--hide-badge') ?>
              <input
                type="checkbox"
                name="mvpf__antispam-recaptcha--hide-badge"
                id="mvpf__antispam-recaptcha--hide-badge"
                <?= $cur ? 'checked' : '' ?>
              >
            </label>
          </div>

          <div class="row">

            <label for="mvpf__antispam-recaptcha--sitekey">
              <span class="label"><?= _x('Site key', 'reCAPTCHA', 'mashvp-forms') ?></span>
              <input
                type="text"
                class="monospace"
                name="mvpf__antispam-recaptcha--sitekey"
                value="<?= esc_attr(get_option('mvpf__antispam-recaptcha--sitekey')) ?>"
              />
            </label>

            <label for="mvpf__antispam-recaptcha--secretkey">
              <span class="label"><?= _x('Secret key', 'reCAPTCHA', 'mashvp-forms') ?></span>
              <input
                type="text"
                class="monospace"
                name="mvpf__antispam-recaptcha--secretkey"
                value="<?= esc_attr(get_option('mvpf__antispam-recaptcha--secretkey')) ?>"
              />
            </label>
          </div>

          <div class="row comment">
            <p><?= __('Be careful to select the correct version for your keys, otherwise reCAPTCHA will not function properly.', 'mashvp-forms') ?></p>&nbsp;
            <a href="https://www.google.com/recaptcha/admin/" target="_blank" rel="noopener noreferrer">
              <?= _x('Get a reCAPTCHA key', 'Global settings reCAPTCHA help', 'mashvp-forms') ?>
            </a>
          </div>
        </fieldset>

        <?php submit_button() ?>
      </form>
    </div>
  </div>
</div>
