<?php
/**
 * @package		contactus
 * @copyright	Copyright (c)2013 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license		GNU General Public License version 2 or later
 */

class MeiadminModelFamilies extends BBDFOFModel
{
	/**
	 * This method is only called after a record is saved. We will hook on it
	 * to send an email to the address specified in the category.
	 *
	 * @param   BBDFOFTable  $table  The BBDFOFTable which was just saved
	 */
	protected function onAfterSave(&$table)
	{
		$result = parent::onAfterSave($table);

		if ($result !== false)
		{
		    //echo 'Saved';
		}

		return $result;
	}
}