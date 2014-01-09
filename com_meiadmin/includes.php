<?php

defined('_JEXEC') or die;

require_once JPATH_LIBRARIES.'/bbdfof/include.php';

JLoader::register('MeiadminUserGroups', JPATH_COMPONENT.'/includes/user_groups.php');
JLoader::register('MeiadminFileMailCoordinator', JPATH_COMPONENT.'/includes/file_mail.php');
JLoader::register('BatchMailerJoomla', JPATH_COMPONENT.'/includes/BatchMailerJoomla.php');
JLoader::register('MailBatchBuilder', JPATH_COMPONENT.'/includes/MailBatchBuilder.php');
JLoader::register('BBDFOFFormFieldCustomerlist', JPATH_COMPONENT.'/models/fields/customerlist.php');