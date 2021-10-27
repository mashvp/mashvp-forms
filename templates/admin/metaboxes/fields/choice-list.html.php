<?php
  use Mashvp\Forms\Form;
  use Mashvp\Forms\Submission;

  $values = Form::getRaw($field, 'value');

  $options = array_filter(
    $values,
    function($key) {
      return preg_match("/^__label--.*$/", $key);
    },
    ARRAY_FILTER_USE_KEY
  );

  $type = Form::get($field, 'value.__type');
  $context = Form::get($args, 'context');
?>

<?php if ($context === 'email'): ?>

  <table class="choice-list">
    <?php foreach ($options as $value => $label): ?>
      <?php
        $value_name = preg_replace("/^__label--/", '', $value);

        $selected_value = $field['value'];
        $selected_value = $selected_value ?? [];

        $selected = in_array($value_name, $selected_value);
      ?>

      <tr>
        <td>
          <?=
            Submission::renderField(
              [
                'id' => $field['id'],
                'type' => $type,
                'label' => 'PLACEHOLDER',
                'value' => $selected,
                'raw_value' => $selected
              ]
            )
          ?>
        </td>

        <th><?= esc_html($label) ?></th>
      </tr>
    <?php endforeach ?>
  </table>

<?php else: ?>

  <dd class="with-sub-dl" data-type="choice-list">
    <dl class="sub-dl">
      <?php foreach ($options as $value => $label): ?>
        <?php
          $value_name = preg_replace("/^__label--/", '', $value);

          $selected_value = $field['value'];
          $selected_value = $selected_value ?? [];

          $selected = in_array($value_name, $selected_value);
        ?>

        <dt><?= esc_html($label) ?></dt>

        <?=
          Submission::renderField(
            [
              'id' => $field['id'],
              'type' => $type,
              'label' => 'PLACEHOLDER',
              'value' => $selected,
              'raw_value' => $selected
            ]
          )
        ?>
      <?php endforeach ?>
    </dl>
  </dd>

<?php endif ?>
