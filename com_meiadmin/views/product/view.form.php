<?php

defined('_JEXEC') or die();

class MeiAdminViewProduct extends BBDFOFViewForm {

    public function display($tpl = null){
        JHtmlBootstrap::framework();
        $this->_loadFiles();
        return parent::display($tpl);
    }

    protected function _loadFiles(){
        $this->items = $this->get('files');
    }

}