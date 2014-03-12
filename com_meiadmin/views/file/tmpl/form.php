<?php

defined('_JEXEC') or die();

JHtml::_('bootstrap.framework');
JHtml::_('behavior.framework', 'more');

?>

<h2 class="modalTitle">
    <?php echo JText::_('COM_MEIADMIN_FILE_MANAGEMENT'); ?>
</h2>

<?php
$viewTemplate = $this->getRenderedForm();
echo $viewTemplate;
?>

<?php

$versions = BBDFOFModel::getTmpInstance('File_versions', 'MeiAdminModel')->getItemsListById($this->item->meiadmin_file_id);

if(!empty($versions)):

?>

<div class="span11" id="fileVersions">
<h4><?php echo JText::_('COM_MEIADMIN_GENERAL_VERSIONS'); ?></h4>
<table class="table table-bordered table-striped table-hover">
    <tbody>
    <th>
        <?php echo JText::_('COM_MEIADMIN_FILE_FIELD_NUMBER'); ?>
    </th>
    <th>
        <?php echo JText::_('COM_MEIADMIN_FILE_FIELD_TIME'); ?>
    </th>
    <th>
        <?php echo JText::_('COM_MEIADMIN_FILE_CREATED_BY'); ?>
    </th>
    <th class="center">
        <?php echo JText::_('COM_MEIADMIN_FILE_FIELD_PROMOTE'); ?>
    </th>

    <?php foreach ($versions as $version): ?>

        <tr>
            <td>
                <?php echo ($version->custom_version != NULL) ? $version->custom_version : $version->version; ?>
            </td>
            <td>
                <?php
                $date = DateTime::createFromFormat('Y-m-d H:i:s', $version->created_on);
                echo $date->format('M d, Y h:i A');
                ?>
            </td>
            <td>
                <?php echo $version->name; ?>
            </td>
            <td class="center">
                <?php if ($this->item->current_version == $version->version): ?>
                    <?php echo JText::_('COM_MEIADMIN_FILE_CURRENT'); ?>
                <?php else: ?>
                    <a href="index.php?option=com_meiadmin&task=promote&view=file&fid=<?php echo $this->item->meiadmin_file_id ?>&vid=<?php echo $version->meiadmin_file_version_id ?>"><?php echo JText::_('COM_MEIADMIN_FILE_FIELD_PROMOTE_LABEL'); ?></a>
                <?php endif; ?>
            </td>
        </tr>

    <?php endforeach; ?>

    </tbody>
</table>
</div>

<?php endif;