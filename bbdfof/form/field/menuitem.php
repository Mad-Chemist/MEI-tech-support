<?php

defined('_JEXEC') or die();

if (!class_exists('BBDFOFFormFieldHidden'))
{
    require_once 'hidden.php';
}

class BBDFOFFormFieldMenuItem extends BBDFOFFormFieldHidden
{

    protected function getInput(){
        $jinput = JFactory::getApplication()->input;
        $Itemid = $jinput->get('Itemid', null);
        if(!$Itemid) return '';
        $this->value = $Itemid;
        $this->name = 'Itemid';
        $this->id = 'Itemid';
        return parent::getInput();
    }

}
