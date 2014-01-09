<?php
/**
 * @package		contactus
 * @copyright	Copyright (c)2013 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license		GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

require_once JPATH_LIBRARIES.'/bbdfof/include.php';

BBDFOFDispatcher::getTmpInstance('com_meiadmin')->dispatch();