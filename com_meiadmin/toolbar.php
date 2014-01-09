<?php
/**
 * @package		contactus
 * @copyright	Copyright (c)2013 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license		GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

class MeiadminToolbar extends BBDFOFToolbar {

    public function __construct($config = array())
    {
	parent::__construct($config);
	// Only show front-end buttons to users with core.manage permissions
    $this->renderFrontendButtons = $this->perms->edit;
    //$this->renderFrontendSubmenu = $this->perms->edit;
    }

    public function onAdd(){
        $jinput = JFactory::getApplication()->input;
        $view = $jinput->getString('view', false);
        $task = $jinput->getString('task', false);
        if($view === 'file' && $task === 'edit') return $this->_loadNoSaveAndNew();
        if($view === 'product' && $task === 'edit') return $this->_loadProductEditButtons();
        $result = parent::onAdd();
        $this->_pageTitle('COM_MEIADMIN_ADD');
        return $result;
    }

    protected function _loadProductEditButtons(){
        if (!$this->renderFrontendButtons) return;
        $this->_loadBaseButtons();
        JToolBarHelper::custom('savenew', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
        JToolbarHelper::custom('addFile','upload','upload','COM_MEIADMIN_PRODUCT_JTOOLBAR_NEWFILE', false);
        JToolBarHelper::cancel();
        $this->_pageTitle('COM_MEIADMIN_ADD');
    }

    protected function _loadNoSaveAndNew(){
        if(!$this->renderFrontendButtons) return;
        $this->_loadBaseButtons();
        JToolBarHelper::cancel();
        $this->_pageTitle('COM_MEIADMIN_ADD');
    }

    protected function _loadBaseButtons(){
        $option = $this->input->getCmd('option', 'com_foobar');
        $componentName = str_replace('com_', '', $option);

        // Set toolbar title
        $subtitle_key = strtoupper($option . '_TITLE_' . BBDFOFInflector::pluralize($this->input->getCmd('view', 'cpanel'))) . '_EDIT';
        JToolBarHelper::title(JText::_(strtoupper($option)) . ' &ndash; <small>' . JText::_($subtitle_key) . '</small>', $componentName);

        // Set toolbar icons
        JToolBarHelper::apply();
        JToolBarHelper::save();
    }

    public function onRead(){
        $result = parent::onRead();
        $this->_pageTitle('COM_MEIADMIN_READ');
        return $result;
    }

    protected function _pageTitle($title){
        $view = BBDFOFInflector::pluralize($this->input->getCmd('view', 'cpanel'));
        JToolBarHelper::title(JText::_(strtoupper($title . '_' . $view)));
    }

}
