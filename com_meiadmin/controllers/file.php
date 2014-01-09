<?php

defined('_JEXEC') or die();

class MeiadminControllerFile extends BBDFOFController
{

    protected $_tmpFileUploadInfo = array();
    protected $_versionModel = null;

    public function onAfterApply()
    {
        return $this->_storeVersion();
    }

    public function onAfterSave()
    {
        return $this->_storeVersion();
    }

    protected function _storeVersion()
    {
        try {
            $this->_addNewVersion();
            if ($this->_versionModel->version !== 0) {
                $this->_updateFileWithNewVersion();
                $this->_alertCustomersOfNewFile();
            }
        } catch (Exception $e) {
            $this->setRedirect('index.php?option=com_meiadmin&view=file&task=edit&id=' . $this->getModel()->meiadmin_file_id, $e->getMessage(), 'error');
            $this->redirect();
        }
        return true;
    }

    protected function _addNewVersion()
    {
        $fid = $this->getThisModel()->getId();
        $this->_versionModel = $this->getModel('file_versions');
        $this->_versionModel->fk_file_id = $fid;
        $this->_versionModel->initialVersion = $this->input->getString('initialVersion', false);
        $this->_tmpFileUploadInfo = $this->input->files->get('newVersion', array());
        $this->_versionModel->upload($this->_tmpFileUploadInfo);
    }

    protected function _updateFileWithNewVersion()
    {
        $fileModel = $this->getModel('files');
        $fileModel->newVersion($this->_versionModel->version, $this->_tmpFileUploadInfo['size']);
    }

    protected function _alertCustomersOfNewFile()
    {
        $pid = $this->input->getAlnum('fk_product_id', 0);
        $fid = $this->input->getAlnum('id', 0);
        $batchBuilder = new MailBatchBuilder();
        $mailer = new BatchMailerJoomla();
        $fileMailer = new MeiadminFileMailCoordinator($pid, $fid, $batchBuilder, $mailer);
        $fileMailer->sendAlert();
    }

    public function cancel()
    {
        parent::cancel();
        $this->_setRedirectToProduct();
    }

    public function save()
    {
        $result = parent::save();
        $this->_setRedirectToProduct();
        return $result;
    }

    protected function _setRedirectToProduct()
    {
        $pid = $this->input->getAlnum('fk_product_id', 0);
        $Itemid = $this->input->getAlNum('Itemid', 0);
        $this->setRedirect('index.php?option=com_meiadmin&view=product&task=edit&id=' . $pid . '&Itemid=' . $Itemid);
    }

    protected function onBeforePromote()
    {
        return $this->checkACL('file.promote');
    }

    public function promote(){
        $fid = $this->input->getAlnum('fid', false);
        $vid = $this->input->getAlnum('vid', false);
        $model = $this->getThisModel();
        $model->set('id', $fid);
        $redirect = 'index.php?option=com_meiadmin&view=file&task=edit&id=' . $fid;
        if($model->promote($vid)) {
            $this->setRedirect($redirect, JText::_('COM_MEIADMIN_VERSION_PROMOTED'));
        } else {
            $this->setRedirect($redirect, JText::_('COM_MEIADMIN_VERSION_NOT_PROMOTED'), 'error');
        }
    }

}