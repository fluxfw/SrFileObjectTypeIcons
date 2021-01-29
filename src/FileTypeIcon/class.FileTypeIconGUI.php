<?php

namespace srag\Plugins\SrFileObjectTypeIcons\FileTypeIcon;

require_once __DIR__ . "/../../vendor/autoload.php";

use ilConfirmationGUI;
use ilSrFileObjectTypeIconsPlugin;
use ilUtil;
use srag\DIC\SrFileObjectTypeIcons\DICTrait;
use srag\Plugins\SrFileObjectTypeIcons\Utils\SrFileObjectTypeIconsTrait;

/**
 * Class FileTypeIconGUI
 *
 * @package           srag\Plugins\SrFileObjectTypeIcons\FileTypeIcon
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\SrFileObjectTypeIcons\FileTypeIcon\FileTypeIconGUI: srag\Plugins\SrFileObjectTypeIcons\FileTypeIcon\FileTypeIconsGUI
 */
class FileTypeIconGUI
{

    use DICTrait;
    use SrFileObjectTypeIconsTrait;

    const CMD_ADD_FILE_TYPE_ICON = "addFileTypeIcon";
    const CMD_BACK = "back";
    const CMD_CREATE_FILE_TYPE_ICON = "createFileTypeIcon";
    const CMD_EDIT_FILE_TYPE_ICON = "editFileTypeIcon";
    const CMD_REMOVE_FILE_TYPE_ICON = "removeFileTypeIcon";
    const CMD_REMOVE_FILE_TYPE_ICON_CONFIRM = "removeFileTypeIconConfirm";
    const CMD_UPDATE_FILE_TYPE_ICON = "updateFileTypeIcon";
    const GET_PARAM_FILE_TYPE_ICON_ID = "file_type_icon_id";
    const PLUGIN_CLASS_NAME = ilSrFileObjectTypeIconsPlugin::class;
    const TAB_EDIT_FILE_TYPE_ICON = "edit_file_type_icon";
    /**
     * @var FileTypeIcon
     */
    protected $file_type_icon;


    /**
     * FileTypeIconGUI constructor
     */
    public function __construct()
    {

    }


    /**
     *
     */
    public function executeCommand()/*: void*/
    {
        $this->file_type_icon = self::srFileObjectTypeIcons()->fileTypeIcons()->getFileTypeIconById(intval(filter_input(INPUT_GET, self::GET_PARAM_FILE_TYPE_ICON_ID)));

        self::dic()->ctrl()->saveParameter($this, self::GET_PARAM_FILE_TYPE_ICON_ID);

        $this->setTabs();

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_ADD_FILE_TYPE_ICON:
                    case self::CMD_BACK:
                    case self::CMD_CREATE_FILE_TYPE_ICON:
                    case self::CMD_EDIT_FILE_TYPE_ICON:
                    case self::CMD_REMOVE_FILE_TYPE_ICON:
                    case self::CMD_REMOVE_FILE_TYPE_ICON_CONFIRM:
                    case self::CMD_UPDATE_FILE_TYPE_ICON:
                        $this->{$cmd}();
                        break;

                    default:
                        break;
                }
                break;
        }
    }


    /**
     *
     */
    protected function addFileTypeIcon()/*: void*/
    {
        $form = self::srFileObjectTypeIcons()->fileTypeIcons()->factory()->newFormInstance($this, $this->file_type_icon);

        self::output()->output($form);
    }


    /**
     *
     */
    protected function back()/*: void*/
    {
        self::dic()->ctrl()->redirectByClass(FileTypeIconsGUI::class, FileTypeIconsGUI::CMD_LIST_FILE_TYPE_ICONS);
    }


    /**
     *
     */
    protected function createFileTypeIcon()/*: void*/
    {
        $form = self::srFileObjectTypeIcons()->fileTypeIcons()->factory()->newFormInstance($this, $this->file_type_icon);

        if (!$form->storeForm()) {
            self::output()->output($form);

            return;
        }

        self::dic()->ctrl()->setParameter($this, self::GET_PARAM_FILE_TYPE_ICON_ID, $this->file_type_icon->getFileTypeIconId());

        ilUtil::sendSuccess(self::plugin()->translate("added_file_type_icon", FileTypeIconsGUI::LANG_MODULE), true);

        self::dic()->ctrl()->redirect($this, self::CMD_BACK);
    }


    /**
     *
     */
    protected function editFileTypeIcon()/*: void*/
    {
        self::dic()->tabs()->activateTab(self::TAB_EDIT_FILE_TYPE_ICON);

        $form = self::srFileObjectTypeIcons()->fileTypeIcons()->factory()->newFormInstance($this, $this->file_type_icon);

        self::output()->output($form);
    }


    /**
     *
     */
    protected function removeFileTypeIcon()/*: void*/
    {
        self::srFileObjectTypeIcons()->fileTypeIcons()->deleteFileTypeIcon($this->file_type_icon);

        ilUtil::sendSuccess(self::plugin()->translate("removed_file_type_icon", FileTypeIconsGUI::LANG_MODULE), true);

        self::dic()->ctrl()->redirect($this, self::CMD_BACK);
    }


    /**
     *
     */
    protected function removeFileTypeIconConfirm()/*: void*/
    {
        $confirmation = new ilConfirmationGUI();

        $confirmation->setFormAction(self::dic()->ctrl()->getFormAction($this));

        $confirmation->setHeaderText(self::plugin()->translate("remove_file_type_icon_confirm", FileTypeIconsGUI::LANG_MODULE));

        //$confirmation->addItem(self::GET_PARAM_FILE_TYPE_ICON_ID, $this->file_type_icon->getFileTypeIconId(), $this->file_type_icon->getObject()->getTitle());

        $confirmation->setConfirm(self::plugin()->translate("remove", FileTypeIconsGUI::LANG_MODULE), self::CMD_REMOVE_FILE_TYPE_ICON);
        $confirmation->setCancel(self::plugin()->translate("cancel", FileTypeIconsGUI::LANG_MODULE), self::CMD_BACK);

        self::output()->output($confirmation);
    }


    /**
     *
     */
    protected function setTabs()/*: void*/
    {
        self::dic()->tabs()->clearTargets();

        self::dic()->tabs()->setBackTarget(self::plugin()->translate("file_type_icons", FileTypeIconsGUI::LANG_MODULE), self::dic()->ctrl()
            ->getLinkTarget($this, self::CMD_BACK));

        if ($this->file_type_icon !== null) {
            if (self::dic()->ctrl()->getCmd() === self::CMD_REMOVE_FILE_TYPE_ICON_CONFIRM) {
                self::dic()->tabs()->addTab(self::TAB_EDIT_FILE_TYPE_ICON, self::plugin()->translate("remove_file_type_icon", FileTypeIconsGUI::LANG_MODULE), self::dic()->ctrl()
                    ->getLinkTarget($this, self::CMD_REMOVE_FILE_TYPE_ICON_CONFIRM));
            } else {
                self::dic()->tabs()->addTab(self::TAB_EDIT_FILE_TYPE_ICON, self::plugin()->translate("edit_file_type_icon", FileTypeIconsGUI::LANG_MODULE), self::dic()->ctrl()
                    ->getLinkTarget($this, self::CMD_EDIT_FILE_TYPE_ICON));
                //self::dic()->locator()->addItem($this->file_type_icon->getObject()->getTitle(), self::dic()->ctrl()->getLinkTarget($this, self::CMD_EDIT_FILE_TYPE_ICON));
            }
        } else {
            $this->file_type_icon = self::srFileObjectTypeIcons()->fileTypeIcons()->factory()->newInstance();

            self::dic()->tabs()->addTab(self::TAB_EDIT_FILE_TYPE_ICON, self::plugin()->translate("add_file_type_icon", FileTypeIconsGUI::LANG_MODULE), self::dic()->ctrl()
                ->getLinkTarget($this, self::CMD_ADD_FILE_TYPE_ICON));
        }
    }


    /**
     *
     */
    protected function updateFileTypeIcon()/*: void*/
    {
        self::dic()->tabs()->activateTab(self::TAB_EDIT_FILE_TYPE_ICON);

        $form = self::srFileObjectTypeIcons()->fileTypeIcons()->factory()->newFormInstance($this, $this->file_type_icon);

        if (!$form->storeForm()) {
            self::output()->output($form);

            return;
        }

        ilUtil::sendSuccess(self::plugin()->translate("saved_file_type_icon", FileTypeIconsGUI::LANG_MODULE), true);

        self::dic()->ctrl()->redirect($this, self::CMD_BACK);
    }
}
