<?php

namespace srag\DataTableUI\SrFileObjectTypeIcons\Component\Utils;

use srag\DataTableUI\SrFileObjectTypeIcons\Component\Table;

/**
 * Interface TableBuilder
 *
 * @package srag\DataTableUI\SrFileObjectTypeIcons\Component\Utils
 */
interface TableBuilder
{

    /**
     * @return Table
     */
    public function getTable() : Table;


    /**
     * @return string
     */
    public function render() : string;
}
