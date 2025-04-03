<?php

namespace ModularityFormBuilder\Upgrade\Version;

/**
 * Interface MigratorInterface
 * 
 * This interface defines the contract for migrators.
 */
interface VersionInterface
{
    /**
     * Upgrades to a new version
     * 
     * @return bool
     */
    public function upgrade():bool;
}