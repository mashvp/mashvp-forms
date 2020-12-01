<?php

namespace Mashvp\Forms;

use Mashvp\SingletonClass;
use Mashvp\Forms\Renderer;

class PostTypes extends SingletonClass
{
    public function register()
    {
        register_post_type(
            'mashvp-form',
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
                'public'              => true,
                'exclude_from_search' => true,

                'capability_type'     => 'post',
                'supports'            => ['title', 'custom-fields'],
                'menu_icon'           => 'dashicons-forms',
            ]
        );

        add_filter(
            'manage_mashvp-form_posts_columns',
            [$this, 'form_post_add_columns']
        );

        add_action(
            'manage_mashvp-form_posts_custom_column',
            [$this, 'form_post_render_columns'],
            1,
            2
        );
    }

    public function form_post_add_columns($columns)
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

    public function form_post_render_columns($column, $post_id)
    {
        if ($column === 'id') {
            echo $post_id;
        }

        if ($column === 'shortcode') {
            Renderer::instance()->renderTemplate('admin/shortcode-input', ['id' => $post_id]);
        }
    }
}
