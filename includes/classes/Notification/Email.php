<?php

declare(strict_types=1);

namespace Mashvp\Forms\Notifications;

use Mashvp\Forms\Notifications\GenericNotification;
use Mashvp\Forms\Renderer;
use Mashvp\Forms\Submission;

class Email extends GenericNotification
{
  private static function sendNotificationMail($to, $submission, $form)
  {
    $url_parts = parse_url(home_url());
    $domain = $url_parts['host'];
    $site_name = get_bloginfo('name');

    if ($domain === 'localhost') {
      $domain = 'localhost.dev';
    }

    return wp_mail(
      // Recipient
      $to,

      // Subject
      sprintf(
        '%s | %s',
        $submission->getTitle(),
        $site_name,
      ),

      // Content
      Renderer::renderTemplateToString(
        'email/submission-notification',
        [
          'submission' => $submission,
          'form' => $form,
        ],
      ),

      // Headers
      [
        'Content-Type: text/html; charset=UTF-8',
        sprintf('From: %s Notification <noreply@%s>', $site_name, $domain),
      ],
    );
  }

  public static function handle($submission, $form)
  {
    if (!$form->getOption('notifications.email.enabled')) {
      return false;
    }

    $email_settings = json_decode((string) $form->getOption('notifications.email.settings', true));

    if (!$email_settings) {
      $submission->updateMeta(
        Submission::SUBMISSION_MAIL_SENT,
        'error',
      );

      return false;
    }

    $success = true;

    foreach ($email_settings as $setting) {
      if ($setting->condition) {
        $field = $submission->getFieldById($setting->condition->attribute);

        if ($field) {
          $left = $field['raw_value'];
          $right = $setting->condition->value;

          $pass = false;

          switch ($setting->condition->operator) {
            case '==':
              $pass = $left === $right;
              break;
            case '!=':
              $pass = $left !== $right;
              break;
            case 'LIKE':
              $pass = preg_match(sprintf('/^.*%s.*$/', $right), (string) $left);
              break;
          }

          if ($pass) {
            $success = self::sendNotificationMail($setting->email, $submission, $form) && $success;
          }
        }
      } else {
        $success = self::sendNotificationMail($setting->email, $submission, $form) && $success;
      }
    }

    $submission->updateMeta(
      Submission::SUBMISSION_MAIL_SENT,
      $success ? 'success' : 'error',
    );

    return $success;
  }
}
