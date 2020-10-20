<?php

namespace srag\Plugins\SrFileObjectTypeIcons\FileTypeIcon\Table;

use ilSrFileObjectTypeIconsPlugin;
use srag\DataTableUI\SrFileObjectTypeIcons\Component\Table;
use srag\DataTableUI\SrFileObjectTypeIcons\Implementation\Utils\AbstractTableBuilder;
use srag\Plugins\SrFileObjectTypeIcons\FileTypeIcon\FileTypeIconGUI;
use srag\Plugins\SrFileObjectTypeIcons\FileTypeIcon\FileTypeIconsGUI;
use srag\Plugins\SrFileObjectTypeIcons\Utils\SrFileObjectTypeIconsTrait;

/**
 * Class TableBuilder
 *
 * @package srag\Plugins\SrFileObjectTypeIcons\FileTypeIcon\Table
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class TableBuilder extends AbstractTableBuilder
{

    use SrFileObjectTypeIconsTrait;

    const PLUGIN_CLASS_NAME = ilSrFileObjectTypeIconsPlugin::class;


    /**
     * @inheritDoc
     *
     * @param FileTypeIconsGUI $parent
     */
    public function __construct(FileTypeIconsGUI $parent)
    {
        parent::__construct($parent);
    }


    /**
     * @inheritDoc
     */
    public function render() : string
    {
        self::dic()->toolbar()->addComponent(self::dic()->ui()->factory()->button()->standard(self::plugin()->translate("add_file_type_icon", FileTypeIconsGUI::LANG_MODULE),
            self::dic()->ctrl()->getLinkTargetByClass(FileTypeIconGUI::class, FileTypeIconGUI::CMD_ADD_FILE_TYPE_ICON, "", false, false)));

        return parent::render();
    }


    /**
     * @inheritDoc
     */
    protected function buildTable() : Table
    {
        $table = self::dataTableUI()->table(ilSrFileObjectTypeIconsPlugin::PLUGIN_ID . "_icons",
            self::dic()->ctrl()->getLinkTarget($this->parent, FileTypeIconsGUI::CMD_LIST_FILE_TYPE_ICONS, "", false, false),
            self::plugin()->translate("file_type_icons", FileTypeIconsGUI::LANG_MODULE), [
                self::dataTableUI()->column()->column("icon_path",
                    self::plugin()->translate("icon", FileTypeIconsGUI::LANG_MODULE))->withSortable(false)->withFormatter(self::dataTableUI()
                    ->column()
                    ->formatter()
                    ->image())->withSelectable(false),
                self::dataTableUI()->column()->column("extensions",
                    self::plugin()->translate("extensions", FileTypeIconsGUI::LANG_MODULE))->withSortable(false)->withFormatter(new ExtensionsFormatter())->withSelectable(false),
                self::dataTableUI()->column()->column("actions",
                    self::plugin()->translate("actions", FileTypeIconsGUI::LANG_MODULE))->withFormatter(self::dataTableUI()->column()->formatter()->actions()->actionsDropdown())
            ], new DataFetcher())->withPlugin(self::plugin());

        return $table;
    }
}
