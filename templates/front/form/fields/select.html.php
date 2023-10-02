<?php
  use Mashvp\Forms\Form;
  use Mashvp\Forms\Renderer;
?>

<label class="mvpf__form-field--select <?= Form::get($field, 'attributes.className') ?>" for="<?= Form::getIter($field, 'id') ?>">
  <?php Renderer::renderTemplate('front/form/fields/partials/label', ['field' => $field]) ?>

  <?php $defaultValue = Form::get($field, 'attributes.defaultValue') ?>

  <select
    name="<?= Form::get($field, 'id') ?>"
    id="<?= Form::getIter($field, 'id') ?>"
    <?= Form::required($field) ?>
  >
    <?php if (Form::get($field, 'attributes.placeholder')): ?>
      <option value=""
        disabled hidden
        <?= $defaultValue === '' ? 'selected' : '' ?>
      >
        <?php
          /**
           * We can't format the placeholder with spans like the label,
           * since only text nodes are allowed inside options
           */
        ?>

        <?= Form::get($field, 'attributes.placeholder') ?>
        <?php if (Form::get($field, 'attributes.required')): ?>
          *
        <?php endif ?>
      </option>
    <?php endif ?>

    <?php $options = Form::getRaw($field, 'attributes.options') ?>
    <?php if ($options): ?>
      <?php foreach ($options as $value => $label): ?>
        <?php $selected = $defaultValue === $value ? 'selected' : '' ?>

        <option value="<?= $value ?>" <?= $selected ?>>
          <?= $label ?>
        </option>
      <?php endforeach ?>
    <?php endif ?>
  </select>
</label>
