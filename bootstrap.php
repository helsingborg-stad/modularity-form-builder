<?php

require_once './vendor/autoload.php';

if (!function_exists('register_activation_hook')) {
    /**
     * Stub for WordPress register_activation_hook in unit tests.
     *
     * @param string   $file    Plugin file path.
     * @param callable $callback Callback to run on activation.
     *
     * @return void
     */
    function register_activation_hook(string $file, callable $callback): void
    {
    }
}

if (!function_exists('register_deactivation_hook')) {
    /**
     * Stub for WordPress register_deactivation_hook in unit tests.
     *
     * @param string   $file    Plugin file path.
     * @param callable $callback Callback to run on deactivation.
     *
     * @return void
     */
    function register_deactivation_hook(string $file, callable $callback): void
    {
    }
}
