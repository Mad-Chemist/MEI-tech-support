<?php 
/*-----------------------------------------
  License: GPL v 3.0 or later
-----------------------------------------*/

defined('_JEXEC') or die;

class MailBatchBuilder {

  protected $_queue = array();
  protected $_fromEmail = null;
  protected $_fromName = null;
  protected $_subject = null;

  public function setFromEmail($email){
    $this->_fromEmail = $email;
  }

  public function setFromName($name){
    $this->_fromName = $name;
  }

  public function setSubject($subject){
    $this->_subject = $subject;
  }

  public function addRecipient($email, $content){
    $mailItem = $this->_getBaseMailItem();
    $mailItem->email = $email;
    $mailItem->content = $content;
    $this->_queue[] = $mailItem;
  }

  protected function _getBaseMailItem(){
    $this->_sanityCheck();
    $mailItem = new stdClass;
    $mailItem->fromEmail = $this->_fromEmail;
    $mailItem->fromName = $this->_fromName;
    $mailItem->subject = $this->_subject;
    return $mailItem;
  }

  protected function _sanityCheck(){
    if(!$this->_fromEmail || !$this->_fromName || !$this->_subject) {
      throw new Exception(JText::_('EXCEPTION_INSUFFICIENT_INFO_QUEUE'));
    }
  }

  public function getQueue(){
    return $this->_queue;
  }

}