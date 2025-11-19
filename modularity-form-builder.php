<?php
/*
 * Plugin Name: Modularity Form Builder
 * Plugin URI: http://github.com/helsingborg-stad
 * Description: Build submittable form(s) to display with module
 * Version: 4.0.1
 * Author: Kristoffer Svanmark, Sebastian Thulin
 */

use ModularityFormBuilder\Blade\Blade;
use ComponentLibrary\Init as ComponentLibraryInit;

define('FORM_BUILDER_MODULE_PATH', plugin_dir_path(__FILE__));
define('FORM_BUILDER_MODULE_URL', plugins_url('', __FILE__));
define('FORM_BUILDER_MODULE_TEMPLATE_PATH', FORM_BUILDER_MODULE_PATH . 'templates/');
define('FORM_BUILDER_MODULE_VIEW_PATH', FORM_BUILDER_MODULE_PATH . 'source/php/Module/views');

//Load lang
add_action('init', function () {
    load_plugin_textdomain('modularity-form-builder', false, plugin_basename(dirname(__FILE__)) . '/languages');
});

require_once FORM_BUILDER_MODULE_PATH . 'Public.php';
if (file_exists(FORM_BUILDER_MODULE_PATH . 'vendor/autoload.php')) {
    require_once FORM_BUILDER_MODULE_PATH . 'vendor/autoload.php';
}


// Acf auto import and export
add_action('plugins_loaded', function () {
    $bladeInstance = new Blade(new ComponentLibraryInit([]));
    $acfExportManager = new \AcfExportManager\AcfExportManager();
    $acfExportManager->setTextdomain('modularity-form-builder');
    $acfExportManager->setExportFolder(FORM_BUILDER_MODULE_PATH . 'acf-fields/');
    $acfExportManager->autoExport(array(
        'form' => 'group_58eb301ecb36a',
    ));
    $acfExportManager->import();

    if (function_exists('get_field')) {
        new ModularityFormBuilder\App($bladeInstance);
    }
});

