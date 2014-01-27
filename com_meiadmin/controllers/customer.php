<?php

defined('_JEXEC') or die;

class MeiadminControllerCustomer extends BBDFOFController
{
    public function execute($task)
    {
        if (!$this->onBeforeExecute()){
            $this->setRedirect(JUri::base(), JText::_('JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN'), 'error');
            $this->redirect();
        }
        return parent::execute($task);
    }

    protected function onBeforeExecute()
    {
        return $this->checkACL('customer.admin');
    }

    public function apply()
    {
        $this->_trySave('apply');
    }

    public function save()
    {
        $this->_trySave('save');
    }

    public function savenew()
    {
        $this->_trySave('savenew');
    }

    protected function _trySave($method)
    {
        try{
            parent::$method();
        }catch (Exception $e){
            $this->_displayErrorMessage($e->getMessage, null, 'edit');
        }
    }

    protected function _displayErrorMessage($message, $layout = null, $task = null)
    {
        $model = $this->getThisModel();
        if (!$model->getId()) $model->setIDsFromRequest();
        $id = $model->getId();
        $url = $this->_loadUrl($id, $layout, $task);
        $this->setRedirect($url, $message, 'error');
    }

    protected function _loadUrl($id, $layout, $task)
    {
        $Itemid = JFactory::getApplication()->input->get('Itemid');
        $url = 'index.php?option=' . $this->component . '&view=' . $this->view . '&id=' . $id . '&Itemid=' . $Itemid;
        if (!is_null($layout)) $url .= '&layout=' . $layout;
        if (!is_null($task)) $url .= '&task=' . $task;
        return $url;
    }
}