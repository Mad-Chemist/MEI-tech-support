<?php

defined('_JEXEC') or die();

class MeiadminHelperProducts {

    public static function getAll(){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('`meiadmin_product_id`, `title`')->from('#__meiadmin_products')->where("`enabled` = '1'");
        $db->setQuery($query);
        return $db->loadObjectList('meiadmin_product_id');
    }

    public static function selected(){
        $jinput = JFactory::getApplication()->input;
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('`products`')->from('#__meiadmin_customers')->where('`meiadmin_customer_id` = ' . $db->quote($jinput->get('id')));
        $db->setQuery($query);
        $result = $db->loadResult();
        return explode(',', $result);
    }

}