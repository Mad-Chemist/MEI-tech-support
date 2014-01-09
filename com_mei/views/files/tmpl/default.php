<?php

defined('_JEXEC') or die();

$this->sectionHeading = 'COM_MEI_DOCUMENTS';
$this->sectionFiles = (array_key_exists('documentation', $this->items)) ? $this->items['documentation'] : '';
if( '' != $this->sectionFiles )
	echo $this->loadTemplate('section');

$this->sectionHeading = 'COM_MEI_TOOLS';
$this->sectionFiles = (array_key_exists('tool', $this->items)) ? $this->items['tool'] : '';
if( '' != $this->sectionFiles )
	echo $this->loadTemplate('section');

$this->sectionHeading = 'COM_MEI_FIRMWARE';
$this->sectionFiles = (array_key_exists('firmware', $this->items)) ? $this->items['firmware'] : '';
if('' != $this->sectionFiles)
	echo $this->loadTemplate('section');