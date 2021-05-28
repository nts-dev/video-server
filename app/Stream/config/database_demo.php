<?php


class Database {
	private $host = "83.98.243.187";
	private $db = "nts_training";
	private $username = "poweruser";
	private $password = "iMfFIg7gAxCmstc76KyQ";
        
        
       
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
