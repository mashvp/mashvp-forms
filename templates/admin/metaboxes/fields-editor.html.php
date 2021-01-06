<div class="mashvp-forms form-fields--wrapper">
  <div class="stage-wrapper" data-controller="form--stage">
    <div class="stage--root" data-target="form--stage.root"></div>

    <input
      type="hidden"
      name="_mashvp-forms__fields"
      id="_mashvp-forms__fields"
      data-target="form--stage.output"
      value="<?= esc_html($form_fields_json) ?>"
      readOnly
    >

    <?php wp_nonce_field('update_mashvp-forms__fields', 'fields_nonce') ?>
  </div>
</div>
