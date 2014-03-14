<?php

defined('_JEXEC') or die;

class MeiModelPassword extends BBDFOFModel
{
    protected $_user;
    protected $_customerRow;
    protected $_task;
    protected $_crypt;
    protected $_passwordAlphabet;
    protected $_newPassword;

    public function __construct($config = array())
    {
        parent::__construct($config);
        $this->_customerRow = $this->getTable();
    }

    public function setTask($task)
    {
        $this->_task = $task;
    }

    public function setUser($user)
    {
        $this->_user = $user;
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

    public function updatePasswordExpiration()
    {
        if (!$id = $this->input->get('meiadmin_customer_id')) throw new Exception(JText::_('COM_MEI_MISSING_CUSTOMER_ID'));
        $this->_customerRow->load($id);
        $interval = $this->_customerRow->password_expiration_interval;
        $expiration = $this->_getPasswordExpiration($interval);
        $this->_customerRow->password_expiration = $expiration;
        if (!$this->_customerRow->store()) throw new Exception(JText::_('COM_MEI_CUSTOMER_SAVE_FAILED'));
    }

    public function getTable($name = 'Customers', $prefix = 'MeiadminTable', $options = array())
    {
        return parent::getTable($name, $prefix, $options);
    }

    protected function _UpdatePassword()
    {
        $currentPasswordFromForm = $this->input->get('password');
        $newPasswordFromForm = $this->input->get('newPassword');
        if(!$this->_verifyCurrentPassword($currentPasswordFromForm)) throw new Exception(JText::_('COM_MEI_CUSTOMER_INCORRECT_PASSWORD'));
        if($this->_tryingToUseSamePassword($newPasswordFromForm)) throw new Exception(JText::_('COM_MEI_CUSTOMER_NEED_DIFFERENT_PASSWORD'));
        $this->_saveCustomerPassword();
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

    protected function _saveCustomerPassword()
    {
        $params = array('password' => $this->input->get('newPassword'), 'password2' => $this->input->get('newPasswordConfirm'));
        if(!$this->_user->bind($params)) throw new Exception(JTEXT::_('COM_MEI_CUSTOMER_NEW_PASSWORDS_MISMATCH'));
        if(!$this->_user->save(true)) throw new Exception(JText::_('COM_MEI_CUSTOMER_PASSWORD_SAVE_FAILED'));
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
        $this->_saveCustomerPassword();
    }

    protected function _generatePassword()
    {
        $username = $this->_user->username;
        $now = time();
        return hash('crc32', $username.$now);
    }

    protected function _addNewPasswordToInput()
    {
        $this->input->set('newPassword', $this->_newPassword);
        $this->input->set('newPasswordConfirm', $this->_newPassword);
    }
}