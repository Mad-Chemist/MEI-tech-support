<?php

defined('_JEXEC') or die();

class MeiadminControllerFamily extends BBDFOFController {

	public function display($cachable = false, $urlparams = false) {
		$view = $this->getThisView(array('tbl' => '#__meiadmin_categories',
										 'table' => 'categories'));
		echo "<h1>Product Families</h1><hr />";
		parent::display();
	}

}
