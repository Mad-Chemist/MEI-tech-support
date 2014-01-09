<?php

defined('_JEXEC') or die();

class MeiadminUserGroups
{
    protected $_user;
    protected $_input;
    protected $_customerTable;
    protected $_db;
    protected $_possibleGroups;

    public function __construct($config = array()){
        $this->_user = array_key_exists('user', $config) ? $config['user'] : '';
        $this->_input = array_key_exists('input', $config) ? $config['input'] : '';
        $this->_customerTable = array_key_exists('customerTable', $config) ? $config['customerTable'] : '';
        $this->_db = array_key_exists('database', $config) ? $config['database'] : '';
        if( '' === $this->_user || '' === $this->_input || '' === $this->_customerTable || '' === $this->_db )
            throw new Exception(JText::_('COM_MEIADMIN_CUSTOMER_USER_GROUP_ERROR'));
    }

    // Some extra db queries here shouldn't affect performance too adversely
    public function assignUserGroups(){
        $this->_extractPossibleGroups();
        foreach($this->_getActualGroups() AS $group){
            $possibleFieldName = strtolower(str_replace(' ', '', $group->title));
            if(!$this->_groupExists($possibleFieldName)) continue;
            if($this->_fieldSet($possibleFieldName)) {
                JUserHelper::addUserToGroup($this->_user->id, $group->id);
            } else {
                JUserHelper::removeUserFromGroup($this->_user->id, $group->id);
            }
        }
        return true;
    }

    protected function _extractPossibleGroups(){
        $this->_possibleGroups = array('registered', 'public');
        foreach ($this->_customerTable->getTableFields() AS $field) {
            if(strpos($field->Field,'access') === false) continue;
            if($field->Field === 'access_account') continue;
            $this->_possibleGroups[] = substr($field->Field, 7);
        }
    }

    protected function _getActualGroups(){
        $query = $this->_db->getQuery(true);
        $query->select('title,id')->from('#__usergroups');
        $this->_db->setQuery($query);
        return $this->_db->loadObjectList('title');
    }

    protected function _groupExists($possibleFieldName) {
        return in_array($possibleFieldName, $this->_possibleGroups);
    }

    protected function _fieldSet($possibleFieldName){
        if(in_array($possibleFieldName, array('registered', 'public'))){
            return true;
        }
        return ($this->_input['access_' . $possibleFieldName]);
    }
}