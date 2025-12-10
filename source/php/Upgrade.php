<?php

declare(strict_types=1);

namespace ModularityFormBuilder;

use WP_CLI;

/**
 * Class App
 *
 * @package ModularityFormBuilder
 */
class Upgrade
{
    private $dbVersion = 1;
    private $dbVersionKey = 'modularity_form_builder_db_version';
    private $db;

    public function __construct()
    {
        add_action('admin_notices', [$this, 'addAdminNotice']);
    }

    public function addAdminNotice()
    {
        if (!is_super_admin()) {
            return;
        }

        $currentDbVersion = get_option($this->dbVersionKey);
        if (empty($currentDbVersion) || $currentDbVersion < $this->dbVersion) {
            echo
                sprintf('<div class="notice notice-warning update-nag inline">%s</div>', __(
                    'The database may need to be updated to accomodate new datatastructures. Run wp-cli "modularity-form-builder upgrade" to upgrade.',
                    'modularityFormBuilder',
                ))
            ;
        }
    }

    /**
     * Reset db version, in order to run all scripts from the beginning.
     *
     * @return void
     */
    public function reset()
    {
        update_option($this->dbVersionKey, 0);
    }

    /**
     * Logs error message
     *
     * @param string $message Error message
     *
     */
    private function logError(string $message)
    {
        error_log($message);
        WP_CLI::warning($message);
    }

    /**
     * Run upgrade functions
     *
     * @return void
     */
    public function upgrade()
    {
        if (empty(get_option($this->dbVersionKey))) {
            update_option($this->dbVersionKey, 0);
        }

        $currentDbVersion = is_numeric(get_option($this->dbVersionKey)) ? (int) get_option($this->dbVersionKey) : 0;
        if ($this->dbVersion != $currentDbVersion) {
            if (!is_numeric($this->dbVersion)) {
                wp_die(__('To be installed database version must be a number.', 'modularity-form-builder'));
                return;
            }

            if (!is_numeric($currentDbVersion)) {
                $this->logError(__('Current database version must be a number.', 'modularity-form-builder'));
                return;
            }

            if ($currentDbVersion > $this->dbVersion) {
                $this->logError(__(
                    'Database cannot be lower than currently installed (cannot downgrade).',
                    'modularity-form-builder',
                ));
                return;
            }

            //Fetch global wpdb object, save to $db
            $this->globalToLocal('wpdb', 'db');

            $currentDbVersion += 1;

            for ($currentDbVersion; $currentDbVersion <= $this->dbVersion; $currentDbVersion++) {
                $class = 'ModularityFormBuilder\Upgrade\Version\V' . $currentDbVersion;

                if (class_exists($class) && $this->db) {
                    WP_CLI::line(sprintf(
                        __('Initializing database migration to %s.', 'modularity-form-builder'),
                        $currentDbVersion,
                    ));

                    for ($halt = 3; $halt > 0; $halt--) {
                        WP_CLI::line(sprintf(
                            __('Upgrade will start in %s seconds.', 'modularity-form-builder'),
                            $halt,
                        ));

                        sleep(1);
                    }

                    $version = new $class($this->db);
                    $version->upgrade();

                    WP_CLI::line(sprintf(
                        __('Locking database to version %s.', 'modularity-form-builder'),
                        $currentDbVersion,
                    ));

                    update_option($this->dbVersionKey, $currentDbVersion);

                    WP_CLI::line('Flushing cache.');
                    wp_cache_flush();
                }
            }

            WP_CLI::success(sprintf(
                __('Database migration complete; upgraded to version %s.', 'modularity-form-builder'),
                $this->dbVersion,
            ));
        } else {
            WP_CLI::line(__('Database is already up to date.', 'modularity-form-builder'));
        }
    }

    /**
     * Creates a local copy of the global instance
     * The target var should be defined in class header as private or public
     * @param string $global The name of global varable that should be made local
     * @param string $local Handle the global with the name of this string locally
     * @return void
     */
    private function globalToLocal($global, $local = null)
    {
        global $$global;

        if (is_null($$global)) {
            return false;
        }

        if (is_null($local)) {
            $this->$global = $$global;
        } else {
            $this->$local = $$global;
        }

        return true;
    }
}
