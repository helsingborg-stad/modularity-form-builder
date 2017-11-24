<?php

namespace ModularityFormBuilder;

class App
{
    public function __construct()
    {
    	new \ModularityFormBuilder\PostType();
		new \ModularityFormBuilder\Submission();
		new \ModularityFormBuilder\Options();

		add_filter('Municipio/blade/view_paths', array($this, 'addTemplatePaths'));

		/**
		 * Registers the module
		 */
		add_action('plugins_loaded', function () {
		    if (function_exists('modularity_register_module')) {
		        modularity_register_module(
		            FORM_BUILDER_MODULE_PATH . 'source/php/Module',
		            'Form'
		        );
		    }
		});
    }

    /**
     * Add searchable blade template paths
     * @param array $array Template paths
     */
    public function addTemplatePaths($array)
    {
        $array[] = FORM_BUILDER_MODULE_PATH . 'source/php/Module/views';
        return $array;
    }
}
