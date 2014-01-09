<?php

defined('_JEXEC') or die();

class MeiadminTableFamily extends BBDFOFTable {

	public function __construct($table, $key, &$db){
		parent::__construct('#__meiadmin_categories', 'meiadmin_category_id', $db);
	}

}
