<?php

namespace Mashvp\Forms\Notifications;

use Mashvp\Forms\Notifications\GenericNotification;
use Mashvp\Forms\Form;
use Mashvp\Forms\Renderer;
use Mashvp\Forms\Submission;

class Email extends GenericNotification
{
    private static function sendNotificationMail($to, $submission, $form)
    {
        $site_name = get_bloginfo('name');

        $url_parts = parse_url(home_url());
        $domain = $url_parts['host'];

        if ($domain === 'localhost') {
            $domain = 'localhost.dev';
        }

        $success = wp_mail(
            // Recipient
            $to,

            // Subject
            sprintf(
                '%s | %s',
                $submission->getTitle(),
                get_bloginfo('name')
            ),

            // Content
            Renderer::instance()->renderTemplateToString(
                'email/submission-notification',
                [
                    'submission' => $submission,
                    'form' => $form,
                ]
            ),

            // Headers
            [
                'Content-Type: text/html; charset=UTF-8',
                "From: Mashvp Forms Notification <noreply@{$domain}>"
            ]
        );

        return $success;
    }

    public static function handle($submission, $form)
    {
        if (!$form->getOption('notifications.email.enabled')) {
            return false;
        }

        $email_settings = json_decode($form->getOption('notifications.email.settings', true));

        if (!$email_settings) {
            $submission->updateMeta(
                Submission::SUBMISSION_MAIL_SENT,
                'error'
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
                            $pass = preg_match("/^.*{$right}.*$/", $left);
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
            $success ? 'success' : 'error'
        );

        return $success;
    }
}
