<a name="<?php echo strtolower(JText::_($this->sectionHeading)); ?>" class="sectionHeading">
    <h2>
        <?php echo JText::_($this->sectionHeading); ?>
    </h2>
</a>

<?php

foreach ($this->sectionFiles as $type => $files) :

    $this->type = $type;
    $this->tableFiles = $files;

    echo $this->loadAnyTemplate('com_mei/files/default_table');


endforeach;
