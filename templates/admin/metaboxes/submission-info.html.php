<?php

use Mashvp\Forms\Submission;

global $wp;

?>


<ul class="notification-statuses">
  <li class="notification email">
    <?php
      $mail_status = $submission->getEmailNotificationStatus();
      $status = "mvpf--{$mail_status}";
      $label = '';

      switch ($mail_status) {
        case 'success':
          $label = _x('Email sent', 'Notification email status', 'mashvp-forms');
        break;
        case 'error':
          $label = _x('Email send error', 'Notification email status', 'mashvp-forms');
        break;
        default:
          $label = _x('Email not sent', 'Notification email status', 'mashvp-forms');
      }
    ?>

    <div class="dashicons dashicons-email <?= $status ?>"></div>
    <span><?= $label ?></span>
  </li>
</ul>