<?php
defined('_JEXEC') or die();
/*schubert*/
$userV3 =& JFactory::getUser();
$userId = $userV3->get( 'id' );
$GLOBALS['user'] = $userId;
if ($userId > 100) { 
    $userAccess =   mysql_query('SELECT  `fk_region_id`, `fk_channel_id`, `access_nda`, `access_level2`, `access_oem`, `access_asc`, `access_dist` FROM  `44aae_meiadmin_customers` WHERE  `fk_user_id` = '.$userId.' LIMIT 0,1');
    if ($userAccess) {
        while ($row  =   mysql_fetch_array($userAccess) ){ 
            $GLOBALS['regV'] = $row[0];
            $GLOBALS['chanV'] = $row[1];

            /*get access levels of users and set to global var to be used when displaying files*/
            $GLOBALS['accessV']= array(
                $row[2],
                $row[3],
                $row[4],
                $row[5],
                $row[6]
            );
        }
    }
    else {
        $GLOBALS['regV'] = '1';
        $GLOBALS['chanV'] = '3';
        $GLOBALS['accessV']= array(0,0,0,0,0);
        $GLOBALS['user'] =0;
        $GLOBALS['unlogged'] =1;
    }
}
else {
    $GLOBALS['regV'] = '1';
    $GLOBALS['chanV'] = '3';
    $GLOBALS['accessV']= array(0,0,0,0,0);
    $GLOBALS['user'] =0;
    $GLOBALS['unlogged'] =1;
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
<!-- // Vico wrote this in to break products page in two -->
    
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

    if (strlen($content) > 30)  echo '<div class="body rounded"><div><div><div><div id="meiProductFiles">'.$content.'</div> </div>';
    /*regardless of whether or no the file structure is actually left blank, the code still outputs <div class=mei-32..... which is about 30 characters*/
    ?>

