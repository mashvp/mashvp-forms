<?php
  use Mashvp\Forms\Form;
  use Mashvp\Forms\Renderer;
?>

<label class="mvpf__form-field--group <?= Form::get($field, 'attributes.className') ?>" for="<?= Form::get($field, 'id') ?>">
  <span class="label"><?= Form::get($field, 'attributes.label') ?></span>

  <?php
    $children = Form::get($field, 'attributes.children');

    if ($children && !empty($children)):
  ?>
    <div class="mvpf__form-field--group-wrapper">
      <?php foreach ($children as $child): ?>
        <?php
          $child_type = Form::get($child, 'type');
          $className = Form::get($child, 'attributes.className') ?? '';

          $className = trim('mvpf__form-subfield ' . trim($className));

          $child['attributes']['className'] = $className;

          Renderer::renderTemplate("front/form/fields/$child_type", ['field' => $child]);
        ?>
      <?php endforeach ?>
    </div>
  <?php endif ?>
</label>
