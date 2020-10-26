<?php
class DatabaseDemo{
  
    // specify your own database credentials
    private $host = "localhost";
    private $db_name = "sptsea_sp_demo_basic";
    private $username = "sptsea_sporttrade";
    private $password = "*?UEwu46YPy5";
    public $conn;
  
    // get the database connection
    public function getConnection(){
  
        $this->conn = null;
  
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
  
        return $this->conn;
    }
}
?>