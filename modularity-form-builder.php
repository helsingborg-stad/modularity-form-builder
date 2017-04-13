<?php
/*
 * Plugin Name: Modularity Form Builder
 * Plugin URI: http://github.com/helsingborg-stad
 * Description: Build submittable form(s) to display with module
 * Version: 1.0
 * Author: Kristoffer Svanmark
 */

define('FORM_BUILDER_MODULE_PATH', plugin_dir_path(__FILE__));

require_once FORM_BUILDER_MODULE_PATH . 'vendor/autoload.php';
require_once FORM_BUILDER_MODULE_PATH . 'PostType.php';
require_once FORM_BUILDER_MODULE_PATH . 'Submission.php';

new \ModularityFormBuilder\PostType();
new \ModularityFormBuilder\Submission();

//Load lang
load_plugin_textdomain('modularity-form-builder', false, plugin_basename(dirname(__FILE__)) . '/languages');

// Acf auto import and export
$acfExportManager = new \AcfExportManager\AcfExportManager();
$acfExportManager->setTextdomain('modularity-form-builder');
$acfExportManager->setExportFolder(FORM_BUILDER_MODULE_PATH . 'acf-fields/');
$acfExportManager->autoExport(array(
    'form' => 'group_58eb301ecb36a'
));
$acfExportManager->import();

/**
 * Registers the module
 */
modularity_register_module(
    FORM_BUILDER_MODULE_PATH,
    'Form'
);
