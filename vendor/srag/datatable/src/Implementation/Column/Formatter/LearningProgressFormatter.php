<?php

namespace srag\DataTableUI\SrFileObjectTypeIcons\Implementation\Column\Formatter;

use ilLearningProgressBaseGUI;
use srag\DataTableUI\SrFileObjectTypeIcons\Component\Column\Column;
use srag\DataTableUI\SrFileObjectTypeIcons\Component\Data\Row\RowData;
use srag\DataTableUI\SrFileObjectTypeIcons\Component\Format\Format;

/**
 * Class LearningProgressFormatter
 *
 * @package srag\DataTableUI\SrFileObjectTypeIcons\Implementation\Column\Formatter
 */
class LearningProgressFormatter extends DefaultFormatter
{

    /**
     * @inheritDoc
     */
    public function formatRowCell(Format $format, $status, Column $column, RowData $row, string $table_id) : string
    {
        $img = ilLearningProgressBaseGUI::_getImagePathForStatus($status);
        $text = ilLearningProgressBaseGUI::_getStatusText($status);

        return self::output()->getHTML([
            self::dic()->ui()
                ->factory()
                ->icon()
                ->custom($img, $text),
            self::dic()->ui()->factory()->legacy($text)
        ]);
    }
}
