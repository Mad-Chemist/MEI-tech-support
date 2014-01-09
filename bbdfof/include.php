<?php
/**
 *  @package     FrameworkOnFramework
 *  @subpackage  include
 *  @copyright   Copyright (c)2010-2012 Nicholas K. Dionysopoulos
 *  @license     GNU General Public License version 2, or later
 *
 *  Initializes BBDFOF
 */

defined('_JEXEC') or die();

if (!defined('BBDFOF_INCLUDED'))
{
    define('BBDFOF_INCLUDED', '2.0.6');

	// Register a debug log
	if (defined('JDEBUG') && JDEBUG)
	{
		JLog::addLogger(array('text_file' => 'BBDFOF.log.php'), JLog::ALL, array('BBDFOF'));
	}

	// Register the BBDFOF autoloader
    require_once __DIR__ . '/autoloader/fof.php';
	BBDFOFAutloaderBBDFOF::init();
}