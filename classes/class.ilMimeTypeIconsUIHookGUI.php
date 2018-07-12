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
	 * @param       $a_comp
	 * @param       $a_part
	 * @param array $a_par
	 *
	 * @return array
	 */
	public function getHTML($a_comp, $a_part, $a_par = array()) {
		if ($a_comp == 'Services/Object') {
			/**
			 * @var $gui ilTemplate
			 *
			 *
			 * ilObjectListGUI, Zeile 3590 (ILIAS 4.4)
			 * // FSX Patch A2WP1.3
			include_once('./Services/UIComponent/classes/class.ilUIHookProcessor.php');
			$uih = new ilUIHookProcessor('Services/Object', 'list', array('list_gui' => $this->tpl, 'type' => $type, 'ref_id' => $a_ref_id));
			return $uih->getHTML($this->tpl->get());
			// END FSX Patch A2WP1.3
			 */
			if ($a_part == 'list' AND $a_par['type'] == 'file') {
				$fileObj = new ilObjFile($a_par['ref_id']);
				$fileObj->getFileExtension();
				$icon_path = ilUtil::getImagePath('icon_' . $fileObj->getFileExtension() . '.png');
				if ($_GET['baseClass'] != 'ilSearchController') {
					if (@file_exists($icon_path)) {
						$gui = $a_par['list_gui'];
						if ($a_par['list_gui']) {
							$gui->blockdata['icon'] = '';
							if ($_SESSION['il_cont_admin_panel']) {
								$gui->touchBlock('i_1');
							}
							$gui->setCurrentBlock('icon');
							$gui->setVariable('SRC_ICON', $icon_path);
							$gui->parseCurrentBlock();

							return array( 'mode' => ilUIHookPluginGUI::REPLACE, 'html' => $gui->get() );
						}
					}
				}
			}
			/**
			 * Hook einsetzen, bspw. ilSearchResultTableGUI Zeile 118
			 *
			 * // FSX Patch A2W1.3
			 * include_once('./Services/UIComponent/classes/class.ilUIHookProcessor.php');
			 * $uih = new ilUIHookProcessor('Services/Object', 'selection_list', array('image' => $item["img"], 'title' => $item["title"]));
			 * $item["img"] = $uih->getHTML($item["img"]);
			 * // FSX END Patch A2W1.3
			 */
			if ($a_part == 'selection_list' OR $a_part == 'search_result') {
				$img = basename($a_par['image']);
				if (strpos($img, 'file')) {
					$extension = str_replace('.', '', strrchr($a_par['title'], '.'));
					$icon_path = ilUtil::getImagePath('icon_' . $extension . '_s.png');
					if (@file_exists($icon_path)) {
						return array( 'mode' => ilUIHookPluginGUI::REPLACE, 'html' => $icon_path );
					}
				}
			}
		}

		return array( 'mode' => ilUIHookPluginGUI::KEEP, 'html' => '' );
	}


	/**
	 * @param       $a_comp
	 * @param       $a_part
	 * @param array $a_par
	 */
	public function modifyGUI($a_comp, $a_part, $a_par = array()) {
		//		echo '<pre>' . print_r($a_par, 1) . '</pre>';
	}
}

?>
