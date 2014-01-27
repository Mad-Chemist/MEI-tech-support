<?php

defined('_JEXEC') or die();

class MeiViewCustomer extends BBDFOFViewForm
{
    protected $_ndaExpiration;
    protected $_customerImage;
    protected $_forms = array();
    protected $_isAdminUser = null;
    protected $_adminGroups = array('Super Users', 'Administrator', 'Product Admin', 'Customer Admin');

    public function display($tpl = null)
    {
        JHtmlBootstrap::framework();
        $this->_loadAdminLanguage();
        $this->_renderForms();
        $this->_insertCustomerImage($this->_forms['image']);
        $this->_insertNdaExpiration($this->_forms['nda']);
        return parent::display($tpl);
    }

    protected function _loadAdminLanguage()
    {
        $lang = JFactory::getLanguage();
        $extension = 'com_meiadmin';
        $base_dir = JPATH_SITE;
        $language_tag = 'en-GB';
        $reload = true;
        $lang->load($extension, $base_dir, $language_tag, $reload);
    }

    protected function _loadSubscriptions()
    {
        $this->items = $this->get('subscriptions');
    }

    protected function _renderForms()
    {
        $formsToRender = array('password', 'image', 'nda', 'attributes');
        foreach ($formsToRender as $form) {
            $this->_forms[$form] = $this->_getRenderedForm($form);
        }
    }

    protected function _getRenderedForm($formName)
    {
        $form = $this->get($formName . 'Form');
        $this->form = $form;
        $this->form->setView($this);
        return $this->getRenderedForm();
    }

    protected function _insertCustomerImage(&$html)
    {
        $this->_loadCustomerImage();
        $imageStringToInsert = $this->loadAnyTemplate('com_mei/customer/image');
        $imageInsertPosition = $this->_getStringReplacePosition($html, '</h3>');
        $html = substr_replace($html, $imageStringToInsert, $imageInsertPosition, 0);
    }

    protected function _loadCustomerImage()
    {
        $this->_customerImage = $this->get('customerImage');
        if ($this->_customerImage == '') $this->_customerImage = $this->get('DefaultImage');
    }

    protected function _ndaCurrent()
    {
        $today = new DateTime;
        return (strtotime($this->_ndaExpiration) > strtotime($today->format('Y-m-d')));
    }

    protected function _ndaExpired()
    {
        $today = new DateTime;
        $today->format('Y-m-d');
        return ($this->_ndaExpiration != '0000-00-00' && strtotime($this->_ndaExpiration) < strtotime($today->format('Y-m-d')));
    }

    protected function _ndaExpirationFormatted()
    {
        return date('M j, Y', strtotime($this->_ndaExpiration));
    }

    protected function _insertNdaExpiration(&$html)
    {
        $this->_ndaExpiration = $this->get('NdaExpiration');
        $ndaStringToInsert = $this->loadAnyTemplate('com_mei/customer/nda');
        $ndaInsertPosition = $this->_getStringReplacePosition($html, '</button>');
        $html = substr_replace($html, $ndaStringToInsert, $ndaInsertPosition, 0);
    }

    protected function _getStringReplacePosition($fullString, $stringToSearchFor)
    {
        $stringLength = strlen($stringToSearchFor);
        return strpos($fullString, $stringToSearchFor) + $stringLength;
    }

    protected function _isAdmin()
    {
        if ($this->_isAdminUser !== null) return $this->_isAdminUser;
        $userGroups = JoomlaUserGroups::getUsersGroupTitles();
        $this->_isAdminUser = (array_intersect($userGroups, $this->_adminGroups)) ? true : false;
        return $this->_isAdminUser;
    }

    protected function _noSubscriptions(){
        return (empty($this->items['documentation']) && empty($this->items['tool']) && empty($this->items['firmware']));
    }

}