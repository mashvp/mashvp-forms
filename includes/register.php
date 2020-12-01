<?php

require 'autoloader.php';

use Mashvp\Forms\PostTypes;
use Mashvp\Forms\Admin;
use Mashvp\Forms\ShortCode;

ShortCode::instance()->register();

add_action('init', 'mashvp_forms__register_plugin__init', 5);
function mashvp_forms__register_plugin__init()
{
    PostTypes::instance()->register();
}

add_action('admin_init', 'mashvp_forms__register_plugin__admin_init', 5);
function mashvp_forms__register_plugin__admin_init()
{
    Admin::instance()->register();
}

add_action('admin_enqueue_scripts', 'mashvp_forms__register_plugin__admin_enqueue_scripts', 5);
function mashvp_forms__register_plugin__admin_enqueue_scripts()
{
    Admin::instance()->enqueue_scripts();
}

add_action('plugins_loaded', 'mashvp_forms__load_textdomain');
function mashvp_forms__load_textdomain()
{
    load_plugin_textdomain('mashvp-forms', false, MASHVP_FORMS__DIR . '/languages/');
}
