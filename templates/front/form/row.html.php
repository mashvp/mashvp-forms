<?php use Mashvp\Forms\Renderer ?>

<div class="mvpf mvpf__form-row" data-row-id="<?= $row['id'] ?>">
  <?php if (isset($row['items'])): ?>
    <?php foreach ($row['items'] as $field): ?>
      <?php Renderer::renderTemplate('front/form/field', ['field' => $field]) ?>
    <?php endforeach ?>
  <?php endif ?>
</div>
