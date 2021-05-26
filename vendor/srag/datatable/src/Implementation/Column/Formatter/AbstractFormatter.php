<?php

namespace srag\DataTableUI\SrFileObjectTypeIcons\Implementation\Column\Formatter;

use srag\DataTableUI\SrFileObjectTypeIcons\Component\Column\Formatter\Formatter;
use srag\DataTableUI\SrFileObjectTypeIcons\Implementation\Utils\DataTableUITrait;
use srag\DIC\SrFileObjectTypeIcons\DICTrait;

/**
 * Class AbstractFormatter
 *
 * @package srag\DataTableUI\SrFileObjectTypeIcons\Implementation\Column\Formatter
 */
abstract class AbstractFormatter implements Formatter
{

    use DICTrait;
    use DataTableUITrait;

    /**
     * AbstractFormatter constructor
     */
    public function __construct()
    {

    }
}
