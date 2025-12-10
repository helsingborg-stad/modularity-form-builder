<?php

declare(strict_types=1);

namespace ModularityFormBuilder\Upgrade\Version;

use ModularityFormBuilder\Upgrade\Version\VersionInterface;
use WP_CLI;

class V1 implements VersionInterface
{
    public function __construct(
        private \wpdb $db,
    ) {}

    public function upgrade(): bool
    {
        \ModularityFormBuilder\App::activatePlugin();
        WP_CLI::line('Cron is set');
        return true;
    }
}
