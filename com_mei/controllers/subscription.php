<?php

defined('_JEXEC') or die;

class MeiControllerSubscription extends BBDFOFController
{
    public function update()
    {
        JSession::checkToken() or die;
        $jinput = JFactory::getApplication()->input;
        $subscriptions = $jinput->post->get('subscriptions', array(), 'ARRAY');
        $model = $this->getThisModel(array('subscriptions' => $subscriptions));
        $url = 'index.php?option=com_mei&view=customer&layout=form&Itemid=' . $jinput->get('Itemid');
        try {
            $model->updateFileExclusions();
            $this->setRedirect($url, JText::_('COM_MEI_SAVE_SUCCESS_EXCLUSIONS'));
        } catch (Exception $e){
            $this->setRedirect($url, $e->getMessage());
        }
    }

}