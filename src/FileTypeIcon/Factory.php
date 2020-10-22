<?php

namespace srag\Plugins\SrFileObjectTypeIcons\FileTypeIcon;

use ilSrFileObjectTypeIconsPlugin;
use srag\DIC\SrFileObjectTypeIcons\DICTrait;
use srag\Plugins\SrFileObjectTypeIcons\FileTypeIcon\Table\TableBuilder;
use srag\Plugins\SrFileObjectTypeIcons\Utils\SrFileObjectTypeIconsTrait;

/**
 * Class Factory
 *
 * @package srag\Plugins\SrFileObjectTypeIcons\FileTypeIcon
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Factory
{

    use DICTrait;
    use SrFileObjectTypeIconsTrait;

    const PLUGIN_CLASS_NAME = ilSrFileObjectTypeIconsPlugin::class;
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * Factory constructor
     */
    private function __construct()
    {

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
     * @param FileTypeIconGUI $parent
     * @param FileTypeIcon    $file_type_icon
     *
     * @return FileTypeIconFormGUI
     */
    public function newFormInstance(FileTypeIconGUI $parent, FileTypeIcon $file_type_icon) : FileTypeIconFormGUI
    {
        $form = new FileTypeIconFormGUI($parent, $file_type_icon);

        return $form;
    }


    /**
     * @return FileTypeIcon
     */
    public function newInstance() : FileTypeIcon
    {
        $file_type_icon = new FileTypeIcon();

        return $file_type_icon;
    }


    /**
     * @param FileTypeIconsGUI $parent
     *
     * @return TableBuilder
     */
    public function newTableBuilderInstance(FileTypeIconsGUI $parent) : TableBuilder
    {
        $table = new TableBuilder($parent);

        return $table;
    }
}
