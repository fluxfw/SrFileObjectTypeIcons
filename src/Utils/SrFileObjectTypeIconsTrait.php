<?php

namespace srag\Plugins\SrFileObjectTypeIcons\Utils;

use srag\Plugins\SrFileObjectTypeIcons\Repository;

/**
 * Trait SrFileObjectTypeIconsTrait
 *
 * @package srag\Plugins\SrFileObjectTypeIcons\Utils
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
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
