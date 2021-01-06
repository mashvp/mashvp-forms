<?php

use Mashvp\Forms\Form;
use Mashvp\Forms\Renderer;

$recaptcha_version = get_option('mvpf__antispam-recaptcha--version');

if ($recaptcha_version) {
  switch ($recaptcha_version) {
    case 'v2:invisible':
      Renderer::instance()->renderTemplate(
        'front/form/antispam/recaptcha/v2-invisible',
        ['form' => $form, 'post' => $post]
      );

      break;

    case 'v2:checkbox':
      Renderer::instance()->renderTemplate(
        'front/form/antispam/recaptcha/v2-checkbox',
        ['form' => $form, 'post' => $post]
      );

      break;
  }
}
