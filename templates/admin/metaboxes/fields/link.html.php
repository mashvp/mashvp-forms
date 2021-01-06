<?php
  $title = isset($title) ? $title : $field['value'];
  $url = isset($url) ? $url : $field['value'];
?>

<dd data-type="<?= $field['type'] ?>">
  <a
    href="<?= $url ?>"
    target="_blank"
    rel="noopener noreferrer"
  ><?= $title ?></a>
</dd>
