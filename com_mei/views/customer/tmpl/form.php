<?php

defined('_JEXEC') or die;
?>
<div id="myAccount" class="clear">

    <h3><?php echo JText::_('COM_MEI_CUSTOMER_ACCOUNT') ?></h3>

    <div class="span4 pull-left">

        <?php echo $this->_forms['password']; ?>

        <?php if(!$this->_isAdmin()): ?>

            <?php echo $this->_forms['attributes']; ?>

        <?php endif; ?>

    </div>

    <div class="span6 pull-right">

        <?php if(!$this->_isAdmin()): ?>

            <?php echo $this->_forms['image']; ?>

            <?php echo $this->_forms['nda']; ?>

        <?php endif; ?>

    </div>

    <?php $this->_loadSubscriptions(); ?>

    <div class="span12">

        <?php echo $this->loadAnyTemplate('com_mei/customer/subscription'); ?>

    </div>

</div>