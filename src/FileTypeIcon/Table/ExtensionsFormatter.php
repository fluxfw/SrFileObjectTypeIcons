<?php

namespace srag\Plugins\SrFileObjectTypeIcons\FileTypeIcon\Table;

use srag\DataTableUI\SrFileObjectTypeIcons\Component\Column\Column;
use srag\DataTableUI\SrFileObjectTypeIcons\Component\Data\Row\RowData;
use srag\DataTableUI\SrFileObjectTypeIcons\Component\Format\Format;
use srag\DataTableUI\SrFileObjectTypeIcons\Implementation\Column\Formatter\DefaultFormatter;

/**
 * Class ExtensionsFormatter
 *
 * @package srag\Plugins\SrFileObjectTypeIcons\FileTypeIcon\Table
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ExtensionsFormatter extends DefaultFormatter
{

    /**
     * @inheritDoc
     */
    public function formatRowCell(Format $format, $extensions, Column $column, RowData $row, string $table_id) : string
    {
        return nl2br(implode("\n", $extensions), false);
    }
}
