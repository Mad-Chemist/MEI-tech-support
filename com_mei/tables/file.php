<?php

defined('_JEXEC') or die();

class MeiTableFile extends BBDFOFTable {
  
	public function __construct($table, $key, &$db){
		parent::__construct('#__meiadmin_files', 'meiadmin_file_id', $db);
	}

}
