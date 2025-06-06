<?php
require_once 'Parameters.php';

class Db {

	private $host;
	private $db;
	private $user;
	private $pass;
	public $conection;

	public function __construct($host=Parameters::VALOR_DB_HOST,$db_name=Parameters::VALOR_DB_NAME,$db_user=Parameters::VALOR_DB_USER,$db_pass=Parameters::VALOR_DB_PASS) {		

		$this->host = $host;
		$this->db = $db_name;
		$this->user = $db_user;
		$this->pass = $db_pass;

		try {
           $this->conection = new PDO('mysql:host='.$this->host.'; dbname='.$this->db.'; charset=utf8mb4', $this->user, $this->pass);
        } catch (PDOException $e) {
			die("Error de conexion");
        }

	}

}
