<?php

namespace srag\Plugins\SrFileObjectTypeIcons\FileTypeIcon;

use ILIAS\FileUpload\DTO\UploadResult;
use ilImageFileInputGUI;
use ilSrFileObjectTypeIconsPlugin;
use ilTextInputGUI;
use srag\CustomInputGUIs\SrFileObjectTypeIcons\MultiLineNewInputGUI\MultiLineNewInputGUI;
use srag\CustomInputGUIs\SrFileObjectTypeIcons\PropertyFormGUI\Items\Items;
use srag\CustomInputGUIs\SrFileObjectTypeIcons\PropertyFormGUI\PropertyFormGUI;
use srag\Plugins\SrFileObjectTypeIcons\Utils\SrFileObjectTypeIconsTrait;

/**
 * Class FileTypeIconFormGUI
 *
 * @package srag\Plugins\SrFileObjectTypeIcons\FileTypeIcon
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class FileTypeIconFormGUI extends PropertyFormGUI
{

    use SrFileObjectTypeIconsTrait;

    const LANG_MODULE = FileTypeIconsGUI::LANG_MODULE;
    const PLUGIN_CLASS_NAME = ilSrFileObjectTypeIconsPlugin::class;
    /**
     * @var FileTypeIcon
     */
    protected $file_type_icon;


    /**
     * @inheritDoc
     *
     * @param FileTypeIconGUI $parent
     * @param FileTypeIcon    $file_type_icon
     */
    public function __construct(FileTypeIconGUI $parent, FileTypeIcon $file_type_icon)
    {
        $this->file_type_icon = $file_type_icon;

        parent::__construct($parent);
    }


    /**
     * @inheritDoc
     */
    public function storeForm() : bool
    {
        if (empty($this->file_type_icon->getFileTypeIconId())) {
            self::srFileObjectTypeIcons()->fileTypeIcons()->storeFileTypeIcon($this->file_type_icon);
        }

        if (!parent::storeForm()) {
            return false;
        }

        self::srFileObjectTypeIcons()->fileTypeIcons()->storeFileTypeIcon($this->file_type_icon);

        return true;
    }


    /**
     * @inheritDoc
     */
    protected function getValue(string $key)
    {
        switch ($key) {
            case "extensions":
                return array_map(function (string $extension) : array {
                    return [
                        "extension" => $extension
                    ];
                }, Items::getter($this->file_type_icon, $key));

            case "icon":
                if (!empty(Items::getter($this->file_type_icon, $key))) {
                    return "./" . $this->file_type_icon->getIconPath();
                }
                break;

            default:
                return Items::getter($this->file_type_icon, $key);
        }

        return null;
    }


    /**
     * @inheritDoc
     */
    protected function initCommands()/*: void*/
    {
        if (!empty($this->file_type_icon->getFileTypeIconId())) {
            $this->addCommandButton(FileTypeIconGUI::CMD_UPDATE_FILE_TYPE_ICON, $this->txt("save"));
        } else {
            $this->addCommandButton(FileTypeIconGUI::CMD_CREATE_FILE_TYPE_ICON, $this->txt("add"));
            $this->addCommandButton(FileTypeIconGUI::CMD_BACK, $this->txt("cancel"));
        }
    }


    /**
     * @inheritDoc
     */
    protected function initFields()/*: void*/
    {
        $this->fields = [
            "icon"       => [
                self::PROPERTY_CLASS    => ilImageFileInputGUI::class,
                self::PROPERTY_REQUIRED => empty($this->file_type_icon->getIcon()),
                "setSuffixes"           => [["gif", "jpg", "jpeg", "png", "svg"]],
                "setAllowDeletion"      => false
            ],
            "extensions" => [
                self::PROPERTY_CLASS    => MultiLineNewInputGUI::class,
                self::PROPERTY_REQUIRED => true,
                self::PROPERTY_SUBITEMS => [
                    "extension" => [
                        self::PROPERTY_CLASS    => ilTextInputGUI::class,
                        self::PROPERTY_REQUIRED => true
                    ]
                ],
                "setShowSort"           => false
            ]
        ];
    }


    /**
     * @inheritDoc
     */
    protected function initId()/*: void*/
    {

    }


    /**
     * @inheritDoc
     */
    protected function initTitle()/*: void*/
    {
        if (!empty($this->file_type_icon->getFileTypeIconId())) {
            $this->setTitle($this->txt("edit_file_type_icon"));
        } else {
            $this->setTitle($this->txt("add_file_type_icon"));
        }
    }


    /**
     * @inheritDoc
     */
    protected function storeValue(string $key, $value)/*: void*/
    {
        switch ($key) {
            case "extensions":
                Items::setter($this->file_type_icon, $key, array_map(function (array $extension) : string {
                    return $extension["extension"];
                }, $value));
                break;

            case "icon":
                if (!self::dic()->upload()->hasBeenProcessed()) {
                    self::dic()->upload()->process();
                }

                /** @var UploadResult $upload_result */
                $upload_result = array_pop(self::dic()->upload()->getResults());

                if (intval($upload_result->getSize()) === 0) {
                    return;
                }

                $this->file_type_icon->applyNewIcon($upload_result);
                break;

            default:
                Items::setter($this->file_type_icon, $key, $value);
                break;
        }
    }
}
