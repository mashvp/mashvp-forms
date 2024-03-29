<?php
// @codingStandardsIgnoreFile
// @codeCoverageIgnoreStart
// this is an autogenerated file - do not edit
spl_autoload_register(
    function($class) {
        static $classes = null;
        if ($classes === null) {
            $classes = array(
                'mashvp\\forms\\admin' => '/classes/Admin.php',
                'mashvp\\forms\\csvexporter' => '/classes/CSVExporter.php',
                'mashvp\\forms\\exporter' => '/classes/inherit/Exporter.php',
                'mashvp\\forms\\form' => '/classes/Form.php',
                'mashvp\\forms\\front' => '/classes/Front.php',
                'mashvp\\forms\\notifications\\email' => '/classes/Notification/Email.php',
                'mashvp\\forms\\notifications\\genericnotification' => '/classes/Notification/GenericNotification.php',
                'mashvp\\forms\\posttypes' => '/classes/PostTypes.php',
                'mashvp\\forms\\remoteaddress' => '/classes/RemoteAddress.php',
                'mashvp\\forms\\renderer' => '/classes/Renderer.php',
                'mashvp\\forms\\shortcode' => '/classes/ShortCode.php',
                'mashvp\\forms\\submission' => '/classes/Submission.php',
                'mashvp\\forms\\submissionhandler' => '/classes/SubmissionHandler.php',
                'mashvp\\forms\\utils' => '/classes/Utils.php',
                'mashvp\\singletonclass' => '/classes/inherit/SingletonClass.php',
                'mashvp\\staticclass' => '/classes/inherit/StaticClass.php'
            );
        }
        $cn = strtolower($class);
        if (isset($classes[$cn])) {
            require __DIR__ . $classes[$cn];
        }
    },
    true,
    false
);
// @codeCoverageIgnoreEnd
