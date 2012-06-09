<?php

namespace JawHare;

// Pull in the configuration file
require_once 'conf/config.php';

/**
 * Create and autoloader for the framework.
 * Note that the order of these two matters so that the module can overwrite the framework objects if it wishes
 */
spl_autoload_register(function($class_name)
{
	global $ini;

	if (strpos($class_name, 'JawHare\\') === 0)
	{
		$class = str_replace('JawHare\\', '', $class_name);
		$filename = $ini['class_dir'] . '/' . str_replace('\\', '/', $class) . '.class.php';
		if (file_exists($filename))
			include_once $filename;
	}
});

/**
 * Access or create the Cache instance.
 * 
 * @param array $config = null
 */
function Cache($config = null)
{
	static $instance = null;

	if ($instance !== null)
		return $instance;

	if ($config === null || !isset($config['class']) || !class_exists($config['class']))
	{
		$instance = new Cache\NullCache();
		return $instance;
	}
	else
	{
		$instance = new $config['class']($config);
		return $instance;
	}
}

/**
 * Access or create a database class instance.
 * 
 * @param array $config = null
 * @throws \Exception
 */
function Database($config = null)
{
	static $instance = null;

	if ($instance !== null)
		return $instance;

	if ($config === null || !isset($config['class']) || !class_exists($config['class']))
	{
		throw new \Exception('No database class available');
	}
	else
	{
		$instance = new $config['class']($config);
		return $instance;
	}
}

/**
 * Access or create a Session class instance.
 * 
 * @param array $config = null
 * @throws \Exception
 */
function Session($config = null)
{
	static $instance = null;

	if ($instance !== null)
		return $instance;

	if ($config === null || !isset($config['class']) || !class_exists($config['class']))
	{
		throw new \Exception('No session class available');
	}
	else
	{
		$instance = new $config['class'](!empty($config['autostart']));
		return $instance;
	}
}

/**
 * Retrieve an instance of Settings (singleton).
 * 
 * @param array $config = null
 */
function Settings($config = array())
{
	static $instance = null;

	if ($instance !== null)
		return $instance;

	$instance = new Settings($config);

	return $instance;
}

/**
 * Retrieve an Authentication class instance. (singleton)
 * 
 * @param array $config = null
 * @throws \Exception
 */
function Authentication($config = null)
{
	static $instance = null;

	if ($instance !== null)
		return $instance;

	if ($config === null || !isset($config['class']) || !class_exists($config['class']))
	{
		throw new \Exception('No authentication class available');
	}
	else
	{
		$instance = new $config['class']($config);
		return $instance;
	}
}
