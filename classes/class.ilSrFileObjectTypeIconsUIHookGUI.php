<?php

use ILIAS\Services\UICore\MetaTemplate\PageContentGUI;
use srag\DIC\SrFileObjectTypeIcons\DICTrait;
use srag\Plugins\SrFileObjectTypeIcons\Utils\SrFileObjectTypeIconsTrait;

/**
 * Class ilSrFileObjectTypeIconsUIHookGUI
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ilSrFileObjectTypeIconsUIHookGUI extends ilUIHookPluginGUI
{

    use DICTrait;
    use SrFileObjectTypeIconsTrait;

    const GET_PARAM_REF_ID = "ref_id";
    const GET_PARAM_TARGET = "target";
    const PLUGIN_CLASS_NAME = ilSrFileObjectTypeIconsPlugin::class;
    const TEMPLATE_GET = "template_get";
    const TEMPLATE_ID_CONTAINER_LIST_ITEM = "Services/Container/tpl.container_list_item.html";
    const TEMPLATE_ID_ITEM_STANDARD = "src/UI/templates/default/Item/tpl.item_standard.html";
    const TEMPLATE_ID_MAIN = "tpl.main.html";
    const TEMPLATE_ID_MAIN_MENU = "Services/MainMenu/tpl.main_menu.html";
    const TEMPLATE_ID_PAGE_CONTENT = "tpl.page_content.html";
    const TEMPLATE_ID_TABLE2 = "Services/Table/tpl.table2.html";
    const TEMPLATE_SHOW = "template_show";
    /**
     * @var int|null
     */
    protected static $current_ref_id = null;


    /**
     * ilSrFileObjectTypeIconsUIHookGUI constructor
     */
    public function __construct()
    {

    }


    /**
     * @return int
     */
    protected static function getCurrentRefId() : int
    {
        if (self::$current_ref_id === null) {
            self::$current_ref_id = filter_input(INPUT_GET, self::GET_PARAM_REF_ID);

            if (self::$current_ref_id === null) {
                $param_target = filter_input(INPUT_GET, self::GET_PARAM_TARGET);

                self::$current_ref_id = explode("_", $param_target)[1];
            }

            self::$current_ref_id = intval(self::$current_ref_id);
        }

        return self::$current_ref_id;
    }


    /**
     * @inheritDoc
     */
    public function getHTML(/*string*/ $a_comp, /*string*/ $a_part, /*array*/ $a_par = []) : array
    {
        if ($a_par["tpl_id"] === self::TEMPLATE_ID_CONTAINER_LIST_ITEM && $a_part === self::TEMPLATE_GET) {
            $html = $a_par["html"];

            $html = self::srFileObjectTypeIcons()->fileTypeIcons()->replaceFileIconsMultipleObjects($html, '/\\s+(data-list-item-)?id\\s*=\\s*["\']{1}lg_div_([0-9]+)/', true, false);

            return [
                "mode" => self::REPLACE,
                "html" => $html
            ];
        }

        if ($a_par["tpl_id"] === self::TEMPLATE_ID_TABLE2 && $a_part === self::TEMPLATE_GET) {
            $html = $a_par["html"];

            $html = self::srFileObjectTypeIcons()->fileTypeIcons()->replaceFileIconsMultipleObjects($html, '/\\s+(data-list-item-)?id\\s*=\\s*["\']{1}lg_div_([0-9]+)/', true, true);

            return [
                "mode" => self::REPLACE,
                "html" => $html
            ];
        }

        if ($a_par["tpl_id"] === self::TEMPLATE_ID_MAIN_MENU && $a_part === self::TEMPLATE_GET) {
            $html = $a_par["html"];

            $html = self::srFileObjectTypeIcons()->fileTypeIcons()->replaceFileIconsMultipleObjects($html, '/\\s+id\\s*=\\s*["\']mm_last_visited_([0-9]+)/', false, false);

            return [
                "mode" => self::REPLACE,
                "html" => $html
            ];
        }
        if ($a_par["tpl_id"] === self::TEMPLATE_ID_ITEM_STANDARD && $a_part === self::TEMPLATE_GET) {
            $html = $a_par["html"];

            $html = self::srFileObjectTypeIcons()->fileTypeIcons()->replaceFileIconsMultipleObjects($html, '/[?&]ref_id=([0-9]+)/', true, true);

            return [
                "mode" => self::REPLACE,
                "html" => $html
            ];
        }

        if ($a_par["tpl_id"] === self::TEMPLATE_ID_MAIN && $a_part === self::TEMPLATE_SHOW
            || $a_par["tpl_obj"] instanceof PageContentGUI && ($a_par["tpl_id"] === self::TEMPLATE_ID_PAGE_CONTENT || $a_par["tpl_id"] === null) && $a_part === self::TEMPLATE_SHOW
        ) {
            $html = $a_par["html"];

            $html = $this->replaceFileIconsCurrentObject($html, '/\\s+class\\s*=\\s*["\']media il_HeaderInner["\']/');

            return [
                "mode" => self::REPLACE,
                "html" => $html
            ];
        }

        return parent::getHTML($a_comp, $a_part, $a_par);
    }


    /**
     * @param string $html
     * @param string $start_pos_reg_exp
     *
     * @return string
     */
    protected function replaceFileIconsCurrentObject(string $html, string $start_pos_reg_exp) : string
    {
        $start_pos = [];

        preg_match($start_pos_reg_exp, $html, $start_pos, PREG_OFFSET_CAPTURE);
        if (is_array($start_pos) && count($start_pos) >= 1 && is_array($start_pos[count($start_pos) - 1])) {
            $start_pos = intval($start_pos[0][1]);

            $html = self::srFileObjectTypeIcons()->fileTypeIcons()->replaceFileIcons($html, self::getCurrentRefId(), true, $start_pos, false);
        }

        return $html;
    }
}
