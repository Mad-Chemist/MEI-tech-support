<?php

defined('_JEXEC') or die();

class MeiadminModelCustomers extends BBDFOFModel
{

    protected $_user = null;
    protected $_listProcessed = false;

    public function __construct($config = array())
    {
        parent::__construct($config);
        $this->_user = JFactory::getUser();
    }

    public function getAccessRecord()
    {
        $this->_table = $this->getTable();
        $this->_table->load(array('fk_user_id' => $this->_user->id), true);
        return $this->_table;
    }

    public function hasSpecialAccess()
    {
        $viewLevels = $this->_user->getAuthorisedViewLevels();
        return (in_array('3', $viewLevels));
    }

    public function &getItemList($overrideLimits = false, $group = '')
    {
        $list = parent::getItemList($overrideLimits, $group);
        if (empty($list)) return $list;
        if ($this->_listProcessed) return $list;
        $this->_formatVisitDates($list);
        $this->_addEnabled($list);
        $this->_listProcessed = true;
        return $list;
    }

    protected function _formatVisitDates(&$list)
    {
        foreach ($list as &$item) {
            if ($this->_isValidVisit($item->lastvisitDate)) {
                $date = new JDate($item->lastvisitDate);
                $item->lastvisitDate = $date->format('M d, Y h:i A ');
            } else {
                $item->lastvisitDate = JText::_('COM_MEIADMIN_CUSTOMER_NOT_LOGGED_IN');
            }
        }
    }

    protected function _isValidVisit($date)
    {
        return ($date !== '12:00 11-30--0001' && $date !== '0000-00-00 00:00:00');
    }

    protected function _addEnabled(&$list)
    {
        foreach ($list as &$item) {
            $item->enabled = ($item->block) ? '0' : '1';
        }
    }

    public function buildQuery($overrideLimits = false)
    {
        $query = parent::buildQuery($overrideLimits);
        $query->clear('select');
        $query->select("u.*, #__meiadmin_customers.*, r.meiadmin_region_id, r.title AS 'region', c.meiadmin_channel_id, c.title AS 'channel'");
        $query->innerJoin('#__users AS u ON(u.id = `fk_user_id`)');
        $query->leftJoin('#__meiadmin_channels AS c ON(c.meiadmin_channel_id = `fk_channel_id`)');
        $query->leftJoin('#__meiadmin_regions AS r ON(r.meiadmin_region_id = `fk_region_id`)');
        $nameSearch = $this->_getNameSearch();
        if ($nameSearch) {
            $query->where('`name` LIKE(' . $this->_db->quote('%' . $nameSearch . '%') . ')');
        }
        return $query;
    }

    protected function _getNameSearch()
    {
        $jinput = JFactory::getApplication()->input;
        return $jinput->get('name', false, 'alnum');
    }

    protected function onAfterGetItem(&$record)
    {
        if (!$record->fk_user_id) return false;
        $user = JFactory::getUser($record->fk_user_id);
        $record->username = $user->username;
        $record->name = $user->name;
        $record->enabled = ($user->block) ? '0' : '1';
        $record->email = $user->email;
    }

    protected function onBeforeSave(&$data, &$table)
    {
        if (!parent::onBeforeSave($data, $table)) return false;
        $this->_filterForm($data);
        $this->_updateCustomerSubscriptions($data);
        $user = $this->_saveUserData($data);
        $this->_assignAccountNumber($data);
        if ($user) {
            $userGroups = new MeiadminUserGroups(array(
                'user'          => $user,
                'input'         => $data,
                'customerTable' => $table,
                'database'      => $this->_db
            ));
            $userGroups->assignUserGroups();
            return true;
        }
        return false;
    }

    protected function _assignAccountNumber(&$data)
    {
        $data['access_account'] = $data['fk_user_id'];
    }

    protected function _filterForm(&$data)
    {
        $form = $form = $this->getForm($data, false, 'form.form');
        $data = $form->filter($data);
    }

    protected function _updateCustomerSubscriptions($data)
    {
        $submittedSubscriptions = $data['products'];
        $currentSubscriptions = $this->_loadSubscriptions($data);
        $subscriptionsToAdd = array_diff($submittedSubscriptions, $currentSubscriptions['products']);
        if (!empty($subscriptionsToAdd)) $this->_addSubscriptions($subscriptionsToAdd, $data['fk_user_id']);
        $subscriptionsToDelete = array_diff($currentSubscriptions['products'], $submittedSubscriptions);
        if (!empty($subscriptionsToDelete)) $this->_deleteSubscriptions($subscriptionsToDelete, $currentSubscriptions);
    }

    protected function _loadSubscriptions($data)
    {
        $query = $this->_db->getQuery(true);
        $query->select('id, fk_product_id')
            ->from('#__meiadmin_customer_subscriptions')
            ->where('fk_user_id = '.$this->_db->quote($data['fk_user_id']));
        $this->_db->setQuery($query);
        $subscriptions = $this->_db->loadObjectList('fk_product_id');
        $subscriptions['products'] = array_keys($subscriptions);
        return $subscriptions;
    }

    protected function _addSubscriptions($subscriptions, $id)
    {
        $table = $this->getTable('customer_subscriptions');
        $table->set('_tbl_key', 'id');
        foreach ($subscriptions as $subscription){
            $data = array('fk_user_id' => $id, 'fk_product_id' => $subscription);
            if (!$table->save($data)) throw new Exception(JText::_('COM_MEIADMIN_CUSTOMER_SUBSCRIPTION_SAVE_ERROR'));
            $table->id = null;
            $table->reset();
        }
    }

    protected function _deleteSubscriptions($subscriptionsToDelete, $currentSubscriptions)
    {
        $table = $this->getTable('customer_subscriptions');
        $table->set('_tbl_key', 'id');
        foreach ($subscriptionsToDelete as $key => $product){
            if (!$table->delete($currentSubscriptions[$product]->id)) throw new Exception(JText::_('COM_MEIADMIN_CUSTOMER_SUBSCRIPTION_DELETE_ERROR'));
        }
    }

    protected function _saveUserData(&$input)
    {
        $password = $input['password'];
        $userId = ($input['fk_user_id']) ? ($input['fk_user_id']) : 0;
        $input['block'] = ($input['enabled']) ? '0' : '1';
        $input['password2'] = $password;
        $this->id = $input['meiadmin_customer_id'];
        unset($input['id']);
        $user = JFactory::getUser($userId);
        if (!$user->id) $this->_createUser($user, $input);
        $this->_saveUser($user, $input, $password);
        return $user;
    }

    protected function _createUser(&$user, &$input)
    {
        if (!$user->bind($input)) throw new Exception(JText::_('COM_MEIADMIN_CUSTOMER_SAVE_ERROR'));
        if (!$user->save()) throw new Exception(JText::_('COM_MEIADMIN_CUSTOMER_EMIAL_ALREADY_REGISTERED'));
        $input['fk_user_id'] = $user->id;
    }

    protected function _saveUser(&$user, &$input, $password)
    {
        $input['password'] = $password;
        $input['password2'] = $password;
        if (!$user->bind($input)) throw new Exception(JText::_('COM_MEIADMIN_CUSTOMER_SAVE_ERROR'));
        if (!$user->save()) throw new Exception(JText::_('COM_MEIADMIN_CUSTOMER_EMIAL_ALREADY_REGISTERED'));
    }

    public function publish($publish = 1, $user = null){
        if (!is_array($this->id_list) || empty($this->id_list)) return;
        $query = $this->_db->getQuery(true);
        $block = ($publish) ? 0 : 1;
        $idList = $this->_getInListUsers();
        $query->update('#__users AS u')->innerJoin('#__meiadmin_customers AS c ON (u.id = c.fk_user_id)')->set('block = ' . $this->_db->quote($block))->where('c.meiadmin_customer_id IN (' . $idList . ')');
        $this->_db->setQuery($query);
        return $this->_db->execute();
    }

    public function _getInListUsers(){
        return "'" . implode("','", $this->id_list) . "'";
    }

    protected function onBeforeDelete(&$id, &$table)
    {
        $this->_db->setQuery('DELETE u.* FROM #__users AS u INNER JOIN #__meiadmin_customers AS c ON (u.id = c.fk_user_id) WHERE c.meiadmin_customer_id = ' . $this->_db->quote($id));
        return $this->_db->execute();
    }
}