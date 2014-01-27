<?php

defined('_JEXEC') or die();

?>

<h2 class="section-head"><?php echo JText::_($this->sectionHeading) ?></h2>

    <div name="<?php echo strtolower(JText::_($this->sectionHeading)) ?>" class="section-children" style="display: block;">

<?php

foreach($this->sectionFiles as $type => $products) {
    $this->type = $type;
    $this->products = $products;
    echo $this->loadAnyTemplate('com_mei/customer/subscription_table');
}

?>

</div>