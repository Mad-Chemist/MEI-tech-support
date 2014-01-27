<?php

defined('_JEXEC') or die();

class MeiPassword
{
    protected $_user;
    protected $_input;
    protected $_db;
    protected $_table;
    protected $_task;
    protected $_crypt;
    protected $_passwordAlphabet;
    protected $_newPassword;

    public function setConfig($config = array())
    {
        if (!isset($config['user'])) throw new Exception(JText::_('COM_MEI_PASSWORD_MISSING_CONFIG_USER'));
        if (!isset($config['input'])) throw new Exception(JText::_('COM_MEI_PASSWORD_MISSING_CONFIG_INPUT'));
        if (!isset($config['db'])) throw new Exception(JText::_('COM_MEI_PASSWORD_MISSING_CONFIG_DB'));
        if (!isset($config['table'])) throw new Exception(JText::_('COM_MEI_PASSWORD_MISSING_CONFIG_TABLE'));
        if (!isset($config['task'])) throw new Exception(JText::_('COM_MEI_PASSWORD_MISSING_CONFIG_TASK'));
        $this->_user    = $config['user'];
        $this->_input   = $config['input'];
        $this->_db      = $config['db'];
        $this->_table   = $config['table'];
        $this->_task    = $config['task'];
    }

    public function getNewPassword()
    {
        return $this->_newPassword;
    }
    
    public function save()
    {
        if (!in_array($this->_task, array('update', 'reset'))) return false;
        if ($this->_task === 'update') $this->_updatePassword();
        if ($this->_task === 'reset') $this->_resetPassword();
    }
    
    protected function _UpdatePassword()
    {
        $currentPasswordFromForm = $this->_input->get('password');
        $newPasswordFromForm = $this->_input->get('newPassword');
        if(!$this->_verifyCurrentPassword($currentPasswordFromForm)) throw new Exception(JText::_('COM_MEI_CUSTOMER_INCORRECT_PASSWORD'));
        if($this->_tryingToUseSamePassword($newPasswordFromForm)) throw new Exception(JText::_('COM_MEI_CUSTOMER_NEED_DIFFERENT_PASSWORD'));
        $customerId = $this->_loadCustomerIdForUserId($this->_user->id);
        $this->_saveCustomerPassword($this->_input);
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

    protected function _saveCustomerPassword()
    {
        $params = array('password' => $this->_input->get('newPassword'), 'password2' => $this->_input->get('newPasswordConfirm'));
        if(!$this->_user->bind($params)) throw new Exception(JTEXT::_('COM_MEI_CUSTOMER_NEW_PASSWORDS_MISMATCH'));
        if(!$this->_user->save(true)) throw new Exception(JText::_('COM_MEI_CUSTOMER_PASSWORD_SAVE_FAILED'));
    }

    protected function _updateCustomerRow($id, $resetExpiration = false)
    {
        $this->_table->load($id);
        $interval = $this->_table->password_expiration_interval;
        $expiration = (!$resetExpiration) ? $this->_getPasswordExpiration($interval) : '0000-00-00';
        $this->_table->password_expiration = $expiration;
        return $this->_table->store();
    }

    protected function _getPasswordExpiration($interval)
    {
        $today = new DateTime();
        $expirationDate = $today->modify('+'.$interval.' days');
        return $expirationDate->format('Y-m-d');
    }

    protected function _resetPassword()
    {
        $this->_newPassword = $this->_generatePassword();
        $this->_addNewPasswordToInput();
        $this->_saveCustomerPassword($this->_input);
        $customerId = $this->_loadCustomerIdForUserId($this->_user->id);
        $resetExpiration = true;
        if (!$this->_updateCustomerRow($customerId, $resetExpiration)) throw new Exception(JText::_('COM_MEI_CUSTOMER_CUSTOMER_SAVE_FAILED'));
    }

    protected function _generatePassword($passwordLength = 12)
    {
        $password = array();
        for ($i = 0; $i < $passwordLength; $i++){
            $password[] = $this->_getPasswordCharacter();
        }
        return implode($password);
    }

    protected function _getPasswordCharacter()
    {
        $alphabet = $this->_loadAlphabet();
        $alphabetLength = strlen($alphabet) - 1;
        $positionInAlphabet = rand(0, $alphabetLength);
        return $alphabet[$positionInAlphabet];
    }

    protected function _loadAlphabet()
    {
        if (!$this->_passwordAlphabet){
            $this->_passwordAlphabet = 'abcdefghijklmnopqrstuwxyz';
            $this->_passwordAlphabet .= 'ABCDEFGHIJKLMNOPQRSTUWXYZ';
            $this->_passwordAlphabet .= '0123456789';
        }
        return $this->_passwordAlphabet;
    }

    protected function _addNewPasswordToInput()
    {
        $this->_input->set('newPassword', $this->_newPassword);
        $this->_input->set('newPasswordConfirm', $this->_newPassword);
    }
}