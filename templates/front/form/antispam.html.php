<?php
  use Mashvp\Forms\Form;
  use Mashvp\Forms\Renderer;
?>

<?php if ($form->getOption('antispam.honeypot.enabled')): ?>
  <?php Renderer::renderTemplate('front/form/antispam/honeypot') ?>
<?php endif ?>

<?php if ($form->getOption('antispam.recaptcha.enabled')): ?>
  <?php Renderer::renderTemplate('front/form/antispam/recaptcha', ['form' => $form, 'post' => $post]) ?>
<?php endif ?>
