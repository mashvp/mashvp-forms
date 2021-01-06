<div id="mashvp-forms--field-options" data-controller="form--field-options">
  <p class="field-options--placeholder" data-target="form--field-options.default">
    <?= __('Select a field to edit', 'mashvp-forms') ?>
  </p>

  <div class="field-options hidden" data-target="form--field-options.options">
    <ul class="field-options--list" data-target="form--field-options.fieldsContainer"></ul>

    <div class="field-options--actions">
      <button type="button" class="button" data-action="form--field-options#save">
        <?= __('Apply', 'mashvp-forms') ?>
      </button>
    </div>
  </div>
</div>
