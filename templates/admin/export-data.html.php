<?php

  use Mashvp\Forms\Admin;
  use Mashvp\Forms\Form;

?>

<div id="poststuff" class="wrap mvpf__general-options">
  <div class="postbox">
    <div class="postbox-header">
      <h1><?= _x('Export data', 'Menu page title', 'mashvp-forms') ?></h1>
    </div>

    <div class="inside">
      <form
        action="<?= admin_url('admin-ajax.php') ?>"
        method="GET"
        data-controller="exporter-settings"
      >
        <table class="form-table">
          <tbody data-exporter-settings-target="container">
            <tr>
              <th>
                <label for="form_id">
                  <?= esc_html_x('Form', 'Post type UI', 'mashvp-forms') ?>
                </label>
              </th>

              <td>
                <select name="form_id" id="form_id" required>
                  <option value="" selected disabled>
                    <?= __('Select a form', 'mashvp-forms') ?>
                  </option>

                  <?php foreach (Form::getAllForms() as $form): ?>
                    <option value="<?= esc_attr($form->getID()) ?>">
                      <span class="name"><?= esc_html($form->getTitle()) ?></span>
                      <span class="id">(#<?= esc_html($form->getID()) ?>)</span>
                    </option>
                  <?php endforeach ?>
                </select>
              </td>
            </tr>

            <tr>
              <th>
                <label for="export_format">
                  <?= esc_html__('Export format', 'mashvp-forms') ?>
                </label>
              </th>

              <td>
                <select
                  name="export_format"
                  id="export_format"
                  required

                  data-exporter-settings-target="formatSelect"
                  data-action="exporter-settings#updateTemplate"
                >
                  <option value="" selected disabled>
                    <?= __('Select an export format', 'mashvp-forms') ?>
                  </option>

                  <option value="csv">CSV</option>
                </select>
              </td>
            </tr>
          </tbody>
        </table>

        <p class="submit">
          <button type="submit" class="button button-primary button-large">
            <span><?= esc_html_x('Export form submissions', 'Action', 'mashvp-forms') ?></span>
          </button>
        </p>

        <input
          type="hidden"
          name="export_type"
          value="<?=
            sanitize_title(
              strtolower(
                _x('Form submissions', 'Post type UI', 'mashvp-forms')
              )
            )
          ?>"
        >

        <?php wp_nonce_field('mvpfadmin__export_data', Admin::SECURITY_CODE) ?>
        <input type="hidden" name="action" value="mvpfadmin__export_data">
      </form>
    </div>
  </div>
</div>
