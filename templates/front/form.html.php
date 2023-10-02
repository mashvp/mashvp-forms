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

  if (
    isset($form_attributes) &&
    array_key_exists('data-controller', $form_attributes) &&
    is_array($form_attributes['data-controller']) &&
    !empty($form_attributes['data-controller'])
  ) {
    $controllers = array_merge($controllers, $form_attributes['data-controller']);
  }

  if (
    isset($form_attributes) &&
    array_key_exists('data-action', $form_attributes) &&
    is_array($form_attributes['data-action']) &&
    !empty($form_attributes['data-action'])
  ) {
    $actions = array_merge($actions, $form_attributes['data-action']);
  }

  $controllers = implode(' ', $controllers);
  $actions = implode(' ', $actions);

  // Do not use a <form> tag for admin previews as this breaks WordPress' post saving.
  $html_tag = $is_admin_preview ? 'div' : 'form';

  $classnames = ['mvpf', 'mvpf__form'];

  if ($is_admin_preview) {
    $classnames[] = 'mvpf__form--preview';
  }

  $classnames = implode(' ', $classnames);
?>

<<?= $html_tag ?>
  action="<?= admin_url('admin-ajax.php') ?>"
  method="post"
  class="<?= $classnames ?>"
  id="mvpf-form--<?= $post->ID ?>"
  data-form-id="<?= $post->ID ?>"
  data-controller="<?= $controllers ?>"
  data-action="<?= $actions ?>"

  <?php
    if (
      isset($form_attributes) &&
      array_key_exists('data', $form_attributes) &&
      is_array($form_attributes['data']) &&
      !empty($form_attributes['data'])
    ):
  ?>
    <?php
      foreach ($form_attributes['data'] as $key => $value) {
        $key = esc_attr($key);
        $value = esc_attr($value);

        echo "data-{$key}=\"{$value}\"";
      }
    ?>
  <?php endif ?>
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
