<?php

defined('_JEXEC') or die;

class MeiadminFileMailCoordinator
{
    protected $_productId;
    protected $_productTitle;
    protected $_fileId;
    protected $_fileTitle;
    protected $_fileName;
    protected $_mailer;
    protected $_batchBuilder;
    protected $_db;
    protected $_customers = array();

    public function __construct($productId, $fileId, MailBatchBuilder $batchBuilder, BatchMailerJoomla $mailer)
    {
        $this->_productId = $productId;
        $this->_fileId = $fileId;
        $this->_batchBuilder = $batchBuilder;
        $this->_mailer = $mailer;
        $this->_db = JFactory::getDbo();
    }

    public function sendAlert()
    {
        $this->_loadCustomers();
        $this->_loadProductDetails();
        $this->_configBatchBuilder();
        $batch = $this->_batchBuilder->getQueue();
        //$this->_mailer->send($batch); TODO: REMOVE COMMENT
    }

    protected function _loadCustomers()
    {
        $query = $this->_db->getQuery(true);
        $query->select('u.email')
            ->from('#__meiadmin_customers as c')
            ->innerJoin('#__users as u ON (u.id = c.fk_user_id)')
            ->where('FIND_IN_SET("'.$this->_productId.'", c.products)');
        $this->_db->setQuery($query);
        $this->_customers = $this->_db->loadObjectList();
    }

    protected function _loadProductDetails()
    {
        $query = $this->_db->getQuery(true);
        $query->select('f.title as fileTitle, p.title as productTitle, v.filename as fileName, v.meiadmin_file_version_id')
            ->from('#__meiadmin_files as f')
            ->innerJoin('#__meiadmin_products as p ON (p.meiadmin_product_id = f.fk_product_id)')
            ->innerJoin('#__meiadmin_file_versions as v ON (v.fk_file_id = f.meiadmin_file_id)')
            ->where('f.meiadmin_file_id = "'.$this->_fileId.'"')
            ->order('v.meiadmin_file_version_id DESC LIMIT 0, 1');
        $this->_db->setQuery($query);
        $result = $this->_db->loadObject();
        $this->_splitProductResults($result);
    }

    protected function _splitProductResults($result)
    {
        $this->_productTitle = $result->productTitle;
        $this->_fileTitle = $result->fileTitle;
        $this->_fileName = $result->fileName;
    }

    protected function _configBatchBuilder()
    {
        $config = JFactory::getConfig();
        $this->_batchBuilder->setFromEmail($config->get('mailfrom'));
        $this->_batchBuilder->setFromName($config->get('fromname'));
        $this->_batchBuilder->setSubject($this->_productTitle.' File Updated');
        $this->_addRecipients();
    }

    protected function _addRecipients()
    {
        $content = $this->_prepareContent();
        foreach ($this->_customers as $customer){
            $this->_batchBuilder->addRecipient($customer->email, $content);
        }
    }

    protected function _prepareContent()
    {
        return <<<CONTENTS
Hello,

This is a notice to inform you that the product "$this->_productTitle" has an updated file "$this->_fileTitle" ($this->_fileName).

Sincerely,

Site Administrators

CONTENTS;
    }
}