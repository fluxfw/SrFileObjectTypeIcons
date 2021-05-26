<?php

namespace srag\Plugins\SrFileObjectTypeIcons\Utils;

use srag\Plugins\SrFileObjectTypeIcons\Repository;

/**
 * Trait SrFileObjectTypeIconsTrait
 *
 * @package srag\Plugins\SrFileObjectTypeIcons\Utils
 */
trait SrFileObjectTypeIconsTrait
{

    /**
     * @return Repository
     */
    protected static function srFileObjectTypeIcons() : Repository
    {
        return Repository::getInstance();
    }
}
