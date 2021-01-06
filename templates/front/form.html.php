<?php
  use Mashvp\Forms\Form;
  use Mashvp\Forms\Renderer;
?>

<form
  action="<?= admin_url('admin-ajax.php') ?>"
  method="post"
  class="mvpf mvpf__form"
  id="mvpf-form--<?= $post->ID ?>"
  data-form-id="<?= $post->ID ?>"
>
  <?php if (isset($form_data['rows'])): ?>
    <?php foreach ($form_data['rows'] as $row): ?>
      <?php Renderer::renderTemplate('front/form/row', ['row' => $row]) ?>
    <?php endforeach ?>
  <?php endif ?>

  <?php Renderer::renderTemplate('front/form/antispam', ['form' => $form, 'post' => $post]) ?>

  <?php wp_nonce_field('mvpf_form_submit', Form::SECURITY_CODE) ?>
  <input type="hidden" name="action" value="mvpf_form_submit">
  <input type="hidden" name="_mvpf_form_id" value="<?= $post->ID ?>">
</form>
