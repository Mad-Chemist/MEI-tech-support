<?php

defined('_JEXEC') or die();

class MeiAdminViewProduct extends BBDFOFViewForm {

    public function display($tpl = null){
        JHtmlBootstrap::framework();
        $this->_loadFiles();
        echo "<h1>Products</h1><hr />";
        return parent::display($tpl);

    }

    protected function _loadFiles(){
        $this->items = $this->get('files');
    }

}