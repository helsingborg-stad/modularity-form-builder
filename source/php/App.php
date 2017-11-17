<?php

namespace ModularityFormBuilder;

class App
{
    public function __construct()
    {
    	new \ModularityFormBuilder\PostType();
		new \ModularityFormBuilder\Submission();
		new \ModularityFormBuilder\Options();

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
}
