<?php

namespace srag\DataTableUI\SrFileObjectTypeIcons\Implementation\Utils;

use srag\DataTableUI\SrFileObjectTypeIcons\Component\Factory as FactoryInterface;
use srag\DataTableUI\SrFileObjectTypeIcons\Implementation\Factory;

/**
 * Trait DataTableUITrait
 *
 * @package srag\DataTableUI\SrFileObjectTypeIcons\Implementation\Utils
 */
trait DataTableUITrait
{

    /**
     * @return FactoryInterface
     */
    protected static function dataTableUI() : FactoryInterface
    {
        return Factory::getInstance();
    }
}
