<?php

defined('_JEXEC') or die;

class MeiControllerCustomer extends BBDFOFController
{
    public function __construct($config = array())
    {
        parent::__construct($config);
        JHtml::_('bootstrap.framework');
        JHtml::script(JUri::base() . 'media/com_mei/js/customer.js');
    }

    public function save()
    {
        if (!parent::save()) return false;
        $model = $this->getThisModel();
        $this->_redirectToEdit($model);
        return true;
    }

    public function onBeforeSave()
    {
        // TODO: Customer needs to be able to save own account profile
        return true;
    }

    public function onBeforeAdd()
    {
        // TODO: Customer needs to be able to view own account profile
        return true;
    }

    public function resetPassword()
    {
        $model = $this->getThisModel();
        $password = new MeiPassword;
        $finput = new BBDFOFInput;
        $password = $model->resetPassword($password, $finput);
        $mailer = JFactory::getMailer();
        $model->setMailer($mailer);
        $model->emailNewPassword($password);
    }

    public function updatePassword()
    {
        $this->changePassword($updating = true);
    }

    public function changePassword($updating = false)
    {
        $model = $this->getThisModel();
        try{
            $password = new MeiPassword;
            $finput = new BBDFOFInput;
            $model->saveNewPassword($password, $finput);
            if (!$updating) $this->_redirectToEdit($model);
        }catch (Exception $e){
            $this->_displayErrorMessage($e, $this->_getLayout(), null);
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

    protected function _redirectToEdit($model)
    {
        $layout = ($this->_getLayout() != '') ? $this->_getLayout() : 'edit';
        $id = $model->getId();
        $this->setRedirect($this->_loadUrl($id, $layout));
    }
}