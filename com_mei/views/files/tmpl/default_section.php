<h2 class="section-head">
    <?php echo JText::_($this->sectionHeading); ?>
</h2>
<?php
echo '<div name="'.strtolower(JText::_($this->sectionHeading)).'" class="section-children" style="display: block;">';
foreach ($this->sectionFiles as $type => $files) :

    $this->type = $type;
    $this->tableFiles = $files;

    echo $this->loadAnyTemplate('com_mei/files/default_table');

endforeach;
echo '</div>';