<?php

require_once('./Services/UIComponent/classes/class.ilUserInterfaceHookPlugin.php');

/**
 * ilMimeTypeIconsPlugin
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version $Id$
 *
 */
class ilMimeTypeIconsPlugin extends ilUserInterfaceHookPlugin {

	/**
	 * @return string
	 */
	function getPluginName() {
		return 'MimeTypeIcons';
	}
}

?>
