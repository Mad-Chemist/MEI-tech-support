<?php

defined('_JEXEC') or die();

class MeiadminControllerProduct extends BBDFOFController
{
    public function addFile(){
        $pid = $this->input->getInt('meiadmin_product_id', 0);
        $Itemid = $this->input->getInt('Itemid', 0);
        $this->setRedirect('index.php?option=com_meiadmin&view=file&task=edit&pid=' . $pid . '&Itemid=' . $Itemid);
    }

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
        return $this->checkACL('product.admin');
    }
}