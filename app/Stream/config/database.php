<?php


class Database {
//	private $host = "213.201.143.91";
        private $host = "83.98.243.185";
	private $db = "nts_training";
	private $username = "root";
	private $password = "wgnd8b";

	public $conn;
	

	public function getConnection(){
		$this->conn = null;
		try{
			$this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db, $this->username, $this->password);
			
		}catch(PDOExeption $exception){
			return "Conection Error: ". $exception->getMessage();
		}

		return $this->conn;
	}


}
