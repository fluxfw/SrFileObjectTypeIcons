<?php

namespace srag\Plugins\SrFileObjectTypeIcons\FileTypeIcon;

require_once __DIR__ . "/../../vendor/autoload.php";

use ilSrFileObjectTypeIconsPlugin;
use srag\DIC\SrFileObjectTypeIcons\DICTrait;
use srag\Plugins\SrFileObjectTypeIcons\Utils\SrFileObjectTypeIconsTrait;

/**
 * Class FileTypeIconsGUI
 *
 * @package           srag\Plugins\SrFileObjectTypeIcons\FileTypeIcon
 *
 * @ilCtrl_isCalledBy srag\Plugins\SrFileObjectTypeIcons\FileTypeIcon\FileTypeIconsGUI: ilSrFileObjectTypeIconsConfigGUI
 */
class FileTypeIconsGUI
{

    use DICTrait;
    use SrFileObjectTypeIconsTrait;

    const CMD_LIST_FILE_TYPE_ICONS = "listFileTypeIcons";
    const LANG_MODULE = "file_type_icons";
    const PLUGIN_CLASS_NAME = ilSrFileObjectTypeIconsPlugin::class;
    const TAB_LIST_FILE_TYPE_ICONS = "list_file_type_icons";


    /**
     * FileTypeIconsGUI constructor
     */
    public function __construct()
    {

    }


    /**
     *
     */
    public static function addTabs()/*: void*/
    {
        self::dic()->tabs()->addTab(self::TAB_LIST_FILE_TYPE_ICONS, self::plugin()->translate("file_type_icons", self::LANG_MODULE), self::dic()->ctrl()
            ->getLinkTargetByClass(self::class, self::CMD_LIST_FILE_TYPE_ICONS));
    }


    /**
     *
     */
    public function executeCommand()/*: void*/
    {
        $this->setTabs();

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            case strtolower(FileTypeIconGUI::class):
                self::dic()->ctrl()->forwardCommand(new FileTypeIconGUI());
                break;

            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_LIST_FILE_TYPE_ICONS:
                        $this->{$cmd}();
                        break;

                    default:
                        break;
                }
                break;
        }
    }


    /**
     *
     */
    protected function listFileTypeIcons()/*: void*/
    {
        self::dic()->tabs()->activateTab(self::TAB_LIST_FILE_TYPE_ICONS);

        $table = self::srFileObjectTypeIcons()->fileTypeIcons()->factory()->newTableBuilderInstance($this);

        self::output()->output($table);
    }


    /**
     *
     */
    protected function setTabs()/*: void*/
    {

    }
}
