<?php

defined('_JEXEC') or die;

class MeiModelCustomers extends BBDFOFModel
{
    protected $_iconPath = 'images/user-images/';
    protected $_defaultImage = 'mei-default.jpg';
    protected $_ndaPath = 'images/user-NDA/';
    protected $_user = null;
    protected $_table = null;
    protected $_crypt = null;
    protected $_password;
    protected $_mailer;
    protected $_data;

    protected function _loadNeededLibraries()
    {
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');
    }

    public function getCustomerImage()
    {
        if (!$this->_data) $this->_data = $this->_loadCustomerData();
        return $this->_data->image;
    }

    public function getDefaultImage()
    {
        return $this->_iconPath . $this->_defaultImage;
    }

    public function getSubscriptions()
    {
        $subscriptionModel = BBDFOFModel::getAnInstance('Subscriptions', 'MeiModel', array('user' => JFactory::getUser()));
        return $subscriptionModel->getSubscriptions();
    }

    public function getNdaExpiration()
    {
        if (!$this->_data) $this->_data = $this->_loadCustomerData();
        return $this->_data->nda_expiration;
    }

    protected function _loadInputData()
    {
        return $this->input->getData();
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
        return $form;
    }

    protected function _loadForm($formName)
    {
        if (!$this->_data) $this->_data = $this->_loadCustomerData();
        $this->_formData = $this->_data;
        $this->_data = null;
        $formSource = 'form.'.$formName;
        return $this->loadForm($formName, $formSource, array('load_data' => true), true);
    }

    public function getPasswordForm()
    {
        return $this->_loadForm('password');
    }

    public function getImageForm()
    {
        return $this->_loadForm('image');
    }

    public function getNdaForm()
    {
        return $this->_loadForm('nda');
    }

    public function getAttributesForm()
    {
        return $this->_loadForm('attributes');
    }

    public function saveAttributes()
    {
        $data = $this->_loadInputData();
        $this->save($data);
        $this->_storeUserEmail($data);
    }

    public function saveImage()
    {
        $image = $this->input->files->get('image', array());
        $this->_uploadFile($image, 'image');
    }

    public function saveNda()
    {
        $nda = $this->input->files->get('nda', array());
        if ($this->_uploadFile($nda, 'nda')) $this->_setNdaExpiration();
    }

    protected function _setNdaExpiration()
    {
        $data = $this->_loadInputData();
        $today = new DateTime;
        $ndaExpiration = $today->modify('+60 days');
        $data['nda_expiration'] = $ndaExpiration->format('Y-m-d');
        return $this->save($data);
    }

    protected function _uploadFile(array $file, $type)
    {
        if ($this->_nothingUploaded($file)) return true;
        if ($file['error'] !== 0) throw new Exception(JText::_('COM_MEI_FILE_UPLOAD_ERROR'));
        $this->_loadNeededLibraries();
        $path = $this->_confirmDirectoryForFile($type);
        $fileName = $this->_loadFileName($type, $file);
        $filePath = JPATH_BASE . '/' . $path . $fileName;
        if (!JFile::upload($file['tmp_name'], $filePath)) throw new Exception(JText::_('COM_MEI_FILE_UPLOAD_FAILED'));
        return $this->_storeFilePath($path, $fileName, $type);
    }

    protected function _nothingUploaded($file)
    {
        return (empty($file) || $file['error'] === 4);
    }

    protected function _confirmDirectoryForFile($type)
    {
        $paths = array('image' => $this->_iconPath, 'nda' => $this->_ndaPath);
        $path = $paths[$type];
        if (!JFolder::exists($path)) JFolder::create($path, 0755);
        return $path;
    }

    protected function _loadFileName($type, $file){
        $this->_loadUsername();
        $extension = JFile::getExt($file['name']);
        if ($type === 'image') return $this->_user->username . '.' . $extension;
        if ($type === 'nda') return $this->_user->username.'_nda.'.$extension;
        return false;
    }

    protected function _storeFilePath($path, $fileName, $type)
    {
        $data = $this->_loadInputData();
        $data[$type] = $path . $fileName;
        return $this->save($data);
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

    protected function _storeUserEmail($data)
    {
        $this->_loadUser();
        $params = array('email' => $data['email']);
        if(!$this->_user->bind($params)) throw new Exception(JTEXT::_('COM_MEI_CUSTOMER_EMAIL_BIND_ERROR'));
        if(!$this->_user->save(true)) throw new Exception(JText::_('COM_MEI_CUSTOMER_PASSWORD_SAVE_FAILED'));
    }

    public function saveNewPassword($passwordModel, $setExpiration)
    {
        if (is_null($username = $this->_loadUsername($this->input))) throw new Exception(JText::_('COM_MEI_CUSTOMER_MISSING_USERNAME'));
        $id = (int) JUserHelper::getUserId($username);
        $this->_loadUser($id);
        $this->_table = $this->getTable();
        $passwordModel->setTask('update');
        $passwordModel->setUser($this->_user);
        $passwordModel->save();
        if ($setExpiration) $passwordModel->updatePasswordExpiration();
    }

    protected function _loadUsername()
    {
        if ($username = $this->input->get('username')) return $username;
        $this->_loadUser();
        if ($username = $this->_user->username) return $username;
        return false;
    }

    public function resetPassword($passwordModel)
    {
        $this->_loadUser();
        $this->_table = $this->getTable();
        $passwordModel->setTask('reset');
        $passwordModel->setUser($this->_user);
        $passwordModel->save();
        return $passwordModel->getNewPassword();
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
        $this->_setRecipient();
        $this->_mailer->Send();
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

Here is your temporary password: $this->_password. You can choose a new password by going to your account page, after logging in.

Sincerely,

Site Administrators

CONTENTS;
    }
}