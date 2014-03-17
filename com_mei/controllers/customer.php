<?php

defined('_JEXEC') or die;

class MeiControllerCustomer extends BBDFOFController
{
    public function __construct($config = array())
    {
        parent::__construct($config);
        JHtml::_('bootstrap.framework');
        /*This file doesn't exist.  I have no idea why it has been linked.  I've commented it out to avoid the 404 error in console*/
        /*JHtml::script(JUri::base() . 'media/com_mei/js/customer.js');*/
    }

    public function onBeforeSave()
    {
        return true;
    }

    public function onBeforeAdd()
    {
        return true;
    }

    public function onBeforeEdit()
    {
        return true;
    }

    public function saveAttributes()
    {
        $this->_save('Attributes');
    }

    public function saveExclusions()
    {
        $this->_save('Exclusions');
    }

    public function saveImage()
    {
        $this->_save('Image');
    }

    public function saveNda()
    {
        $this->_save('Nda');
    }

    protected function _save($form)
    {
        $model = $this->getThisModel();
        $method = 'save'.$form;
        $model->$method();
        $this->_redirectToEdit($model, JText::_('COM_MEI_SAVE_SUCCESS_'.strtoupper($form)));
        return true;
    }

    public function resetPassword()
    {
        $model = $this->getThisModel();
        $passwordModel = $this->getModel('password');
        $newPassword = $model->resetPassword($passwordModel);
        $mailer = JFactory::getMailer();
        $model->setMailer($mailer);
        $model->emailNewPassword($newPassword);
    }

    public function requiredPasswordChange()
    {
        $this->changePassword($setExpiration = true);
    }

    public function changePassword($setExpiration = false)
    {

        $model = $this->getThisModel();
        $passwordModel = $this->getModel('password');

        try{
            $model->saveNewPassword($passwordModel, $setExpiration);
            if (!$setExpiration) {
                $this->_redirectToEdit($model, JText::_('COM_MEI_SAVE_SUCCESS_PASSWORD'));
            } else {
                $this->setRedirect(JUri::base() . 'index.php');
            }
        }catch (Exception $e){
            if (!$setExpiration) {
                $this->_redirectToEdit($model, $e->getMessage(), 'error');
            } else {
                $this->setRedirect(JUri::base().'index.php?', $e->getMessage(), 'error');
            }
        }
    }

    protected function _displayErrorMessage($e, $layout = null, $task = null)
    {
        $model = $this->getThisModel();
        if (!$model->getId()) $model->setIDsFromRequest();
        $id = $model->getId();
        $url = $this->_loadUrl($id, $layout, $task);
        $this->setRedirect($url, $e->getMessage(), 'error');
    }

    protected function _loadUrl($id = null, $layout = null, $task = null)
    {
        $Itemid = JFactory::getApplication()->input->get('Itemid');
        $url = 'index.php?option=' . $this->component . '&view=' . $this->view . '&Itemid=' . $Itemid;
        $url .= ($id != '0') ? '&id=' . $id : '';
        $url .= (!is_null($layout)) ? '&layout=' . $layout : '';
        $url .= (!is_null($task)) ? '&task=' . $task : '';
        return $url;
    }

    protected function _getLayout()
    {
        if ($layout = JFactory::getApplication()->input->get('layout')) return $layout;
        return 'edit';
    }

    protected function _redirectToEdit($model, $message = '', $error = null)
    {
        $layout = ($this->_getLayout() != 'edit') ? $this->_getLayout() : 'form';
        $id = $model->getId();
        $this->setRedirect($this->_loadUrl($id, $layout), $message, $error);
    }
}