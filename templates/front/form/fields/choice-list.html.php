<?php
  use Mashvp\Forms\Form;
  use Mashvp\Forms\Renderer;

  $id = Form::get($field, 'id');
  $classes = Form::get($field, 'attributes.className');

  $type = Form::get($field, 'attributes.multipleChoice') ? 'checkbox' : 'radio';
  $type_class = "mvpf__form-field--choices-list--$type";
?>

<div class="mvpf__form-field--choices-list <?= $type_class ?> <?= $classes ?>" for="<?= $id ?>">
  <?php Renderer::renderTemplate('front/form/fields/partials/label', ['field' => $field]) ?>

  <div class="mvpf__form-field--choices-wrapper">
    <?php
      $defaultValue = Form::get($field, 'attributes.defaultValue');
      $options = Form::getRaw($field, 'attributes.options');
    ?>

    <?php
      Renderer::renderTemplate(
        'front/form/fields/hidden',
        [
          'field' => [
            'id' => "{$id}[__type]",
            'attributes' => [
              'value' => $type,
            ]
          ]
        ]
      )
    ?>

    <?php if ($options): ?>
      <?php $index = 0 ?>
      <?php foreach ($options as $value => $label): ?>
        <?php
          $defaultValues = explode(',', $defaultValue);
          $defaultValues = array_map('trim', $defaultValues);

          $selected = in_array($value, $defaultValues);
        ?>

        <?php
          Renderer::renderTemplate(
            "front/form/fields/$type",
            [
              'field' => [
                'id' => "{$id}[{$index}]",
                'name' => "{$id}[]",
                'attributes' => [
                  'className' => $classes,
                  'defaultValue' => $value,
                  'label' => $label,

                  /* Always set required to false on checkbox groups as HTML5 validation doesn't work */
                  'required' => $type === 'checkbox' ? false : Form::get($field, 'attributes.required'),
                ]
              ]
            ]
          )
        ?>

        <?php
          Renderer::renderTemplate(
            'front/form/fields/hidden',
            [
              'field' => [
                'id' => "{$id}[__label--{$value}]",
                'attributes' => [
                  'value' => $label,
                ]
              ]
            ]
          )
        ?>

        <?php $index += 1 ?>
      <?php endforeach ?>
    <?php endif ?>
  </div>
</div>
