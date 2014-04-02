<?php  

  include_once "file-icon.php";
  include_once "file-access-filter.php";    
  $tHeadEcho = '';
  $tHeadEcho.= '<h4 class="typeTitle">'. JText::_('COM_MEI_TYPE_TITLE_' . strtoupper($this->type)).'</h4>';
  $tHeadEcho.= '<div class="file_table span11">';
  $tHeadEcho.= '<table class="table table-bordered table-striped table-hover">';
  $tHeadEcho.= '<tr class="file-table-head">';
  $tHeadEcho.= '<th>'. JText::_('COM_MEI_TABLE_HEADING_NAME').'</th>';
  $tHeadEcho.= '<th>'. JText::_('COM_MEI_TABLE_HEADING_VERSION').'</th>';
  $tHeadEcho.= '<th>'. JText::_('COM_MEI_TABLE_HEADING_LAST_UPDATED').'</th></tr>';
  $filesEcho = '';   

  foreach($this->tableFiles as $file) : 
    if (checkAccessLevelsForFile($file)) { 
      $fileVersion  = ($file->custom_version != NULL) ? $file->custom_version : $file->current_version;
      $modified     = new JDate($file-> modified_on);

      $filesEcho.=  '<tr  class="'.strtolower(str_replace(" ", "-", $file->title)).'">';
      $filesEcho.=  '<td>'.retrieveEXT($file->meiadmin_file_id, $file->current_version).'<a class="clicky_log_download" href="'.$file->url.'#version='.$fileVersion.'">'.$file->title.'</a></td>';
      $filesEcho.=  '<td>'.$fileVersion.'</td>';
      $filesEcho.=  '<td>'.$modified->format('M d, Y h:i A').'</td></tr>';
    } 
  endforeach; 
  if (strlen($filesEcho) > 0)   echo $tHeadEcho.$filesEcho.'</table></div>';




?>