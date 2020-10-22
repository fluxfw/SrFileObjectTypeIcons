<?php

namespace srag\Plugins\SrFileObjectTypeIcons;

use ilSrFileObjectTypeIconsPlugin;
use srag\DIC\SrFileObjectTypeIcons\DICTrait;
use srag\Plugins\SrFileObjectTypeIcons\FileTypeIcon\Repository as FileTypeIconRepository;
use srag\Plugins\SrFileObjectTypeIcons\Utils\SrFileObjectTypeIconsTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\SrFileObjectTypeIcons
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository
{

    use DICTrait;
    use SrFileObjectTypeIconsTrait;

    const PLUGIN_CLASS_NAME = ilSrFileObjectTypeIconsPlugin::class;
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * Repository constructor
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
     *
     */
    public function dropTables()/*:void*/
    {
        $this->fileTypeIcons()->dropTables();
    }


    /**
     * @return FileTypeIconRepository
     */
    public function fileTypeIcons() : FileTypeIconRepository
    {
        return FileTypeIconRepository::getInstance();
    }


    /**
     *
     */
    public function installTables()/*:void*/
    {
        $this->fileTypeIcons()->installTables();
    }
}
