<?php 
$sHeadEcho = '';
$sHeadEcho.= '<h2 class="section-head">'.JText::_($this->sectionHeading).'</h2>';
$sHeadEcho.= '<div name="'.strtolower(JText::_($this->sectionHeading)).'" class="section-children" style="display: block;">';
$checkLengthEcho='';
foreach ($this->sectionFiles as $type => $files) :
    $this->type = $type;
    $this->tableFiles = $files;
    $checkLengthEcho .= $this->loadAnyTemplate('com_mei/files/default_table');
endforeach;
if (strlen($checkLengthEcho) > 0) echo $sHeadEcho.$checkLengthEcho.'</div>';