<?php
/*
 * Plugin Name: Modularity Form Builder
 * Plugin URI: http://github.com/helsingborg-stad
 * Description: Build submittable form(s) to display with module
 * Version: 1.0
 * Author: Kristoffer Svanmark
 */

define('FORM_BUILDER_MODULE_PATH', plugin_dir_path(__FILE__));

/**
 * Registers the module
 */
modularity_register_module(
    FORM_BUILDER_MODULE_PATH,
    'Form'
);
