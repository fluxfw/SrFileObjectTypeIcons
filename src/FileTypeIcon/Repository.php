<?php

namespace srag\Plugins\SrFileObjectTypeIcons\FileTypeIcon;

use ilObjFile;
use ilSrFileObjectTypeIconsPlugin;
use ilUtil;
use srag\DIC\SrFileObjectTypeIcons\DICTrait;
use srag\Plugins\SrFileObjectTypeIcons\Utils\SrFileObjectTypeIconsTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\SrFileObjectTypeIcons\FileTypeIcon
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
     * @var array
     */
    protected $file_object_extension = [];
    /**
     * @var array
     */
    protected $file_type_icon_by_extension = [];
    /**
     * @var array
     */
    protected $file_type_icon_by_id = [];


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
     * @param FileTypeIcon $file_type_icon
     */
    public function deleteFileTypeIcon(FileTypeIcon $file_type_icon) : void
    {
        $file_type_icon->applyNewIcon();

        $file_type_icon->delete();
    }


    /**
     * @internal
     */
    public function dropTables() : void
    {
        self::dic()->database()->dropTable(FileTypeIcon::TABLE_NAME, false);
        ilUtil::delDir(ILIAS_WEB_DIR . "/" . CLIENT_ID . "/" . ilSrFileObjectTypeIconsPlugin::WEB_DATA_FOLDER);
    }


    /**
     * @return Factory
     */
    public function factory() : Factory
    {
        return Factory::getInstance();
    }


    /**
     * @param string $extension
     *
     * @return FileTypeIcon|null
     */
    public function getFileTypeIconByExtension(string $extension) : ?FileTypeIcon
    {
        /**
         * @var FileTypeIcon|null $file_type_icon
         */

        $file_type_icon = $this->file_type_icon_by_extension[$extension];

        if ($file_type_icon === null) {
            $file_type_icon = FileTypeIcon::where(["extensions" => '%"' . $extension . '"%'], "LIKE")->orderBy("extensions", "asc")->first();

            $this->file_type_icon_by_extension[$extension] = $file_type_icon;

            if ($file_type_icon !== null) {
                $this->file_type_icon_by_id[$file_type_icon->getFileTypeIconId()] = $file_type_icon;
            }
        }

        return $file_type_icon;
    }


    /**
     * @param int $file_type_icon_id
     *
     * @return FileTypeIcon|null
     */
    public function getFileTypeIconById(int $file_type_icon_id) : ?FileTypeIcon
    {
        /**
         * @var FileTypeIcon|null $file_type_icon
         */

        $file_type_icon = $this->file_type_icon_by_id[$file_type_icon_id];

        if ($file_type_icon === null) {
            $file_type_icon = FileTypeIcon::where(["file_type_icon_id" => $file_type_icon_id])->first();

            $this->file_type_icon_by_id[$file_type_icon_id] = $file_type_icon;
        }

        return $file_type_icon;
    }


    /**
     * @return FileTypeIcon[]
     */
    public function getFileTypeIcons() : array
    {
        return FileTypeIcon::orderBy("extensions", "asc")->get();
    }


    /**
     * @internal
     */
    public function installTables() : void
    {
        FileTypeIcon::updateDB();
    }


    /**
     * @param string $html
     * @param int    $obj_id
     * @param bool   $is_ref_id
     * @param int    $start_pos
     * @param bool   $before
     *
     * @return string
     */
    public function replaceFileIcons(string $html, int $obj_id, bool $is_ref_id, int $start_pos, bool $before) : string
    {
        if ($before) {
            $img_start_pos = strrpos(substr($html, 0, $start_pos), "<img");
        } else {
            $img_start_pos = strpos($html, "<img", $start_pos);
        }
        if ($img_start_pos === false) {
            return $html;
        }

        $img_end_pos = strpos($html, ">", $img_start_pos);
        if ($img_end_pos === false) {
            return $html;
        }
        $img_end_pos += 1;

        $part_html = $part_html_org = substr($html, $img_start_pos, ($img_end_pos - $img_start_pos));

        $part_html = $this->replaceFileIconsPart($part_html, $obj_id, $is_ref_id);

        if ($part_html === $part_html_org) {
            return $html;
        }

        $html = substr($html, 0, $img_start_pos) . $part_html . substr($html, $img_end_pos);

        return $html;
    }


    /**
     * @param string $html
     * @param string $obj_id_reg_exp
     * @param bool   $is_ref_id
     * @param bool   $before
     *
     * @return string
     */
    public function replaceFileIconsMultipleObjects(string $html, string $obj_id_reg_exp, bool $is_ref_id, bool $before) : string
    {
        $obj_ids = [];
        preg_match_all($obj_id_reg_exp, $html, $obj_ids, PREG_OFFSET_CAPTURE);
        if (is_array($obj_ids) && count($obj_ids) >= 2 && is_array($obj_ids[count($obj_ids) - 1])) {

            foreach ($obj_ids[count($obj_ids) - 1] as $obj_id) {
                $start_pos = intval($obj_id[1]);
                $obj_id = intval($obj_id[0]);

                $html = $this->replaceFileIcons($html, $obj_id, $is_ref_id, $start_pos, $before);
            }
        }

        return $html;
    }


    /**
     * @param string $part_html
     * @param int    $obj_id
     * @param bool   $is_ref_id
     *
     * @return string
     */
    public function replaceFileIconsPart(string $part_html, int $obj_id, bool $is_ref_id) : string
    {
        global $DIC;

        if ($is_ref_id) {
            $obj_id = $DIC["ilObjDataCache"]->lookupObjId($obj_id);
        }

        if ($DIC["ilObjDataCache"]->lookupType($obj_id) !== "file") {
            return $part_html;
        }

        $extension = $this->getFileObjectExtension($obj_id);
        $file_type_icon = $this->getFileTypeIconByExtension($extension);
        if ($file_type_icon === null) {
            return $part_html;
        }
        $ext_icon_path = $file_type_icon->getIconPathWithCheck();
        if (empty($ext_icon_path)) {
            return $part_html;
        }

        $icon_paths = array_unique([
            "./templates/default/images/icon_file.svg",
            ilUtil::getImagePath("icon_file.svg"),

            "./templates/default/images/icon_file_inline.svg",
            ilUtil::getImagePath("icon_file_inline.svg")
        ]);

        foreach ($icon_paths as $icon_path) {
            $part_html = str_replace($icon_path, $ext_icon_path, $part_html);
        }

        return $part_html;
    }


    /**
     * @param FileTypeIcon $file_type_icon
     */
    public function storeFileTypeIcon(FileTypeIcon $file_type_icon) : void
    {
        $file_type_icon->store();

        $this->file_type_icon_by_extension = [];
        $this->file_type_icon_by_id = [];
    }


    /**
     * @param string $obj_id
     *
     * @return string
     */
    protected function getFileObjectExtension(string $obj_id) : string
    {
        $extension = $this->file_object_extension[$obj_id];

        if ($extension === null) {
            $obj = new ilObjFile($obj_id, false);

            $extension = strval($obj->getFileExtension());

            $this->file_object_extension[$obj_id] = $extension;
        }

        return $extension;
    }
}
