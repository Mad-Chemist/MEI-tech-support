<?php

defined('_JEXEC') or die;

class MeiModelCustomers extends BBDFOFModel
{
    protected $_user = null;
    protected $_table = null;
    protected $_crypt = null;

    public function getTable($name = 'Customers', $prefix = 'MeiadminTable', $options = array())
    {
        return parent::getTable($name, $prefix, $options);
    }

    public function saveNewPassword()
    {
        $finput = new BBDFOFInput;
        $currentPasswordFromForm = $finput->get('password');
        $newPasswordFromForm = $finput->get('newPassword');
        $id = (int) JUserHelper::getUserId($finput->get('username'));
        $this->_user = JUser::getInstance($id);
        if(!$this->_verifyCurrentPassword($currentPasswordFromForm)) throw new Exception(JText::_('COM_MEI_CUSTOMER_INCORRECT_PASSWORD'));
        if($this->_tryingToUseSamePassword($newPasswordFromForm)) throw new Exception(JText::_('COM_MEI_CUSTOMER_NEED_DIFFERENT_PASSWORD'));
        $customerId = $this->_loadCustomerIdForUserId($id);
        $this->_saveCustomerPassword($finput);
        if(!$this->_updateCustomerRow($customerId)) throw new Exception(JText::_('COM_MEI_CUSTOMER_CUSTOMER_SAVE_FAILED'));
    }

    protected function _verifyCurrentPassword($passwordToVerify)
    {
        if (!$this->_crypt) $this->_crypt = new JCryptPasswordSimple;
        return $this->_crypt->verify($passwordToVerify, $this->_user->password);
    }

    protected function _tryingToUseSamePassword($newPassword)
    {
        return $this->_verifyCurrentPassword($newPassword);
    }

    protected function _loadCustomerIdForUserId($id)
    {
        $query = $this->_db->getQuery(true);
        $query->select('meiadmin_customer_id')->from('#__meiadmin_customers')->where('fk_user_id = '.$this->_db->quote($id));
        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }

    protected function _saveCustomerPassword($input)
    {
        $params = array('password' => $input->get('newPassword'), 'password2' => $input->get('newPasswordConfirm'));
        if(!$this->_user->bind($params)) throw new Exception(JTEXT::_('COM_MEI_CUSTOMER_NEW_PASSWORDS_MISMATCH'));
        if(!$this->_user->save(true)) throw new Exception(JText::_('COM_MEI_CUSTOMER_PASSWORD_SAVE_FAILED'));
    }

    protected function _updateCustomerRow($id)
    {
        $this->_table = $this->getTable();
        $this->_table->load($id);
        $interval = $this->_table->password_expiration_interval;
        $expiration = (new DateTime())->modify('+'.$interval.' days')->format('Y-m-d');
        $this->_table->password_expiration = $expiration;
        return $this->_table->store();
    }
}