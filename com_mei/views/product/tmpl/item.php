<?php
defined('_JEXEC') or die();

?>
<div id="meiProduct">
    <div id="meiProductDescription">
        <img src="<?php echo $this->item->images ?>" id="meiProductThumb" alt="<?php echo $this->item->title ?> thumbnail">
    	<div id="prod-info">
            <h1><?php echo JText::_('COM_MEI_PRODUCT') ?>: <?php echo $this->item->title ?></h1>
            <?php if(isset($this->item->images)): ?>
            <?php endif; ?>
            <span><?php echo $this->item->description ?></span>
        </div>
        <div style="clear:both;"></div>
    </div>
</div>
<!-- Vico wrote this in to break products page in two -->
</div></div></div>
<div class="body rounded">
<div><div><div>
<!-- // Vico wrote this in to break products page in two -->
    <div id="meiProductFiles">

    <?php

    $jinput = JFactory::getApplication()->input;

    $productId = $jinput->get('id', 0, 'alnum');

    $inputvars = array(
        'limit'            => 10,
        'limitstart'    => 0,
        'fk_product_id' => $productId,
        'format' => 'html'
    );

    $input = new BBDFOFInput($inputvars);

    ob_start();
    BBDFOFDispatcher::getTmpInstance('com_mei', 'files', array('input' => $input))->dispatch();
    $content = ob_get_clean();
    echo $content;

    ?>

    </div>


</div>
