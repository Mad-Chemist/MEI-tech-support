<?php

defined('_JEXEC') or die();

class MeiadminControllerFamily extends BBDFOFController
{
	public function display($cachable = false, $urlparams = false) {
		$view = $this->getThisView(array('tbl' => '#__meiadmin_categories',
										 'table' => 'categories'));
        echo "<h1>Product Families</h1><hr />";
		parent::display();
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
