<?php

namespace srag\Plugins\SrFileObjectTypeIcons\FileTypeIcon;

use ActiveRecord;
use arConnector;
use ILIAS\FileUpload\DTO\UploadResult;
use ILIAS\FileUpload\Location;
use ILIAS\UI\Component\Component;
use ilSrFileObjectTypeIconsPlugin;
use srag\DIC\SrFileObjectTypeIcons\DICTrait;
use srag\Plugins\SrFileObjectTypeIcons\Utils\SrFileObjectTypeIconsTrait;

/**
 * Class FileTypeIcon
 *
 * @package srag\Plugins\SrFileObjectTypeIcons\FileTypeIcon
 */
class FileTypeIcon extends ActiveRecord
{

    use DICTrait;
    use SrFileObjectTypeIconsTrait;

    const ICON_PREFIX = "icon_";
    const PLUGIN_CLASS_NAME = ilSrFileObjectTypeIconsPlugin::class;
    const TABLE_NAME = ilSrFileObjectTypeIconsPlugin::PLUGIN_ID . "_icon";
    /**
     * @var array
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_is_notnull   true
     */
    protected $extensions = [];
    /**
     * @var int
     *
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   true
     * @con_is_primary   true
     * @con_sequence     true
     */
    protected $file_type_icon_id;
    /**
     * @var string
     *
     * @con_has_field   true
     * @con_fieldtype   text
     * @con_is_notnull  true
     */
    protected $icon = "";


    /**
     * FileTypeIcon constructor
     *
     * @param int              $primary_key_value
     * @param arConnector|null $connector
     */
    public function __construct(/*int*/ $primary_key_value = 0, /*?*/ arConnector $connector = null)
    {
        parent::__construct($primary_key_value, $connector);
    }


    /**
     * @inheritDoc
     *
     * @deprecated
     */
    public static function returnDbTableName() : string
    {
        return self::TABLE_NAME;
    }


    /**
     * @param UploadResult|null $upload_result
     */
    public function applyNewIcon(/*?*/ UploadResult $upload_result = null) : void
    {
        if (!empty($this->getIcon())) {
            if (file_exists($icon_old_path = $this->getIconPath())) {
                unlink($icon_old_path);
            }
            $this->setIcon("");
        }

        if ($upload_result !== null) {
            if (intval($upload_result->getSize()) === 0) {
                return;
            }

            $file_name = $this->file_type_icon_id . "." . pathinfo($upload_result->getName(), PATHINFO_EXTENSION);

            self::dic()->upload()->moveOneFileTo($upload_result, $this->getIconPathAsRelative(false), Location::WEB, $file_name, true);

            $this->setIcon($file_name);
        }
    }


    /**
     * @return Component[]
     */
    public function getActions() : array
    {
        self::dic()->ctrl()->setParameterByClass(FileTypeIconGUI::class, FileTypeIconGUI::GET_PARAM_FILE_TYPE_ICON_ID, $this->file_type_icon_id);

        return [
            self::dic()->ui()->factory()->link()->standard(self::plugin()->translate("edit_file_type_icon", FileTypeIconsGUI::LANG_MODULE),
                self::dic()->ctrl()->getLinkTargetByClass(FileTypeIconGUI::class, FileTypeIconGUI::CMD_EDIT_FILE_TYPE_ICON, "", false, false)),
            self::dic()->ui()->factory()->link()->standard(self::plugin()->translate("remove_file_type_icon", FileTypeIconsGUI::LANG_MODULE),
                self::dic()->ctrl()->getLinkTargetByClass(FileTypeIconGUI::class, FileTypeIconGUI::CMD_REMOVE_FILE_TYPE_ICON_CONFIRM, "", false, false))
        ];
    }


    /**
     * @inheritDoc
     */
    public function getConnectorContainerName() : string
    {
        return self::TABLE_NAME;
    }


    /**
     * @return array
     */
    public function getExtensions() : array
    {
        return $this->extensions;
    }


    /**
     * @param array $extensions
     */
    public function setExtensions(array $extensions) : void
    {
        $extensions = array_map(function (string $extension) : string {
            $extension = ltrim(trim(strtolower($extension)), ".");

            return $extension;
        }, $extensions);

        usort($extensions, "strnatcasecmp");

        $this->extensions = $extensions;
    }


    /**
     * @return int
     */
    public function getFileTypeIconId() : int
    {
        return $this->file_type_icon_id;
    }


    /**
     * @param int $file_type_icon_id
     */
    public function setFileTypeIconId(int $file_type_icon_id) : void
    {
        $this->file_type_icon_id = $file_type_icon_id;
    }


    /**
     * @return string
     *
     */
    public function getIcon() : string
    {
        return $this->icon;
    }


    /**
     * @param string $icon
     */
    public function setIcon(string $icon) : void
    {
        $this->icon = $icon;
    }


    /**
     * @return string
     */
    public function getIconPath() : string
    {
        return ILIAS_WEB_DIR . "/" . CLIENT_ID . "/" . $this->getIconPathAsRelative();
    }


    /**
     * @param bool $append_filename
     *
     * @return string
     */
    public function getIconPathAsRelative(bool $append_filename = true) : string
    {
        $path = ilSrFileObjectTypeIconsPlugin::WEB_DATA_FOLDER . "/" . static::ICON_PREFIX . $this->file_type_icon_id . "/";

        if ($append_filename) {
            $path .= $this->getIcon();
        }

        return $path;
    }


    /**
     * @return string
     */
    public function getIconPathWithCheck() : string
    {
        if (!empty($this->getIcon())) {
            if (file_exists($icon_path = $this->getIconPath())) {
                return $icon_path;
            }
        }

        return "";
    }


    /**
     * @inheritDoc
     */
    public function sleep(/*string*/ $field_name)
    {
        $field_value = $this->{$field_name};

        switch ($field_name) {
            case "extensions":
                return json_encode($field_value);

            default:
                return parent::sleep($field_name);
        }
    }


    /**
     * @inheritDoc
     */
    public function wakeUp(/*string*/ $field_name, $field_value)
    {
        switch ($field_name) {
            case "extensions":
                return json_decode($field_value, true);

            default:
                return parent::wakeUp($field_name, $field_value);
        }
    }
}
