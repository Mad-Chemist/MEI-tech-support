<?php
/**
 * @package		contactus
 * @copyright	Copyright (c)2013 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license		GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

class MeiControllerFamily extends BBDFOFController {

	public function display($cachable = false, $urlparams = false) {
		$view = $this->getThisView(array('tbl' => '#__meiadmin_categories',
										 'table' => 'categories'));
		parent::display($cachable, $urlparams);
	}

}
