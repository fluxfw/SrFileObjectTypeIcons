<?php

namespace srag\CustomInputGUIs\SrFileObjectTypeIcons\HiddenInputGUI;

use ilHiddenInputGUI;
use srag\CustomInputGUIs\SrFileObjectTypeIcons\Template\Template;
use srag\DIC\SrFileObjectTypeIcons\DICTrait;

/**
 * Class HiddenInputGUI
 *
 * @package srag\CustomInputGUIs\SrFileObjectTypeIcons\HiddenInputGUI
 */
class HiddenInputGUI extends ilHiddenInputGUI
{

    use DICTrait;

    /**
     * HiddenInputGUI constructor
     *
     * @param string $a_postvar
     */
    public function __construct(string $a_postvar = "")
    {
        parent::__construct($a_postvar);
    }


    /**
     * @return string
     */
    public function render() : string
    {
        $tpl = new Template("Services/Form/templates/default/tpl.property_form.html", true, true);

        $this->insert($tpl);

        return self::output()->getHTML($tpl);
    }
}
