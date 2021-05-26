<?php

namespace srag\DataTableUI\SrFileObjectTypeIcons\Component\Data\Fetcher;

use srag\DataTableUI\SrFileObjectTypeIcons\Component\Data\Data;
use srag\DataTableUI\SrFileObjectTypeIcons\Component\Settings\Settings;
use srag\DataTableUI\SrFileObjectTypeIcons\Component\Table;

/**
 * Interface DataFetcher
 *
 * @package srag\DataTableUI\SrFileObjectTypeIcons\Component\Data\Fetcher
 */
interface DataFetcher
{

    /**
     * @param Settings $settings
     *
     * @return Data
     */
    public function fetchData(Settings $settings) : Data;


    /**
     * @param Table $component
     *
     * @return string
     */
    public function getNoDataText(Table $component) : string;


    /**
     * @return bool
     */
    public function isFetchDataNeedsFilterFirstSet() : bool;
}
