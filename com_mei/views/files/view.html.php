<?php

defined('_JEXEC') or die();

class MeiViewFiles extends BBDFOFViewHtml {

	public function display($tpl = null){
        JHtmlBootstrap::framework();
        
        $doc = JFactory::getDocument();
        $doc->addStyleSheet(JURI::base() . '/media/com_mei/css/product.css');

        return parent::display($tpl);
    }

}