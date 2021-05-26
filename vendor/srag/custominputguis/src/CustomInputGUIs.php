<?php

namespace srag\CustomInputGUIs\SrFileObjectTypeIcons;

use srag\CustomInputGUIs\SrFileObjectTypeIcons\ViewControlModeUI\ViewControlModeUI;
use srag\DIC\SrFileObjectTypeIcons\DICTrait;

/**
 * Class CustomInputGUIs
 *
 * @package srag\CustomInputGUIs\SrFileObjectTypeIcons
 */
final class CustomInputGUIs
{

    use DICTrait;

    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * CustomInputGUIs constructor
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
     * @return ViewControlModeUI
     */
    public function viewControlMode() : ViewControlModeUI
    {
        return new ViewControlModeUI();
    }
}
