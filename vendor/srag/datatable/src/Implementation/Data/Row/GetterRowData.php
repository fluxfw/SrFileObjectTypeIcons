<?php

namespace srag\DataTableUI\SrFileObjectTypeIcons\Implementation\Data\Row;

use srag\CustomInputGUIs\SrFileObjectTypeIcons\PropertyFormGUI\Items\Items;

/**
 * Class GetterRowData
 *
 * @package srag\DataTableUI\SrFileObjectTypeIcons\Implementation\Data\Row
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
