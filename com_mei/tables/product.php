<?php

defined('_JEXEC') or die();

class MeiTableProduct extends BBDFOFTable {

	public function __construct($table, $key, &$db){
		parent::__construct('#__meiadmin_products', 'meiadmin_product_id', $db);
	}

}
