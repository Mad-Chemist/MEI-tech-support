<?php 
/*-----------------------------------------
  License: GPL v 3.0 or later
-----------------------------------------*/

defined ('_JEXEC') or die;

jimport('joomla.mail.mail');

class BatchMailerJoomla {

  /* We have to buffer output because JMail doesn't pass arguments onto PHPMailer which prints exceptions by default */
  public function send($batch){
    ob_start();
    $this->_sendBatch($batch);
    $contents = ob_get_contents();
    ob_end_clean();
    if($contents !== '') throw new Exception($contents);
  }

  protected function _sendBatch(&$batch){
    $mailer = JFactory::getMailer();
    $mailer->isHTML();
    $mailer->Encoding = "base64";
    foreach($batch as $item){
      $mailer->sendMail($item->fromEmail, $item->fromName, $item->email, $item->subject, $item->content, true);
      $mailer->ClearAddresses();
    }
  }

}