<?php

require 'autoloader.php';

use Mashvp\Forms\PostTypes;
use Mashvp\Forms\Admin;
use Mashvp\Forms\Front;
use Mashvp\Forms\ShortCode;

use Mashvp\Forms\Form;
use Mashvp\Forms\Submission;
use Mashvp\Forms\SubmissionHandler;

use Mashvp\Forms\Notifications\Email;

ShortCode::instance()->register();

add_action('init', 'mashvp_forms__register_plugin__init', 5);
function mashvp_forms__register_plugin__init()
{
    PostTypes::instance()->register();

    Front::instance()->register();
    SubmissionHandler::instance()->registerFormHandler();
}

add_action('wp_enqueue_scripts', 'mashvp_forms__register_plugin__front_enqueue_scripts', 5);
function mashvp_forms__register_plugin__front_enqueue_scripts()
{
    Front::instance()->enqueueScripts();
}

add_action('admin_init', 'mashvp_forms__register_plugin__admin_init', 5);
function mashvp_forms__register_plugin__admin_init()
{
    Admin::instance()->register();
}

add_action('admin_menu', 'mashvp_forms__register_plugin__admin_menu', 5);
function mashvp_forms__register_plugin__admin_menu()
{
    Admin::instance()->registerAdminMenu();
}

add_action('admin_enqueue_scripts', 'mashvp_forms__register_plugin__admin_enqueue_scripts', 5);
function mashvp_forms__register_plugin__admin_enqueue_scripts()
{
    Admin::instance()->enqueueScripts();
}

add_action('plugins_loaded', 'mashvp_forms__load_textdomain');
function mashvp_forms__load_textdomain()
{
    load_plugin_textdomain('mashvp-forms', false, MASHVP_FORMS__DIR . '/languages/');
}

add_action('save_post_mvpf-form', 'mashvp_forms__save_post');
function mashvp_forms__save_post($post_id)
{
    Admin::instance()->savePostData($post_id);
}

add_action('mvpf__submission_created', [Email::class, 'handle'], 10, 2);
