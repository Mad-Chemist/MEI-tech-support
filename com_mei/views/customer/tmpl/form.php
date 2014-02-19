<?php

defined('_JEXEC') or die;
?>


    <h1><?php echo JText::_('COM_MEI_CUSTOMER_ACCOUNT') ?></h1>
    <span class="anchor-links">
        <a onclick="jQuery(window).scrollTop(jQuery('a[name=update]').offset().top);return false;"><?php echo JText::_('COM_MEI_CUSTOMER_INFORMATION'); ?></a> 
        <a onclick="jQuery(window).scrollTop(jQuery('a[name=nda]').offset().top);return false;"><?php echo JText::_('COM_MEI_CUSTOMER_NDA'); ?></a> 
        <a onclick="jQuery(window).scrollTop(jQuery('a[name=passw]').offset().top);return false;"><?php echo JText::_('COM_MEI_CUSTOMER_PASSWORD_RESET'); ?></a> 
        <a onclick="jQuery(window).scrollTop(jQuery('a[name=image]').offset().top);return false;"><?php echo JText::_('COM_MEI_CUSTOMER_IMAGE'); ?></a> 
        <a onclick="jQuery(window).scrollTop(jQuery('a[name=subscribe]').offset().top);return false;"><?php echo JText::_('COM_MEI_CUSTOMER_ACCOUNT_SUBSCRIBE'); ?></a>
    </span>
<div style="width:100%;height:30px;display:block;"></div>
</div></div></div>
<div class="body rounded">

<div id="myAccount" class="clear">
    <div class="my-account-left">
        <?php if(!$this->_isAdmin()): ?>
            <?php echo  "<a name='update'></a>".$this->_forms['attributes']; ?>
            <?php echo "<a name='nda'></a>".$this->_forms['nda']; ?>
        <?php endif; ?>
    </div>

    <div class="my-account-right">
        <?php echo "<a name='passw'></a>".$this->_forms['password']; ?>
        <?php if(!$this->_isAdmin()): ?>
            <?php echo "<a name='image'></a>".$this->_forms['image']; ?>
            
        <?php endif; ?>
    </div>
<div style="clear:both;"></div>
</div></div>

<div class="body rounded">
<div><div>
    <?php $this->_loadSubscriptions(); ?>

    <div class="my-account-bottom">
    <a name='subscribe'></a>
    <h3 style="margin-bottom:20px;"><?php echo JText::_('COM_MEI_CUSTOMER_ACCOUNT_SUBSCRIBE') ?></h3>
        <?php echo $this->loadAnyTemplate('com_mei/customer/subscription'); ?>

    </div>


<style>
    #myAccount form, .my-account-right form, .form-horizontal .control-label, .controls {
    margin:0px !important; 
    padding:0px !important;
    }
     #myAccount form {
        margin-bottom:25px !important;
     }
    h1, h3 {
    border-bottom: #8F8F8F solid 1px;
    /* width: 90%; */
    padding-bottom: 5px;
    }
    .my-account-left {
    width: 50%;
    float: left;
    min-width: 375px;
    }
    .my-account-right {  width: 50%;
    float: left;
    }

    .my-account-right form .control-group, .my-account-left form .control-group {
    margin:0px !important;
    line-height:3.25;
    }
    .my-account-bottom {
    clear: both;
    }

    .form-horizontal input {
    vertical-align: top;
    }

    /* a links */
.anchor-links {
    display: block;
    width: 100%;
    text-align: center;
}
.anchor-links a {
    padding: 5px 10px;
    font-size: 15px;
    display: inline-block;
}
    /* attributes */
    #customerAttributes .control-label {
    width: 115px !important;
    margin:5px 10px -5px 0px !important;
    }
    #customerAttributes .bttn {
    margin-left: 125px;
    }

    /* nda */
    #signedNDA .controls {
    line-height: 2;
    }
    #signedNDA p {
    display:block;
    font-weight: bold;
    text-align: left;
    font-size: 19px;
    margin: 0px;
    margin-left: 125px !important;
    }
    #signedNDA .control-group {
    margin-left: 125px !important;
    margin-top: 5px !important;
    width: auto !important;
    }
    
    /* password */
    #customerPassword .control-label {
    width: 145px !important;
    margin-right: 10px !important;
    }
    #customerPassword .bttn {
    margin-left: 155px !important;
    }
    
    /* image */
#customerImage img {
    width: 150px;
    height: auto;
    max-height: 200px;
    float: none;
    display: block;
    margin: 0px auto;
    margin-bottom: 15px;
    border: 1px #808080 solid;
    box-shadow: 0px 0px 4px 2px #BEBEBE;
    -webkit-transition: all 1s ease;
    -moz-transition: all 1s ease;
    -ms-transition: all 1s ease;
    -o-transition: all 1s ease;
    transition: all 1s ease;
}
#customerImage .control-group {
    line-height: 2;
    width: 170px;
    margin: 0px auto !important;
    display: block;
    text-align: center;
}

/* subscriptions */
.my-account-bottom .bttn {
    margin-top: 25px;
    display: block;
}
@media (max-width:850px) {
    .my-account-left, .my-account-right {  
        width: 100%;
        float: none;
    }
    .anchor-links a {
padding: 15px 20px !important;
background: #FAFAFA;
margin: 10px 5px;
border: #E7E7E7 solid 1px;
}
}
@media (max-width:480px) {
    #customerAttributes .control-label {
        margin-bottom: 4px !important;
    }
}
</style>
<script>
    jQuery('.btn').removeClass('btn')
</script>