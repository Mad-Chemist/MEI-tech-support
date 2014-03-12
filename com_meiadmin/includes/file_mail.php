<?php

defined('_JEXEC') or die;

class MeiadminFileMailCoordinator
{
    protected $_productId;
    protected $_productTitle;
    protected $_fileId;
    protected $_fileTitle;
    protected $_fileName;
    protected $_type;
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
        $this->_loadProductDetails();
        $this->_loadCustomers();
        $this->_configBatchBuilder();
        $batch = $this->_batchBuilder->getQueue();
        $this->_mailer->send($batch);
    }

    protected function _loadProductDetails()
    {
        $query = $this->_db->getQuery(true);
        $query->select('f.title as fileTitle, f.type, p.title as productTitle, v.filename as fileName, v.meiadmin_file_version_id')
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
        $this->_type = $result->type;
    }

    protected function _loadCustomers()
    {
        $this->_db->setQuery('SELECT u.email
                            FROM #__meiadmin_customer_subscriptions as s
                            INNER JOIN #__users as u ON (u.id = s.fk_user_id)
                            INNER JOIN #__meiadmin_customers as c ON (c.fk_user_id = u.id)
                            WHERE s.fk_product_id = ' . $this->_db->quote($this->_productId) .
                            ' AND s.id NOT IN (SELECT fk_subscription_id FROM #__meiadmin_customer_exclusions AS x INNER JOIN #__meiadmin_customer_subscriptions as s ON (s.id = x.fk_subscription_id)
                                                        WHERE s.fk_product_id = ' . $this->_db->quote($this->_productId) . ' AND x.file_type = ' . $this->_db->quote($this->_type) . ')');
        $this->_customers = $this->_db->loadObjectList();
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
/*        return <<<CONTENTS
Hello,

This is a notice to inform you that the product "$this->_productTitle" has an updated file "$this->_fileTitle" ($this->_fileName).

Sincerely,

Site Administrators

CONTENTS;*/

return "Hello,<br><br>This is a notice to inform you that the product, <strong>".$this->_productTitle."</strong>, has an updated file: <strong>".$this->_fileTitle."</strong> (<strong>".$this->_fileName."</strong>).<br><br>Sincerely, <br> Site Administrators";
    }
}