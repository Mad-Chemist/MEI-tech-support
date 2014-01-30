<?php

defined('_JEXEC') or die;
?>
<?php if($this->_ndaCurrent()) : ?>
    <p><?php echo JText::_('COM_MEI_CUSTOMER_NDA_EXPIRES') . ' ' . $this->_ndaExpirationFormatted(); ?></p>
<?php elseif ($this->_ndaExpired()) : ?>
    <p><?php echo JText::_('COM_MEI_CUSTOMER_NDA_EXPIRED') ?></p>
<?php else : ?>
    <p><?php echo JText::_('COM_MEI_CUSTOMER_NDA_NONE') ?></p>
<?php endif; ?>

<p style="font-weight: normal; font-size: 12px;margin-top:10px;"><?php echo JText::_('COM_MEI_CUSTOMER_PDF_ONLY'); ?></p>