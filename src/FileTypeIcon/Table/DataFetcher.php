<?php

namespace srag\Plugins\SrFileObjectTypeIcons\FileTypeIcon\Table;

use ilSrFileObjectTypeIconsPlugin;
use srag\DataTableUI\SrFileObjectTypeIcons\Component\Data\Data;
use srag\DataTableUI\SrFileObjectTypeIcons\Component\Data\Row\RowData;
use srag\DataTableUI\SrFileObjectTypeIcons\Component\Settings\Settings;
use srag\DataTableUI\SrFileObjectTypeIcons\Implementation\Data\Fetcher\AbstractDataFetcher;
use srag\Plugins\SrFileObjectTypeIcons\FileTypeIcon\FileTypeIcon;
use srag\Plugins\SrFileObjectTypeIcons\Utils\SrFileObjectTypeIconsTrait;

/**
 * Class DataFetcher
 *
 * @package srag\Plugins\SrFileObjectTypeIcons\FileTypeIcon\Table
 */
class DataFetcher extends AbstractDataFetcher
{

    use SrFileObjectTypeIconsTrait;

    const PLUGIN_CLASS_NAME = ilSrFileObjectTypeIconsPlugin::class;


    /**
     * @inheritDoc
     */
    public function fetchData(Settings $settings) : Data
    {
        $data = self::srFileObjectTypeIcons()->fileTypeIcons()->getFileTypeIcons();

        $max_count = count($data);

        $data = array_slice($data, $settings->getOffset(), $settings->getRowsCount());

        return self::dataTableUI()->data()->data(array_map(function (FileTypeIcon $file_type_icon
        ) : RowData {
            return self::dataTableUI()->data()->row()->getter($file_type_icon->getFileTypeIconId(), $file_type_icon);
        }, $data), $max_count);
    }
}
