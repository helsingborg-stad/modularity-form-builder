<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_58eb301ecb36a',
    'title' => __('Form', 'modularity-form-builder'),
    'fields' => array(
        0 => array(
            'layouts' => array(
                0 => array(
                    'key' => '58eb3033e588f',
                    'name' => 'firstname_lastname',
                    'label' => 'Firstname & lastname',
                    'display' => 'block',
                    'sub_fields' => array(
                        0 => array(
                            'default_value' => 0,
                            'message' => 'Required field',
                            'ui' => 0,
                            'ui_on_text' => '',
                            'ui_off_text' => '',
                            'key' => 'field_58eb31c8d6672',
                            'label' => 'Required',
                            'name' => 'required',
                            'type' => 'true_false',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                        ),
                    ),
                    'min' => '',
                    'max' => '',
                ),
            ),
            'min' => 1,
            'max' => '',
            'button_label' => 'Add form field',
            'key' => 'field_58eb302883a68',
            'label' => __('Fields', 'modularity-form-builder'),
            'name' => 'form_fields',
            'type' => 'flexible_content',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
        ),
    ),
    'location' => array(
        0 => array(
            0 => array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'mod-form',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));
}