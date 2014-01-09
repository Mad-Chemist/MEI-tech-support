<?php
/**
 * @package		contactus
 * @copyright	Copyright (c)2013 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license		GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

class MeiControllerProduct extends BBDFOFController {

	public function display($cachable = false, $urlparams = false) {
		$view = $this->getThisView(array('tbl' => '#__meiadmin_products'));	
		parent::display($cachable, $urlparams);
	}

	public function onBeforeBrowse(){
        if (!parent::onBeforeBrowse()) return false;
		$catid = $this->input->get('fid', 0, 'alnum');
		$products = $this->getModel();
		$products->set('cat_id', $catid);
		return true;
	}

}
