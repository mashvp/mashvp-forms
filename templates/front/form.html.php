<?php
  use Mashvp\Forms\Form;
  use Mashvp\Forms\Renderer;
  use Mashvp\Forms\Utils;

  $controllers = ['mashvp-forms--form'];
  $actions = [];

  $is_admin_preview = Utils::get_render_global('is_admin_preview', false);

  if ($form->getOption('submission.ajax.enabled')) {
    $controllers[] = 'mashvp-forms--ajax';
    $actions[] = 'submit->mashvp-forms--ajax#handleSubmit';
  }

  if ($form->getOption('antispam.recaptcha.enabled')) {
    $version = get_option('mvpf__antispam-recaptcha--version');

    if ($version === 'v2:invisible') {
      $controllers[] = 'mashvp-forms--recaptcha';
    }
  }

  $controllers = implode(' ', $controllers);
  $actions = implode(' ', $actions);

  // Do not use a <form> tag for admin previews as this breaks WordPress' post saving.
  $html_tag = $is_admin_preview ? 'div' : 'form';

  $klass = ['mvpf', 'mvpf__form'];
  if ($is_admin_preview) {
    $klass[] = 'mvpf__form--preview';
  }
  $klass = implode(' ', $klass);
?>

<<?= $html_tag ?>
  action="<?= admin_url('admin-ajax.php') ?>"
  method="post"
  class="<?= $klass ?>"
  id="mvpf-form--<?= $post->ID ?>"
  data-form-id="<?= $post->ID ?>"
  data-controller="<?= $controllers ?>"
  data-action="<?= $actions ?>"
>
  <?php if (isset($form_data['rows'])): ?>
    <?php foreach ($form_data['rows'] as $row): ?>
      <?php
        Renderer::renderTemplate(
          'front/form/row',
          [
            'row' => $row
          ]
        )
      ?>
    <?php endforeach ?>
  <?php endif ?>

  <?php
    Renderer::renderTemplate(
      'front/form/antispam',
      [
        'form' => $form,
        'post' => $post
      ]
    )
  ?>

  <?php wp_nonce_field('mvpf_form_submit', Form::SECURITY_CODE) ?>
  <input type="hidden" name="action" value="mvpf_form_submit">
  <input type="hidden" name="_mvpf_form_id" value="<?= $post->ID ?>">
</<?= $html_tag ?>>
