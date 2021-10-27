<?php
  $title = isset($title) ? $title : $field['value'];
  $url = isset($url) ? $url : $field['value'];
?>

<dd data-type="<?= esc_attr($field['type']) ?>">
  <a
    href="<?= esc_attr($url) ?>"
    target="_blank"
    rel="noopener noreferrer"
  ><?= esc_html($title) ?></a>
</dd>
