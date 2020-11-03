<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\DevTools\SrFileObjectTypeIcons\DevToolsCtrl;
use srag\DIC\SrFileObjectTypeIcons\DICTrait;
use srag\Plugins\SrFileObjectTypeIcons\FileTypeIcon\FileTypeIconsGUI;
use srag\Plugins\SrFileObjectTypeIcons\Utils\SrFileObjectTypeIconsTrait;

/**
 * Class ilSrFileObjectTypeIconsConfigGUI
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\DevTools\SrFileObjectTypeIcons\DevToolsCtrl: ilSrFileObjectTypeIconsConfigGUI
 */
class ilSrFileObjectTypeIconsConfigGUI extends ilPluginConfigGUI
{

    use DICTrait;
    use SrFileObjectTypeIconsTrait;

    const CMD_CONFIGURE = "configure";
    const PLUGIN_CLASS_NAME = ilSrFileObjectTypeIconsPlugin::class;


    /**
     * ilSrFileObjectTypeIconsConfigGUI constructor
     */
    public function __construct()
    {

    }


    /**
     * @inheritDoc
     */
    public function performCommand(/*string*/ $cmd)/*:void*/
    {
        $this->setTabs();

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            case strtolower(FileTypeIconsGUI::class):
                self::dic()->ctrl()->forwardCommand(new FileTypeIconsGUI());
                break;

            case strtolower(DevToolsCtrl::class):
                self::dic()->ctrl()->forwardCommand(new DevToolsCtrl($this, self::plugin()));
                break;

            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_CONFIGURE:
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
    protected function configure()/*: void*/
    {
        self::dic()->ctrl()->redirectByClass(FileTypeIconsGUI::class, FileTypeIconsGUI::CMD_LIST_FILE_TYPE_ICONS);
    }


    /**
     *
     */
    protected function setTabs()/*: void*/
    {
        FileTypeIconsGUI::addTabs();

        DevToolsCtrl::addTabs(self::plugin());

        self::dic()->locator()->addItem(ilSrFileObjectTypeIconsPlugin::PLUGIN_NAME, self::dic()->ctrl()->getLinkTarget($this, self::CMD_CONFIGURE));
    }
}
