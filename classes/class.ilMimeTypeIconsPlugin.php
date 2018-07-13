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

	const PLUGIN_ID = 'mimetypeicons';
	const PLUGIN_NAME = 'MimeTypeIcons';
	/**
	 * @var ilMimeTypeIconsPlugin
	 */
	protected static $instance;


	/**
	 * @return ilMimeTypeIconsPlugin
	 */
	public static function getInstance() {
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * @return string
	 */
	public function getPluginName() {
		return self::PLUGIN_NAME;
	}


	/**
	 * @return bool
	 */
	protected function beforeUninstall() {
		return true;
	}
}
