<?php

namespace srag\DataTableUI\SrFileObjectTypeIcons\Component\Settings\Storage;

/**
 * Interface Factory
 *
 * @package srag\DataTableUI\SrFileObjectTypeIcons\Component\Settings\Storage
 */
interface Factory
{

    /**
     * @return SettingsStorage
     */
    public function default() : SettingsStorage;
}
