<h4 class="typeTitle"><?php echo JText::_('COM_MEI_TYPE_TITLE_' . strtoupper($this->type)) ?></h4>
<div class="file_table span11">
  <table class="table table-bordered table-striped table-hover">
    <tr>
      <th><?php echo JText::_('COM_MEI_TABLE_HEADING_NAME'); ?></th>
      <th><?php echo JText::_('COM_MEI_TABLE_HEADING_VERSION'); ?></th>
      <th><?php echo JText::_('COM_MEI_TABLE_HEADING_LAST_UPDATED'); ?></th>
    </tr>
    <?php foreach($this->tableFiles as $file) : ?>
    <tr>
      <td><a href="<?php echo $file->url; ?>"><?php echo($file->title); ?></a></td>
      <td><?php echo( $file->current_version ); ?></td>
      <td>
      <?php 
        $modified = new JDate($file->modified_on);
        echo $modified->format('M d, Y h:i A'); 
      ?>
      </td>
    </tr>
    <?php endforeach; ?>  
  </table>
</div>