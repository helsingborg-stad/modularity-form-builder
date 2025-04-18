<?php

namespace ModularityFormBuilder;

use ModularityFormBuilder\Upgrade;
use WP_CLI;

class WpCli {
    public function __construct(Upgrade $upgradeInstance)
    {
        add_action('cli_init', function () use ($upgradeInstance) {
            if (defined('WP_CLI') && WP_CLI) {
                if (function_exists('acf')) {
                    WP_CLI::add_command('modularity-form-builder', $upgradeInstance);
                } else {
                    WP_CLI::error('ACF is not available.');
                }
            }
        });
    }
}