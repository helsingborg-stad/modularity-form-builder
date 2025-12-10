<?php

declare(strict_types=1);

namespace ModularityFormBuilder\Entity;

class MetaBox
{
    public $title;
    public $fields;
    public $context;
    public $priority;
    public $postType;

    public function __construct($title, $fields = [], $context = 'normal', $priority = 'default', $postType)
    {
        $this->title = $title;
        $this->fields = $fields;
        $this->context = $context;
        $this->priority = $priority;
        $this->postType = $postType;

        add_action('add_meta_boxes', [$this, 'addMetaBox']);
    }

    /* Attaches meta boxes to the post type */
    public function addMetaBox()
    {
        // Meta variables
        $boxId = strtolower(str_replace(' ', '_', $this->title));
        $boxTitle = ucwords(str_replace('_', ' ', $this->title));

        // Make the fields global
        global $custom_fields;
        $custom_fields[$this->title] = $fields;

        add_meta_box(
            $boxId,
            $boxTitle,
            static function ($post, $data) {
                global $post;

                // Nonce field for some validation
                wp_nonce_field(plugin_basename(__FILE__), 'custom_post_type');

                // Get all inputs from $data
                $custom_fields = $data['args'][0];

                // Get the saved values
                $meta = get_post_custom($post->ID);

                // Check the array and loop through it
                if (!empty($custom_fields)) {
                    /* Loop through $custom_fields */
                    foreach ($custom_fields as $label => $type) {
                        $field_id_name = strtolower(str_replace(' ', '_', $data['id'])) . '_' . strtolower(str_replace(' ', '_', $label));

                        echo '<label for="' . $field_id_name . '">' . $label . '</label><input type="text" name="custom_meta[' . $field_id_name . ']" id="' . $field_id_name . '" value="' . $meta[$field_id_name][0] . '" />';
                    }
                }
            },
            $this->postType,
            $this->context,
            $this->priority,
            [$fields],
        );
    }
}
