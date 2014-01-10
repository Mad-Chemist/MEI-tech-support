<?php
/**
 *  @package     FrameworkOnFramework
 *  @subpackage  autoloader
 *  @copyright   Copyright (c)2010-2012 Nicholas K. Dionysopoulos
 *  @license     GNU General Public License version 2, or later
 */

defined('BBDFOF_INCLUDED') or die();

/**
 * The main class autoloader for BBDFOF itself
 */
class BBDFOFAutloaderBBDFOF
{
	/**
	 * An instance of this autoloader
	 *
	 * @var   BBDFOFAutoloaderBBDFOF
	 */
	public static $autoloader = null;

	/**
	 * The path to the BBDFOF root directory
	 *
	 * @var   string
	 */
	public static $BBDFOFPath = null;

	/**
	 * Initialise this autoloader
	 *
	 * @return  BBDFOFAutloaderBBDFOF
	 */
	public static function init()
	{
		if (self::$autoloader == NULL)
		{
			self::$autoloader = new self();
		}

		return self::$autoloader;
	}

	/**
	 * Public constructor. Registers the autoloader with PHP.
	 *
	 * @return  void
	 */
	public function __construct()
	{
		self::$BBDFOFPath = realpath(__DIR__ . '/../');

		spl_autoload_register(array($this,'autoload_BBDFOF_core'));
	}

	/**
	 * The actual autoloader
	 *
	 * @param   string  $class_name  The name of the class to load
	 *
	 * @return  void
	 */
	public function autoload_BBDFOF_core($class_name)
	{
		// Make sure the class has a BBDFOF prefix
        if (substr($class_name, 0, 6) != 'BBDFOF')
		{
            return;
		}

		// Remove the prefix
        $class = substr($class_name, 6);

		// Change from camel cased (e.g. ViewHtml) into a lowercase array (e.g. 'view','html')
        $class = preg_replace('/(\s)+/', '_', $class);
        $class = strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $class));
        $class = explode('_', $class);

        // First try finding in structured directory format (preferred)
        $path = self::$BBDFOFPath . '/' . implode('/', $class) . '.php';

        if (@file_exists($path))
        {
            include_once $path;
        }

        // Then try the duplicate last name structured directory format (not recommended)
        if (!class_exists($class_name, false))
        {
			reset($class);
            $lastPart = end($class);
            $path = self::$BBDFOFPath . '/' . implode('/', $class) . '/' . $lastPart . '.php';
            if (@file_exists($path))
            {
                include_once $path;
            }
        }

        // If it still fails, try looking in the legacy folder (used for backwards compatibility)
        if (!class_exists($class_name, false))
        {
            $path = self::$BBDFOFPath . '/legacy/' . implode('/', $class) . '.php';
            if (@file_exists($path))
            {
                include_once $path;
            }
        }
	}
}