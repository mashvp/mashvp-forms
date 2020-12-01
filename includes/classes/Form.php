<?php

namespace Mashvp\Forms;

class Form
{
    private $id;
    private $post;

    public function __construct($form_id)
    {
        $this->id = $form_id;

        $post = get_post($form_id);
        if (get_post_type($post) === 'mashvp-form') {
          $this->post = $post;
        }
    }

    public function render()
    {
        if (!$this->post) {
          return <<<HTML
            <!-- [mashvp-form] No form with id {$this->id} was found -->
          HTML;
        }

        return <<<HTML
          <p>This is the form ID {$this->id}</p>
        HTML;
    }
}
