<div class="mashvp-forms form-fields--wrapper">
  <div class="stage-wrapper" data-controller="form--stage">
    <div class="stage--root" data-form--stage-target="root"></div>

    <input
      type="hidden"
      name="_mashvp-forms__fields"
      id="_mashvp-forms__fields"
      data-form--stage-target="output"
      value="<?= esc_html($form_fields_json) ?>"
      readOnly
    >

    <?php wp_nonce_field('update_mashvp-forms__fields', 'fields_nonce') ?>
  </div>
</div>
