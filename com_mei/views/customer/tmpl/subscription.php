<?php

defined('_JEXEC') or die();

?>

<?php
    if($this->_noSubscriptions()) return false;
?>
<form action="index.php" method="post" name="subscriptionsForm" id="subscriptionsForm" class="form-horizontal form-validate">
    <input type="hidden" name="option" value="com_mei">
    <input type="hidden" name="task" value="update">
    <input type="hidden" name="view" value="subscription">
    <input type="hidden" name="Itemid" value="<?php echo JFactory::getApplication()->input->get('Itemid', 0) ?>">
    <?php echo JHtml::_('form.token'); ?>
<?php

$this->sectionHeading = 'COM_MEI_DOCUMENTS';
$this->sectionFiles = (array_key_exists('documentation', $this->items)) ? $this->items['documentation'] : '';
if( '' != $this->sectionFiles )
    echo $this->loadAnyTemplate('com_mei/customer/subscription_section');

$this->sectionHeading = 'COM_MEI_TOOLS';
$this->sectionFiles = (array_key_exists('tool', $this->items)) ? $this->items['tool'] : '';
if( '' != $this->sectionFiles )
    echo $this->loadAnyTemplate('com_mei/customer/subscription_section');

$this->sectionHeading = 'COM_MEI_FIRMWARE';
$this->sectionFiles = (array_key_exists('firmware', $this->items)) ? $this->items['firmware'] : '';
if('' != $this->sectionFiles)
    echo $this->loadAnyTemplate('com_mei/customer/subscription_section');

?>

    <div class="controls">
        <input type="submit"
               id="submitSubscriptions"
               class="btn mei-title-button"
               value="<?php echo JText::_('COM_MEI_CUSTOMER_BUTTON_SAVE_EXCLUSIONS') ?>"
            />
    </div>

</form>