<?php

namespace Mashvp\Forms;

use Mashvp\SingletonClass;
use Mashvp\Forms\Renderer;
use Mashvp\Forms\Submission;

class PostTypes extends SingletonClass
{
    public function register()
    {
        $this->registerFormPostType();
        $this->registerSubmissionPostType();
    }

    private function registerFormPostType()
    {
        register_post_type(
            'mvpf-form',
            [
                'label'  => _x('Forms', 'Post type UI', 'mashvp-forms'),
                'labels' => [
                    'name'               => _x('Forms', 'Post type UI', 'mashvp-forms'),
                    'singular_name'      => _x('Form', 'Post type UI', 'mashvp-forms'),
                    'all_items'          => _x('All forms', 'Post type UI', 'mashvp-forms'),
                    'add_new_item'       => _x('Add new', 'Post type UI', 'mashvp-forms'),
                    'edit_item'          => _x('Edit form', 'Post type UI', 'mashvp-forms'),
                    'new_item'           => _x('New form', 'Post type UI', 'mashvp-forms'),
                    'view_item'          => _x('Show form', 'Post type UI', 'mashvp-forms'),
                    'search_items'       => _x('Search forms', 'Post type UI', 'mashvp-forms'),
                    'not_found'          => _x('No forms found', 'Post type UI', 'mashvp-forms'),
                    'not_found_in_trash' => _x('No forms found in Trash', 'Post type UI', 'mashvp-forms')
                ],

                'has_archive'         => false,
                'publicly_queryable'  => false,
                'public'              => false,
                'show_ui'             => true,
                'exclude_from_search' => true,
                'show_in_rest'        => false,

                'capability_type'     => 'post',
                'supports'            => ['title', 'custom-fields'],
                'menu_icon'           => 'dashicons-forms',
            ]
        );

        add_filter(
            'manage_mvpf-form_posts_columns',
            [$this, 'formPostAddColumns']
        );

        add_action(
            'manage_mvpf-form_posts_custom_column',
            [$this, 'formPostRenderColumns'],
            1,
            2
        );
    }

    private function registerSubmissionPostType()
    {
        register_post_type(
            'mvpf-submission',
            [
                'label'  => _x('Form submissions', 'Post type UI', 'mashvp-forms'),
                'labels' => [
                    'name'               => _x('Form submissions', 'Post type UI', 'mashvp-forms'),
                    'singular_name'      => _x('Form submission', 'Post type UI', 'mashvp-forms'),
                    'all_items'          => _x('Form submissions', 'Post type UI', 'mashvp-forms'),
                    'add_new_item'       => _x('Add new', 'Post type UI', 'mashvp-forms'),
                    'edit_item'          => _x('Edit form submission', 'Post type UI', 'mashvp-forms'),
                    'new_item'           => _x('New form submission', 'Post type UI', 'mashvp-forms'),
                    'view_item'          => _x('Show form submission', 'Post type UI', 'mashvp-forms'),
                    'search_items'       => _x('Search form submissions', 'Post type UI', 'mashvp-forms'),
                    'not_found'          => _x('No form submissions found', 'Post type UI', 'mashvp-forms'),
                    'not_found_in_trash' => _x('No form submissions found in Trash', 'Post type UI', 'mashvp-forms')
                ],

                'has_archive'         => false,
                'publicly_queryable'  => false,
                'public'              => false,
                'show_ui'             => true,
                'exclude_from_search' => true,
                'show_in_rest'        => false,
                'show_in_menu'        => 'edit.php?post_type=mvpf-form',
                'menu_position'       => 10,

                'capability_type'     => 'post',
                'supports'            => ['title', 'custom-fields'],
                'menu_icon'           => 'dashicons-email',
            ]
        );

        add_filter(
            'manage_mvpf-submission_posts_columns',
            [$this, 'formSubmissionPostAddColumns']
        );

        add_action(
            'manage_mvpf-submission_posts_custom_column',
            [$this, 'formSubmissionPostRenderColumns'],
            1,
            2
        );
    }

    public function formPostAddColumns($columns)
    {
        $clone = [];

        foreach ($columns as $key => $value) {
            if ($key === 'date') {
                $clone['id'] = _x('ID', 'Post type columns', 'mashvp-forms');
                $clone['shortcode'] = _x('Shortcode', 'Post type columns', 'mashvp-forms');
            }

            $clone[$key] = $value;
        }

        return $clone;
    }

    public function formPostRenderColumns($column, $post_id)
    {
        if ($column === 'id') {
            echo $post_id;
        }

        if ($column === 'shortcode') {
            Renderer::instance()->renderTemplate('admin/shortcode-input', ['id' => $post_id]);
        }
    }

    public function formSubmissionPostAddColumns($columns)
    {
        $clone = [];

        foreach ($columns as $key => $value) {
            if ($key === 'date') {
                $clone['id'] = _x('ID', 'Post type columns', 'mashvp-forms');
                $clone['form'] = _x('Form', 'Post type columns', 'mashvp-forms');
                $clone['notifications'] = _x('Notifications', 'Post type columns', 'mashvp-forms');
            }

            $clone[$key] = $value;
        }

        return $clone;
    }

    public function formSubmissionPostRenderColumns($column, $post_id)
    {
        $submission = new Submission($post_id);

        if ($column === 'id') {
            echo $post_id;
        }

        if ($column === 'form') {
            $parent = wp_get_post_parent_id($post_id);
            $parent_title = get_the_title($parent);
            $parent_url = get_edit_post_link($parent);

            echo sprintf(
                "<a href=\"%s\">%s</a>",
                $parent_url,
                $parent_title
            );
        }

        if ($column === 'notifications') {
            $mail_status = $submission->getEmailNotificationStatus();
            $status = "mvpf--{$mail_status}";

            echo "<div class=\"dashicons dashicons-email $status\"></div>";
        }
    }
}
