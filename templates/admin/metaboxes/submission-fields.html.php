<?php
  use Mashvp\Forms\Submission;

  $skip_fields = [
    'submit',
    'reset',
    'button',
    'message',
    'horizontal-separator',
    'builtin-status-message-zone'
  ];
?>

<dl class="mvpf mvpf__submission-list">
  <?php foreach ($fields as $field): ?>
    <?php if (!in_array($field['type'], $skip_fields)): ?>
      <dt><div class="label"><?= $field['label'] ?></div></dt>
      <?= Submission::renderField($field, $post->post_parent) ?>
    <?php endif ?>
  <?php endforeach ?>
</dl>
