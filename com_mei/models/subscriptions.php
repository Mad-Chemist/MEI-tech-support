<?php

defined('_JEXEC') or die;

/*
 * ALTER TABLE  `sdev_meiadmin_customer_exclusions` CHANGE  `fk_user_id`  `fk_subscription_id` INT( 11 ) NOT NULL ;
 */

class MeiModelSubscriptions extends BBDFOFModel
{

    protected $_user = null;
    protected $_subscriptions = array();

    public function __construct($config = array())
    {
        $this->_user = (array_key_exists('user', $config)) ? $config['user'] : JFactory::getUser();
        $this->_subscriptions = (array_key_exists('subscriptions', $config)) ? $config['subscriptions'] : array();
        return parent::__construct($config);
    }

    public function getSubscriptions()
    {
        $this->_loadSubscriptions();
        if(empty($this->_subscriptions)) return array();
        $this->_markExclusions();
        $this->_groupSubscriptionsByProductAndSection();
        return $this->_subscriptions;
    }

    /* A smarter SQL query may be able to trim these methods up to just 1 */
    protected function _loadSubscriptions()
    {
        $query = $this->_db->getQuery(true);
        $query->select("DISTINCT f.type, f.section, f.modified_on, p.title, s.id AS 'subid'")
                ->from('#__meiadmin_files as f')
                ->innerJoin('#__meiadmin_products as p ON (f.fk_product_id = p.meiadmin_product_id)')
                ->innerJoin('#__meiadmin_customer_subscriptions as s ON (s.fk_product_id = p.meiadmin_product_id)')
                ->where('s.fk_user_id = '.$this->_db->quote($this->_user->id))
                ->order('f.modified_on DESC');
        $this->_db->setQuery($query);
        $this->_subscriptions = $this->_db->loadObjectList();
    }

    /* Array_walk would probably work better here, but too busy to think through */
    protected function _markExclusions(){
        $exclusions = $this->_getExclusions();
        $subsWithExclusions = $this->_getExcludedSubscriptions($exclusions);
        foreach ($this->_subscriptions AS &$sub){
            if(!in_array($sub->subid, $subsWithExclusions)) {
                $sub->excluded = false;
            } else {
                $this->_checkSubscriptionTypeForExclusion($sub, $exclusions);
            }
        }
    }

    protected function _getExcludedSubscriptions($exclusions){
        $subList = array();
        foreach ($exclusions as $exclusion) {
            $subList[] = $exclusion['subid'];
        }
        return $subList;
    }

    protected function _checkSubscriptionTypeForExclusion(&$sub, $exclusions){
        if($this->_isFileTypeExcluded($sub, $exclusions)){
            $sub->excluded = true;
        } else {
            $sub->excluded = false;
        }
    }

    protected function _isFileTypeExcluded($sub, $exclusions){
        foreach($exclusions as $exclusion){
            if($exclusion['subid'] !== $sub->subid) continue;
            if($exclusion['file_type'] !== $sub->type) continue;
            return true;
        }
        return false;
    }

    protected function _getExclusions(){
        $query = $this->_db->getQuery(true);
        $query->select("s.id AS 'subid', x.file_type")
            ->from('#__meiadmin_customer_exclusions AS x')
            ->innerJoin('#__meiadmin_customer_subscriptions as s ON(x.fk_subscription_id = s.id)')
            ->where('s.fk_user_id = '.$this->_db->quote($this->_user->id));
        $this->_db->setQuery($query);
        return $this->_db->loadAssocList();
    }

    protected function _groupSubscriptionsByProductAndSection()
    {
        $sortedSubscriptions = array();
        foreach ($this->_subscriptions as $product) {
            $sortedSubscriptions[$product->section][$product->type][$product->title] = $product;
        }
        $this->_subscriptions = $sortedSubscriptions;
    }

    public function updateFileExclusions()
    {
        foreach($this->_subscriptions AS $subid => $subscription){
            $this->_processSubscription($subid, $subscription);
        }
    }

    protected function _processSubscription($subid, $subscription){
        $this->_deleteExclusions($subid);
        $addExclusionList = array();
        foreach ($subscription AS $type => $subscribe) {
            if(!$subscribe){
                $addExclusionList[] = $type;
            }
        }
        $this->_addExclusions($subid, $addExclusionList);
    }

    protected function _addExclusions($subid, $addExclusionList)
    {
        if(empty($addExclusionList)) return;
        foreach ($addExclusionList as $type){
            $exclusion = new stdClass();
            $exclusion->fk_subscription_id = (int) $subid;
            $exclusion->file_type = $type;
            if(!$this->_db->insertObject('#__meiadmin_customer_exclusions',
                                        $exclusion,
                                        'id')) throw new Exception($this->_db->getError());
        }
    }

    protected function _deleteExclusions($subid)
    {
        if(!$subid) return;
        $query = $this->_db->getQuery(true);
        $query->delete('#__meiadmin_customer_exclusions')
            ->where('fk_subscription_id = ' . $this->_db->quote($subid));
        $this->_db->setQuery($query);
        return $this->_db->execute();
    }

}