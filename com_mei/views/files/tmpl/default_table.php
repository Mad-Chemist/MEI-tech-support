<?php  
  include_once "file-icon.php";  
  $sHeadEcho = '';
  $sHeadEcho.= '<h4 class="typeTitle">'. JText::_('COM_MEI_TYPE_TITLE_' . strtoupper($this->type)).'</h4>';
  $sHeadEcho.= '<div class="file_table span11">';
  $sHeadEcho.= '<table class="table table-bordered table-striped table-hover">';
  $sHeadEcho.= '<tr class="file-table-head">';
  $sHeadEcho.= '<th>'. JText::_('COM_MEI_TABLE_HEADING_NAME').'</th>';
  $sHeadEcho.= '<th>'. JText::_('COM_MEI_TABLE_HEADING_VERSION').'</th>';
  $sHeadEcho.= '<th>'. JText::_('COM_MEI_TABLE_HEADING_LAST_UPDATED').'</th></tr>';
  $filesEcho = '';    
  foreach($this->tableFiles as $file) : 
    if (!$GLOBALS['PERMV'] || (strpos($file->channel,$GLOBALS['chanV']) !== false && strpos($file->region,$GLOBALS['regV']) !== false) ) { 
      $filesEcho.=  '<tr  class="'.str_replace(" ", "-", $file->title).'">';
      $filesEcho.=  '<td>'.retrieveEXT($file->meiadmin_file_id, $file->current_version).'<a href="'.$file->url.'">'.$file->title.'</a></td>';
      $filesEcho.=  '<td>'.$file->current_version.'</td>';
      $modified = new JDate($file-> modified_on);
      $filesEcho.=  '<td>'.$modified->format('M d, Y h:i A').'</td></tr>';
    } 
  endforeach; 
  if (strlen($filesEcho) > 0)   echo $sHeadEcho.$filesEcho.'</table></div>';
?>