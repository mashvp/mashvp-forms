<?php

/**
 * Plugin Name: Mashvp — Forms
 * Plugin URI: http://mashvp.com
 * Description: No-bullsh!t form plugin
 * Version: 0.2.1-beta.1
 * Author: Mashvp
 * Author URI: http://mashvp.com
 * Text Domain: mashvp-forms
 * Domain Path: /languages
 */

defined('ABSPATH') or die();

if (defined('MASHVP_FORMS')) {
    error_log('[mashvp-forms] Plugin loaded twice, aborting.');
    return false;
}

define('MASHVP_FORMS', true);
define('MASHVP_FORMS__VERSION', '0.2.1-beta.1');
define('MASHVP_FORMS__DIR', basename(dirname(__FILE__)));
define('MASHVP_FORMS__PATH', plugin_dir_path(__FILE__));

include __DIR__ . '/includes/register.php';
