<div class="mashvp-forms shortcode-input">
  <input
    type="text"
    value="[mashvp-form id=<?= $id ?>]"
    data-controller="clipboard"
    data-action="click->clipboard#copy blur->clipboard#removeCopiedStyled"
    readonly
  >
  <span class="dashicons">&#xf481;</span>
</div>
