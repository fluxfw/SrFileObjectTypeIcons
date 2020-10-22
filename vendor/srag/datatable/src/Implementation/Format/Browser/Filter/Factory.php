<?php

namespace srag\DataTableUI\SrFileObjectTypeIcons\Implementation\Format\Browser\Filter;

use srag\CustomInputGUIs\SrFileObjectTypeIcons\FormBuilder\FormBuilder as FormBuilderInterface;
use srag\DataTableUI\SrFileObjectTypeIcons\Component\Format\Browser\BrowserFormat;
use srag\DataTableUI\SrFileObjectTypeIcons\Component\Format\Browser\Filter\Factory as FactoryInterface;
use srag\DataTableUI\SrFileObjectTypeIcons\Component\Settings\Settings;
use srag\DataTableUI\SrFileObjectTypeIcons\Component\Table;
use srag\DataTableUI\SrFileObjectTypeIcons\Implementation\Utils\DataTableUITrait;
use srag\DIC\SrFileObjectTypeIcons\DICTrait;

/**
 * Class Factory
 *
 * @package srag\DataTableUI\SrFileObjectTypeIcons\Implementation\Format\Browser\Filter
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Factory implements FactoryInterface
{

    use DICTrait;
    use DataTableUITrait;

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
     * @inheritDoc
     */
    public function formBuilder(BrowserFormat $parent, Table $component, Settings $settings) : FormBuilderInterface
    {
        return new FormBuilder($parent, $component, $settings);
    }
}
