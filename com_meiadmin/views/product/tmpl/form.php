<?php

defined('_JEXEC') or die();

$viewTemplate = $this->getRenderedForm();

echo $viewTemplate;



?>

<?php $this->_loadFiles(); ?>

<?php if (!empty($this->items)): ?>

    <div class="span11" id="productFiles">

        <h3><?php echo JText::_('COM_MEIADMIN_PRODUCTS_FILES') ?></h3>

        <?php echo JHtml::_('bootstrap.startTabSet', 'productFiles', array('active' => 'documents')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'productFiles', 'documents', JText::_('COM_MEIADMIN_DOCUMENTS')); ?>

        <?php
        $this->sectionHeading = 'COM_MEIADMIN_DOCUMENTS';
        $this->sectionFiles = (array_key_exists('documentation', $this->items)) ? $this->items['documentation'] : false;
        if($this->sectionFiles ) echo $this->loadAnyTemplate('com_meiadmin/files/default_section');
        ?>


        <?php echo JHtml::_('bootstrap.endTab'); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'productFiles', 'tools', JText::_('COM_MEIADMIN_TOOLS')); ?>

        <?php
        $this->sectionHeading = 'COM_MEIADMIN_TOOLS';
        $this->sectionFiles = (array_key_exists('tools', $this->items)) ? $this->items['tools'] : false;
        if($this->sectionFiles ) echo $this->loadAnyTemplate('com_meiadmin/files/default_section');
        ?>

        <?php echo JHtml::_('bootstrap.endTab'); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'productFiles', 'firmware', JText::_('COM_MEIADMIN_FIRMWARE')); ?>

        <?php
        $this->sectionHeading = 'COM_MEIADMIN_FIRMWARE';
        $this->sectionFiles = (array_key_exists('firmware', $this->items)) ? $this->items['firmware'] : false;
        if($this->sectionFiles) echo $this->loadAnyTemplate('com_meiadmin/files/default_section');
        ?>

        <?php echo JHtml::_('bootstrap.endTab'); ?>

        <?php echo JHtml::_('bootstrap.endTabSet'); ?>

    </div>

<?php endif; ?>