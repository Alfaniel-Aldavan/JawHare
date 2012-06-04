<?php

namespace JawHare\Database;

/**
 * The abstract base database class. 
 */
abstract class Database
{
	/**
	 * Stores the connection resources in an associative array
	 * @var array
	 */
	protected $conns = array();
	
	/**
	 * The settings passed to this database object
	 * @var array
	 */
	protected $settings = array();
	
	/**
	 * The name of the implemented database class.
	 * Used to load database specific storage classes
	 * @var string
	 */
	protected $dbtypename = '';
	
	/**
	 * Stores the various database storages that have been created.
	 * Done so the storages can be reused.
	 * @var array
	 */
	protected $dbstorages = array();

	/**
	 *
	 * @param array $settings The database configurations
	 */
	public function __construct($settings)
	{
		$this->settings = $settings;

		$this->connect();
	}

	/**
	 * Executes the specified query after doing any replacements
	 * @param string $query The query to execute
	 * @param array $replacements The replacement variables
	 * @param string $conn Defaults to 'host'.  The connection to use for this query.
	 * @return \JawHare\Database\DatabaseResult
	 * @throws DatabaseException 
	 */
	abstract public function query($query, $replacements = array(), $conn = 'host');
	abstract public function insert($table, $columns, $data, $querytype = 'insert', $conn = 'write');
	abstract public function update($table, $columns, $data, $conn = 'write');
	abstract protected function connect($set = 'all', $select_db = true, $reconnect = false);

	public function select($query, $replacements = array(), $conn = 'select')
	{
		return $this->query($query, $replacements, $conn);
	}

	public function load_storage($class, $namespace = '\\JawHare\\Storage\\')
	{
		$class = $namespace . $class . 'Storage' . $this->dbtypename;

		if (!isset($this->dbstorages[$class]))
			$this->dbstorages[$class] = new $class($this);

		return $this->dbstorages[$class];
	}
}