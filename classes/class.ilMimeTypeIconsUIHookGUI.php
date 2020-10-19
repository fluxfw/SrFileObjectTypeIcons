<?php

/* Copyright (c) 1998-2010 ILIAS open source, Extended GPL, see docs/LICENSE */

require_once('./Services/UIComponent/classes/class.ilUIHookPluginGUI.php');
require_once('./Modules/File/classes/class.ilObjFile.php');

/**
 * User interface hook class
 *
 * @author  Alex Killing <alex.killing@gmx.de>
 * @author  fabian Schmid <fs@studer-raimann.ch>
 * @version $Id$
 * @ingroup ServicesUIComponent
 */
class ilMimeTypeIconsUIHookGUI extends ilUIHookPluginGUI {

	/**
	 * @param string $a_comp
	 * @param string $a_part
	 * @param array  $a_par
	 *
	 * @return array
	 */
	public function getHTML($a_comp, $a_part, $a_par = array()) {
        if ($a_par["tpl_id"] === "Services/Container/tpl.container_list_item.html" && $a_part === "template_get") {
            $html = $a_par["html"];

            $html = $this->replaceFileIconsMultipleObjects($html, '/\\s+(data-list-item-)?id\\s*=\\s*["\']{1}lg_div_([0-9]+)/', true, false);

            return ["mode" => self::REPLACE, "html" => $html];
        }

        if ($a_par["tpl_id"] === "Services/Table/tpl.table2.html" && $a_part === "template_get") {
            $html = $a_par["html"];

            $html = $this->replaceFileIconsMultipleObjects($html, '/\\s+(data-list-item-)?id\\s*=\\s*["\']{1}lg_div_([0-9]+)/', true, true);

            return ["mode" => self::REPLACE, "html" => $html];
        }

        if ($a_par["tpl_id"] === "Services/MainMenu/tpl.main_menu.html" && $a_part === "template_get") {
            $html = $a_par["html"];

            $html = $this->replaceFileIconsMultipleObjects($html, '/\\s+id\\s*=\\s*["\']mm_last_visited_([0-9]+)/', false, false);

            return ["mode" => self::REPLACE, "html" => $html];
        }

        if ($a_par["tpl_id"] === "tpl.main.html" && $a_part === "template_show") {
            $html = $a_par["html"];

            $html = $this->replaceFileIconsCurrentObject($html, '/\\s+class\\s*=\\s*["\']media il_HeaderInner["\']/');

            return ["mode" => self::REPLACE, "html" => $html];
        }

		return array( 'mode' => ilUIHookPluginGUI::KEEP, 'html' => '' );
	}


	/**
	 * @param string $a_comp
	 * @param string $a_part
	 * @param array  $a_par
	 */
	public function modifyGUI($a_comp, $a_part, $a_par = array()) {
		//		echo '<pre>' . print_r($a_par, 1) . '</pre>';
	}


    /**
     * @param string $html
     * @param string $obj_id_reg_exp
     * @param bool   $is_ref_id
     * @param bool   $before
     *
     * @return string
     */
    protected function replaceFileIconsMultipleObjects(string $html, string $obj_id_reg_exp, bool $is_ref_id,bool $before) : string
    {
        $obj_ids = [];
        preg_match_all($obj_id_reg_exp, $html, $obj_ids, PREG_OFFSET_CAPTURE);
        if (is_array($obj_ids) && count($obj_ids) >= 2 && is_array($obj_ids[count($obj_ids) - 1])) {

            foreach ($obj_ids[count($obj_ids) - 1] as $obj_id) {
                $start_pos = intval($obj_id[1]);
                $obj_id = intval($obj_id[0]);

                $html = $this->replaceFileIcons($html, $obj_id, $is_ref_id, $start_pos,$before);
            }
        }

        return $html;
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

            $html = $this->replaceFileIcons($html, intval(filter_input(INPUT_GET, "ref_id")), true, $start_pos,false);
        }

        return $html;
    }


    /**
     * @param string $html
     * @param int    $obj_id
     * @param bool   $is_ref_id
     * @param int    $start_pos
     * @param bool   $before
     *
     * @return string
     */
    protected function replaceFileIcons(string $html, int $obj_id, bool $is_ref_id, int $start_pos,bool $before) : string
    {
        global $DIC;

        if ($is_ref_id) {
            $obj_id = $DIC["ilObjDataCache"]->lookupObjId($obj_id);
        }

        if ($DIC["ilObjDataCache"]->lookupType($obj_id) !== "file") {
            return $html;
        }

        $fileObj = new ilObjFile($obj_id, false);
        $ext_icon_path = ilUtil::getImagePath('icon_' . $fileObj->getFileExtension() . '.png');
        if (!file_exists($ext_icon_path)) {
            return $html;
        }

        if($before) {
            $img_start_pos = strrpos(substr($html,0,$start_pos), "<img");
        } else {
            $img_start_pos = strpos($html, "<img", $start_pos);
        }
        if ($img_start_pos === false) {
            return $html;
        }

        $img_end_pos = strpos($html, ">", $img_start_pos);
        if ($img_end_pos === false) {
            return $html;
        }
        $img_end_pos += 1;

        $part_html = $part_html_org = substr($html, $img_start_pos, ($img_end_pos - $img_start_pos));

        $icon_paths = array_unique(["./templates/default/images/icon_file.svg", ilUtil::getImagePath("icon_file.svg")]);

        foreach ($icon_paths as $icon_path) {
            $part_html = str_replace($icon_path, $ext_icon_path, $part_html);
        }

        if ($part_html === $part_html_org) {
            return $html;
        }

        $html = substr($html, 0, $img_start_pos) . $part_html . substr($html, $img_end_pos);

        return $html;
    }
}
