<?php

defined('_JEXEC') or die;

class MeiModelCustomers extends BBDFOFModel
{
    protected $_iconPath = 'images/profiles/';
    protected $_defaultImage = 'default.jpg';
    protected $_user = null;
    protected $_table = null;
    protected $_crypt = null;
    protected $_passwordAlphabet;
    protected $_password;
    protected $_mailer;

    public function __construct($config = array())
    {
        parent::__construct($config);
        $this->_loadNeededLibraries();
        if (!JFolder::exists($this->_iconPath)) JFolder::create($this->_iconPath, (int) 0644);
    }

    protected function _loadNeededLibraries()
    {
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');
    }

    public function getTable($name = 'Customers', $prefix = 'MeiadminTable', $options = array())
    {
        return parent::getTable($name, $prefix, $options);
    }

    protected function _loadUser($id = null)
    {
        $this->_user = JFactory::getUser($id);
    }

    public function getForm($data = array(), $loadData = true, $source = null)
    {
        $form = parent::getForm($data, $loadData, $source);
        $form->setFieldAttribute('image', 'directory', 'profiles');
        return $form;
    }

    protected function onAfterGetItem(&$record)
    {
        $customerData = $this->_loadCustomerData();
        $this->_addCustomerExclusions($customerData);
        $this->_configRecordWithData($record, $customerData);
        return $record;
    }

    protected function _loadCustomerData()
    {
        $this->_loadUser();
        $query = $this->_db->getQuery(true);
        $query->select('u.email, u.username, c.*');
        $query->from('#__users as u');
        $query->leftJoin('#__meiadmin_customers as c ON (c.fk_user_id = u.id)');
        $query->where('u.id = '.$this->_db->quote($this->_user->id));
        $this->_db->setQuery($query);
        return $this->_db->loadObject();
    }

    protected function _addCustomerExclusions(&$customerData)
    {
        $exclusions = $this->_loadCurrentExclusions($customerData->fk_user_id);
        foreach ($exclusions['types'] as $type){
            $customerData->$type = '0';
        }
    }

    protected function _configRecordWithData(&$row, $data)
    {
        $row->bind($data);
        $rowFieldsToSet = array(
            'region'            => 'fk_region_id',
            'email'             => '',
            'username'          => '',
            'general'           => '',
            'service'           => '',
            'integration'       => '',
            'specifications'    => '',
            'drawing'           => '',
            'application'       => '',
            'variant'           => '',
            'channel'           => '',
            'combined'          => '',
            'service-tool'      => '',
            'api'               => '',
            'coin'              => ''
        );
        foreach($rowFieldsToSet as $formField => $dbField){
            $field = ($dbField != '') ? $dbField : $formField;
            if (isset($data->$field)) $row->$formField = $data->$field;
        }
    }

    protected function onBeforeSave(&$data, &$table)
    {
        if (!parent::onBeforeSave($data, $table)) return false;
        $this->_changeFormFieldNamesForStoring($data);
        $this->_saveFileExclusions($data);
        $this->_storeUserEmail($data);
        return true;
    }

    protected function onAfterSave(&$table)
    {
        $this->_removeDefaultProfileImage($table);
        parent::onAfterSave($table);
    }

    protected function _removeDefaultProfileImage(&$table)
    {
        if ($table->image !== $this->_iconPath . $this->_defaultImage) return true;
        $table->image = '';
        return $table->store();
    }

    protected function _changeFormFieldNamesForStoring(&$data)
    {
        $data['fk_region_id'] = $data['region'];
    }

    protected function _saveFileExclusions($data)
    {
        $submittedExclusions = $this->_loadSubmittedExclusions($data);
        $currentExclusions = $this->_loadCurrentExclusions($data['fk_user_id']);
        $exclusionsToAdd = array_diff($submittedExclusions, $currentExclusions['types']);
        if (!empty($exclusionsToAdd)) $this->_addExclusions($exclusionsToAdd, $data['fk_user_id']);
        $exclusionsToDelete = array_diff($currentExclusions['types'], $submittedExclusions);
        if (!empty($exclusionsToDelete)) $this->_deleteExclusions($exclusionsToDelete, $currentExclusions);
    }

    protected function _loadSubmittedExclusions($data)
    {
        $fileTypes = array('general', 'service', 'integration', 'specifications', 'drawing', 'application', 'variant', 'channel', 'combined', 'service-tool', 'api', 'coin');
        $exclusions = array();
        foreach ($fileTypes as $type){
            if ($data[$type] == '0') $exclusions[] = $type;
        }
        return $exclusions;
    }

    protected function _loadCurrentExclusions($uid)
    {
        $query = $this->_db->getQuery(true);
        $query->select('id, file_type')
            ->from('#__meiadmin_customer_exclusions')
            ->where('fk_user_id = '.$this->_db->quote($uid));
        $this->_db->setQuery($query);
        $exclusions = $this->_db->loadObjectList('file_type');
        $exclusions['types'] = array_keys($exclusions);
        return $exclusions;
    }

    protected function _addExclusions($exclusions, $id)
    {
        $table = $this->getTable('customer_exclusions');
        $table->set('_tbl_key', 'id');
        foreach ($exclusions as $exclusion){
            $data = array('fk_user_id' => $id, 'file_type' => $exclusion);
            if (!$table->save($data)) throw new Exception(JText::_('COM_MEIADMIN_CUSTOMER_SUBSCRIPTION_SAVE_ERROR'));
            $table->id = null;
            $table->reset();
        }
    }

    protected function _deleteExclusions($exclusionsToDelete, $currentExclusions)
    {
        $table = $this->getTable('customer_exclusions');
        $table->set('_tbl_key', 'id');
        foreach ($exclusionsToDelete as $type){
            if (!$table->delete($currentExclusions[$type]->id)) throw new Exception(JText::_('COM_MEIADMIN_CUSTOMER_SUBSCRIPTION_DELETE_ERROR'));
        }
    }

    protected function _storeUserEmail($data)
    {
        $this->_loadUser();
        $params = array('email' => $data['email']);
        if(!$this->_user->bind($params)) throw new Exception(JTEXT::_('COM_MEI_CUSTOMER_EMAIL_BIND_ERROR'));
        if(!$this->_user->save(true)) throw new Exception(JText::_('COM_MEI_CUSTOMER_PASSWORD_SAVE_FAILED'));
    }

    public function saveNewPassword(MeiPassword $password, $input)
    {
        if (is_null($username = $this->_loadUsername($input))) throw new Exception(JText::_('COM_MEI_CUSTOMER_MISSING_USERNAME'));
        $id = (int) JUserHelper::getUserId($username);
        $this->_loadUser($id);
        $this->_table = $this->getTable();
        $password->setConfig(array(
            'user'  => $this->_user,
            'input' => $input,
            'db'    => $this->_db,
            'table' => $this->_table,
            'task'  => 'update'
            ));
        $password->save();
    }

    protected function _loadUsername($finput)
    {
        if ($username = $finput->get('username')) return $username;
        $this->_loadUser();
        if ($username = $this->_user->username) return $username;
        return false;
    }

    public function resetPassword(MeiPassword $password, $input)
    {
        $this->_loadUser();
        $this->_table = $this->getTable();
        $password->setConfig(array(
            'user'  => $this->_user,
            'input' => $input,
            'db'    => $this->_db,
            'table' => $this->_table,
            'task'  => 'reset'
        ));
        $password->save();
        return $password->getNewPassword();
    }

    public function setMailer($mailer)
    {
        $this->_mailer = $mailer;
    }

    public  function emailNewPassword($password)
    {
        if(!$this->_mailer) throw new Exception(JTEXT::_('COM_MEI_CUSTOMER_MISSING_MAILER'));
        $this->_password = $password;
        $this->_setMailerSender();
        $this->_setMailerSubject();
        $this->_setMailerBody();
        $this->_setRecipient(); print_r($this->_mailer);
        //$this->_mailer->Send();
        $this->_mailer->ClearAllRecipients();
    }

    protected function _setMailerSender()
    {
        $config = JFactory::getConfig();
        $sender = array(
            $config->get('mailfrom'),
            $config->get('fromname')
        );
        $this->_mailer->setSender($sender);
    }

    protected function _setMailerSubject()
    {
        $subject = 'Password Reset Request';
        $this->_mailer->setSubject($subject);
    }

    protected function _setMailerBody()
    {
        $body = $this->_getMailBodyContents();
        $this->_mailer->setBody($body);
    }

    protected function _setRecipient()
    {
        $this->_mailer->ClearAllRecipients();
        $this->_mailer->addRecipient($this->_user->email);
    }

    protected function _getMailBodyContents()
    {
        $site = str_ireplace('administrator/', '', JURI::base());
        return <<<CONTENTS
Hello,

This is a notice to inform you that your password has been reset at $site.

Here is your temporary password: $this->_password. You will be prompted to choose a new password after trying to log in.

Sincerely,

Site Administrators

CONTENTS;
    }
}