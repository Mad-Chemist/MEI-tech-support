<?php

defined('_JEXEC') or die();

class MeiadminTableFile extends BBDFOFTable {

	public function __construct($table, $key, &$db){
		parent::__construct('#__meiadmin_files', 'meiadmin_file_id', $db);
	}


}
