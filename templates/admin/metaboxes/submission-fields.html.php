<?php use Mashvp\Forms\Submission ?>

<dl class="mvpf mvpf__submission-list">
  <?php foreach ($fields as $field): ?>
    <?php if (!in_array($field['type'], ['submit', 'reset', 'button'])): ?>
      <dt><div class="label"><?= $field['label'] ?></div></dt>
      <?= Submission::renderField($field, $post->post_parent) ?>
    <?php endif ?>
  <?php endforeach ?>
</dl>
