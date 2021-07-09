<?php

require_once __DIR__ . "/../vendor/autoload.php";

use ILIAS\DI\Container;
use ILIAS\GlobalScreen\Scope\MainMenu\Factory\AbstractBaseItem;
use ILIAS\GlobalScreen\Scope\MainMenu\Factory\hasSymbol;
use ILIAS\UI\Component\Symbol\Icon\Custom;
use ILIAS\UI\Component\Symbol\Icon\Standard;
use srag\CustomInputGUIs\SrFileObjectTypeIcons\Loader\CustomInputGUIsLoaderDetector;
use srag\DataTableUI\SrFileObjectTypeIcons\Implementation\Utils\DataTableUITrait;
use srag\DevTools\SrFileObjectTypeIcons\DevToolsCtrl;
use srag\Plugins\SrFileObjectTypeIcons\Utils\SrFileObjectTypeIconsTrait;
use srag\Plugins\SrTile\Tile\Tile;
use srag\RemovePluginDataConfirm\SrFileObjectTypeIcons\PluginUninstallTrait;

/**
 * Class ilSrFileObjectTypeIconsPlugin
 */
class ilSrFileObjectTypeIconsPlugin extends ilUserInterfaceHookPlugin
{

    use PluginUninstallTrait;
    use SrFileObjectTypeIconsTrait;

    use DataTableUITrait;

    const PLUGIN_CLASS_NAME = self::class;
    const PLUGIN_ID = "srfilobjtypicns";
    const PLUGIN_NAME = "SrFileObjectTypeIcons";
    const WEB_DATA_FOLDER = self::PLUGIN_ID . "_data";
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * ilSrFileObjectTypeIconsPlugin constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * @inheritDoc
     */
    public function exchangeUIRendererAfterInitialization(Container $dic) : Closure
    {
        return CustomInputGUIsLoaderDetector::exchangeUIRendererAfterInitialization();
    }


    /**
     * @inheritDoc
     */
    public function getPluginName() : string
    {
        return self::PLUGIN_NAME;
    }


    /**
     * @inheritDoc
     */
    public function handleEvent(/*string*/ $a_component, /*string*/ $a_event, /*array*/ $a_parameter)/* : void*/
    {
        if (file_exists(__DIR__ . "/../../SrContainerObjectMenu/vendor/autoload.php")) {
            switch ($a_component) {
                case IL_COMP_PLUGIN . "/" . ilSrContainerObjectMenuPlugin::PLUGIN_NAME:
                    switch ($a_event) {
                        case ilSrContainerObjectMenuPlugin::EVENT_CHANGE_MENU_ENTRY:
                            $this->changeMenuEntry($a_parameter["entry"], $a_parameter["obj_id"]);
                            break;
                        default:
                            break;
                    }
                    break;
                default:
                    break;
            }
        }

        if (file_exists(__DIR__ . "/../../../../Repository/RepositoryObject/SrContainerObjectTree/vendor/autoload.php")) {
            switch ($a_component) {
                case IL_COMP_PLUGIN . "/" . ilSrContainerObjectTreePlugin::PLUGIN_NAME:
                    switch ($a_event) {
                        case ilSrContainerObjectTreePlugin::EVENT_CHANGE_CHILD_BEFORE_OUTPUT:
                            $this->changeChildBeforeOutput($a_parameter["child"]);
                            break;
                        default:
                            break;
                    }
                    break;
                default:
                    break;
            }
        }

        if (file_exists(__DIR__ . "/../../SrTile/vendor/autoload.php")) {
            switch ($a_component) {
                case IL_COMP_PLUGIN . "/" . ilSrTilePlugin::PLUGIN_NAME:
                    switch ($a_event) {
                        case ilSrTilePlugin::EVENT_CHANGE_TILE_BEFORE_RENDER:
                            $this->changeTileBeforeRender($a_parameter["tile"]);
                            break;
                        default:
                            break;
                    }
                    break;
                default:
                    break;
            }
        }
    }


    /**
     * @inheritDoc
     */
    public function updateLanguages(/*?array*/ $a_lang_keys = null)/*:void*/
    {
        parent::updateLanguages($a_lang_keys);

        $this->installRemovePluginDataConfirmLanguages();

        self::dataTableUI()->installLanguages(self::plugin());

        DevToolsCtrl::installLanguages(self::plugin());
    }


    /**
     * @param array $child
     */
    protected function changeChildBeforeOutput(array &$child)/* : void*/
    {
        $child["icon"] = self::srFileObjectTypeIcons()->fileTypeIcons()->replaceFileIconsPart($child["icon"], $child["ref_id"], true);
    }


    /**
     * @param AbstractBaseItem $entry
     * @param int              $obj_id
     */
    protected function changeMenuEntry(AbstractBaseItem &$entry, int $obj_id)/* : void*/
    {
        if ($entry instanceof hasSymbol && $entry->hasSymbol()) {
            $symbol = $entry->getSymbol();

            if ($symbol instanceof Custom) {
                $icon = $icon_org = $symbol->getIconPath();
            } else {
                if ($symbol instanceof Standard) {
                    $icon = $icon_org = ilUtil::getImagePath("icon_" . $symbol->getName() . ".svg");
                } else {
                    return;
                }
            }

            $icon = self::srFileObjectTypeIcons()->fileTypeIcons()->replaceFileIconsPart($icon, $obj_id, false);

            if ($icon === $icon_org) {
                return;
            }

            $entry = $entry->withSymbol(self::dic()->ui()->factory()->symbol()->icon()->custom($icon, $symbol->getAriaLabel()));
        }
    }


    /**
     * @param Tile $tile
     */
    protected function changeTileBeforeRender(Tile $tile)/* : void*/
    {
        $tile->setCustomIcon(self::srFileObjectTypeIcons()->fileTypeIcons()->replaceFileIconsPart($tile->getIcon(), $tile->getObjRefId(), true));
    }


    /**
     * @inheritDoc
     */
    protected function deleteData()/*: void*/
    {
        self::srFileObjectTypeIcons()->dropTables();
    }


    /**
     * @inheritDoc
     */
    protected function shouldUseOneUpdateStepOnly() : bool
    {
        return true;
    }
}
