<?php

namespace Mashvp\Forms;

use Mashvp\SingletonClass;

use Mashvp\Forms\Form;
use Mashvp\Forms\Utils;

class SubmissionHandler extends SingletonClass
{
    public function registerFormHandler()
    {
        add_action('wp_ajax_mvpf_form_submit', [$this, 'handleFormSubmit']);
        add_action('wp_ajax_nopriv_mvpf_form_submit', [$this, 'handleFormSubmit']);
    }

    private function getRawFieldValue($field)
    {
        $id = Form::get($field, 'id');
        $type = Form::get($field, 'attributes.type');

        if ($type === 'checkbox') {
            return Utils::get($_POST, $id) !== null;
        }

        return Utils::get($_POST, $id);
    }

    private function getFieldValue($field)
    {
        $id = Form::get($field, 'id');
        $type = Form::get($field, 'attributes.type');

        if ($type === 'checkbox') {
            return Utils::get($_POST, $id) !== null;
        }

        if ($type === 'select') {
            $value = Utils::get($_POST, $id);
            $options = Form::getRaw($field, 'attributes.options');

            if (isset($options[$value])) {
                return $options[$value];
            }
        }

        return Utils::get($_POST, $id);
    }

    private function redirectToReferer($success, $message = '', $localizedMessage = '')
    {
        $args = [
            'mvpf-submit' => $success ? 'success' : 'error',
            'mvpf-message' => '',
        ];

        if ($localizedMessage) {
            $args['mvpf-message'] = rawurlencode($localizedMessage);
        }

        if (!$success) {
            error_log("[mashvp-forms] Process ended with error: $message");
        }

        $args = array_merge($_GET, $args);

        // Redirect to avoid multiple submissions
        wp_redirect(
            add_query_arg($args, $_POST['_wp_http_referer']),
            303
        );
    }

    private function generateJSONResponse($success, $message = '', $localizedMessage = '')
    {
        if (!$localizedMessage) {
            $localizedMessage = _x(
                'Message sent successfully',
                'Submission default success message',
                'mashvp-forms'
            );
        }

        $data = [
            'success' => !!$success,
            'message' => esc_html($localizedMessage),
            'rawMessage' => $message,
        ];

        if ($message && !$success) {
            error_log("[mashvp-forms] Process ended with error: $message");
        }

        header('Content-Type: application/json;charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Mvpf-Method');

        echo json_encode($data);
    }

    private function terminateSubmissionProcess($success, $message = '', $localizedMessage = '')
    {
        if ($_SERVER['HTTP_X_MVPF_METHOD'] === 'AJAX') {
            $this->generateJSONResponse($success, $message, $localizedMessage);
        } else {
            $this->redirectToReferer($success, $message, $localizedMessage);
        }

        die();
    }

    private function verifyRecaptchaToken($token)
    {
        $remote_addr = new RemoteAddress();
        $remote_ip = $remote_addr->getIpAddress();

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'secret' => get_option('mvpf__antispam-recaptcha--secretkey'),
            'response' => $token,
            'remoteip' => $remote_ip
        ]));

        $headers = ['Content-Type: application/x-www-form-urlencoded'];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            $err = curl_error($ch);

            error_log("[mashvp-forms] Error verifying reCAPTCHA token: $err (from: $remote_ip)");
            return false;
        }

        curl_close($ch);

        $json = json_decode($result, true);
        if (!$json) {
            error_log("[mashvp-forms] reCAPTCHA error: Unable to decode JSON response (from: $remote_ip)");

            return false;
        };

        if (!$json['success'] && is_array($json['error-codes'])) {
            $err = implode(', ', $json['error-codes']);

            error_log("[mashvp-forms] reCAPTCHA error: $err (from: $remote_ip)");
        }

        return $json['success'];
    }

    // https://www.google.com/recaptcha/api/siteverify
    private function isRecaptchaSpam($form)
    {
        $remote_addr = new RemoteAddress();
        $remote_ip = $remote_addr->getIpAddress();

        $token = Utils::get($_POST, 'g-recaptcha-response');

        // reCAPTCHA is enabled but no token was submitted
        if (!$token) {
            error_log("[mashvp-forms] reCAPTCHA error: No token was submitted but validation is enabled (from: $remote_ip)");

            return $this->terminateSubmissionProcess(
                false,
                'Cannot verify reCAPTCHA response, please try again',
                _x(
                    'Cannot verify reCAPTCHA response, please try again',
                    'Submission handler error',
                    'mashvp-forms'
                )
            );
        }

        return !$this->verifyRecaptchaToken($token);
    }

    private function isSpam($form)
    {
        if ($form->getOption('antispam.honeypot.enabled')) {
            $fail = (
                Utils::get($_POST, 'email')    !== '' ||
                Utils::get($_POST, 'name')     !== '' ||
                Utils::get($_POST, 'message')  !== ''
            );

            if ($fail) {
                return 'Honeypot was triggered';
            }
        }

        if ($form->getOption('antispam.recaptcha.enabled')) {
            $fail = $this->isRecaptchaSpam($form);

            if ($fail) {
                return 'reCAPTCHA verification failed';
            }
        }

        return false;
    }

    private function logSpamAttempt($form, $reason = 'unknown')
    {
        $remote_addr = new RemoteAddress();

        $form_name = $form->getTitle();
        $remote_ip = $remote_addr->getIpAddress();

        error_log("[mashvp-forms] Spam attempt detected on form \"$form_name\" (reason: $reason, from: $remote_ip)");
    }

    public function handleFormSubmit()
    {
        if (
            empty($_POST) ||
            !wp_verify_nonce($_POST[Form::SECURITY_CODE], 'mvpf_form_submit')
        ) {
            return $this->terminateSubmissionProcess(
                false,
                'Cannot verify the authenticity of the message',
                _x(
                    'Cannot verify the authenticity of the message',
                    'Submission handler error',
                    'mashvp-forms'
                )
            );
        }

        if (!isset($_POST['_mvpf_form_id'])) {
            return $this->terminateSubmissionProcess(
                false,
                'Missing form ID',
                _x(
                    'Missing form ID',
                    'Submission handler error',
                    'mashvp-forms'
                )
            );
        }

        $form = new Form($_POST['_mvpf_form_id']);
        $fields = $form->getFields();

        if (!isset($fields)) {
            return $this->terminateSubmissionProcess(
                false,
                'Unable to retreive form data',
                _x(
                    'Unable to retreive form data',
                    'Submission handler error',
                    'mashvp-forms'
                )
            );
        }

        if ($reason = $this->isSpam($form)) {
            $this->logSpamAttempt($form, $reason);

            return $this->terminateSubmissionProcess(
                false,
                'Your message was rejected because it looks like spam. If you are a legitimate user, please try again.',
                _x(
                    'Your message was rejected because it looks like spam. If you are a legitimate user, please try again.',
                    'Submission handler error',
                    'mashvp-forms'
                )
            );
        }

        $fields = array_map(function ($field) {
            return [
                'id' => Form::get($field, 'id'),
                'type' => Form::get($field, 'attributes.type'),
                'label' => Form::get($field, 'attributes.label'),
                'value' => $this->getFieldValue($field),
                'raw_value' => $this->getRawFieldValue($field),
            ];
        }, $fields);

        $submission = new Submission($form, $fields);
        $success = $submission->createAndRunHooks();

        $defaultMessage = $success ?
            'Your message was sent successfully.' :
            'There was an error trying to send your message. Please try again later.';

        $defaultLocalizedMessage = $success ?
            _x(
                'Your message was sent successfully.',
                'Message submission status',
                'mashvp-forms'
            ) :
            _x(
                'There was an error trying to send your message. Please try again later.',
                'Message submission status',
                'mashvp-forms'
            );

        return $this->terminateSubmissionProcess(
            $success,
            $defaultMessage,
            $defaultLocalizedMessage
        );
    }
}
