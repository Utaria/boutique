<?php  
class Controller
{

	protected $config;

	protected $DB = null;

	public $d  = array();

	public function __construct($config, $database)
	{
		$this->config = $config;
		$this->DB = $database;
	}

	public function getData()
	{
		return $this->d;
	}

	public function set($d)
	{
		$this->d = $d;
	}

}
?>