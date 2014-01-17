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
        $finput = new BBDFOFInput;
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('`fk_product_id`')
            ->from('#__meiadmin_customer_subscriptions as s')
            ->leftJoin('#__meiadmin_customers as c ON (c.fk_user_id = s.fk_user_id)')
            ->where('`meiadmin_customer_id` = ' . $db->quote($finput->get('id')));
        $db->setQuery($query);
        $result = $db->loadObjectList('fk_product_id');
        return array_keys($result);
    }

}