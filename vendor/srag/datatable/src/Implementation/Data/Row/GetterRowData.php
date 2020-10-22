<?php

namespace srag\DataTableUI\SrFileObjectTypeIcons\Implementation\Data\Row;

use srag\CustomInputGUIs\SrFileObjectTypeIcons\PropertyFormGUI\Items\Items;

/**
 * Class GetterRowData
 *
 * @package srag\DataTableUI\SrFileObjectTypeIcons\Implementation\Data\Row
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class GetterRowData extends AbstractRowData
{

    /**
     * @inheritDoc
     */
    public function __invoke(string $key)
    {
        return Items::getter($this->getOriginalData(), $key);
    }
}
