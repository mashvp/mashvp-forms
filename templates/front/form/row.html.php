<?php use Mashvp\Forms\Renderer; ?>

<?php
  $fields_count = 0;

  if ($row['items']) {
    $fields_count = count($row['items']);
  }
?>

<div class="mvpf mvpf__form-row" data-row-id="<?= $row['id'] ?>" data-fields-count="<?= $fields_count ?>">
  <?php if (isset($row['items'])): ?>
    <?php foreach ($row['items'] as $field): ?>
      <?php Renderer::renderTemplate('front/form/field', ['field' => $field]) ?>
    <?php endforeach ?>
  <?php endif ?>
</div>
