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
      <form action="<?= admin_url('admin-ajax.php') ?>" method="GET">
        <label for="form_id">
          <select name="form_id" id="form_id">
            <option value="" selected disabled>
              <?= __('Select a form', 'mashvp-forms') ?>
            </option>

            <?php foreach (Form::getAllForms() as $form): ?>
              <option value="<?= $form->getID() ?>">
                <span class="name"><?= $form->getTitle() ?></span>
                <span class="id">(#<?= $form->getID() ?>)</span>
              </option>
            <?php endforeach ?>
          </select>
        </label>

        <label for="export_format">
          <select name="export_format" id="export_format">
            <option value="" selected disabled>
              <?= __('Select an export format', 'mashvp-forms') ?>
            </option>

            <option value="csv">CSV</option>
          </select>
        </label>

        <button type="submit">Export</button>

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
