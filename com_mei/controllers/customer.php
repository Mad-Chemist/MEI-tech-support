<?php

defined('_JEXEC') or die;

class MeiControllerCustomer extends BBDFOFController
{
    public function changePassword()
    {
        $model = $this->getThisModel();
        try{
            $model->saveNewPassword();
        }catch (Exception $e){
            $this->_displayErrorMessage($e, 'changepassword', null);
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

    protected function _loadUrl($id, $layout, $task)
    {
        $Itemid = JFactory::getApplication()->input->get('Itemid');
        $url = 'index.php?option=' . $this->component . '&view=' . $this->view . '&id=' . $id . '&Itemid=' . $Itemid;
        $url .= (!is_null($layout)) ? '&layout=' . $layout : '';
        $url .= (!is_null($task)) ? '&task=' . $task : '';
        return $url;
    }
}