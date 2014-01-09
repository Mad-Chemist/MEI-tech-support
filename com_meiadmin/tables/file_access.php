<?php

defined('_JEXEC') or die();

class MeiadminTableFile_access extends BBDFOFTable {

	public function __construct($table, $key, &$db){
		parent::__construct('#__meiadmin_file_accesses', 'meiadmin_file_access_id', $db);
	}

}
