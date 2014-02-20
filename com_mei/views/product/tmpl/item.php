<?php
defined('_JEXEC') or die();
/*schubert*/
$userV3 =& JFactory::getUser();
$userId = $userV3->get( 'id' );
if ($userId > 100) { 
    $userAccess      =   mysql_query('SELECT  `fk_region_id`, `fk_channel_id` FROM  `44aae_meiadmin_customers` WHERE  `fk_user_id` = '.$userId.' LIMIT 0,1');
    if ($userAccess) {
        while ($row  =   mysql_fetch_array($userAccess) ){ 
            $GLOBALS['regV'] = $row[0];
            $GLOBALS['chanV'] = $row[1];
        }
    }
    else {
        $GLOBALS['regV'] = '1';
        $GLOBALS['chanV'] = '3';
    }
}
else {
    $GLOBALS['regV'] = '1';
    $GLOBALS['chanV'] = '3';
}
$GLOBALS['PERMV'] = true;
/*schubert*/
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
        'limit'         => 10000,
        'limitstart'    => 0,
        'fk_product_id' => $productId,
        /*by adding this enabled check, it will prevent showing files that have been disabled.*/
        'enabled'       => 1,
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
