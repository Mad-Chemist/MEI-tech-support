<?php

defined('_JEXEC') or die;

class JoomlaUserGroups {

    static public function getUsersGroupTitles(){
        $juser = JFactory::getUser();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('title')->from('#__usergroups')->where("id IN('" . implode("','", $juser->groups) . "')");
        $db->setQuery($query);
        return $db->loadAssoc('title');
    }

}