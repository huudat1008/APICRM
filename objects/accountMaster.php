<?php
include_once '../config/core.php';
class Accountmaster
{
    // database connect and table name
    private $conn;
    private $table_name = "accounts";

    // object properties
    public $id;
    public $user_id;
    public $pool_id;
    public $groups;
    public $currentBalance;
    public $joiningDate;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
        $database = new ApiConfig();
        $this->table_accounts = $database->dbprefix_master.$this->table_name;
        $this->dbprefix = $database->dbprefix_master;
    }

    //Get Infor group by account id
    public function getDataLogAction()
	{
        $query = "SELECT * FROM " . $this->dbprefix . "log_action";
        //prepare query statement
        $stmt = $this->conn->prepare($query);
        // bind id of product  to be updated
        $stmt->execute();
        return $stmt;
    }

    //Get Infor group by account id
    public function getDataLogActionByUser($id)
    {
        $query = "SELECT * FROM " . $this->dbprefix . "log_action WHERE user_id = ?";
        //prepare query statement
        $stmt = $this->conn->prepare($query);
        // bind id of product  to be updated
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt;
    }

    //Get Infor group by account id
    public function getListUser()
    {
        $query = "SELECT u.*, l.timestart as time FROM " . $this->dbprefix . "users u
        LEFT JOIN ".$this->dbprefix."log_action l ON l.user_id = u.id AND NOT EXISTS (
            SELECT 1 FROM ".$this->dbprefix."log_action l1
            WHERE l1.user_id = u.id AND l1.id > l.id) ORDER BY u.id DESC";
        //prepare query statement
        $stmt = $this->conn->prepare($query);
        // bind id of product  to be updated
        $stmt->execute();
        return $stmt;
    }

    //Update Infor users table
    public function insertUser()
    {
        $query = "INSERT INTO " . $this->dbprefix . "users 
            SET name = :name, username = :username, email = :email, password = :password,
            block = :block, sendEmail = :sendEmail,  registerDate = :registerDate,
            lastvisitDate = :lastvisitDate, activation = :activation,  params = :params";
        //prepare query statement
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->block = htmlspecialchars(strip_tags($this->block));
        $this->sendEmail = htmlspecialchars(strip_tags($this->sendEmail));
        $this->registerDate = htmlspecialchars(strip_tags($this->registerDate));
        $this->lastvisitDate = htmlspecialchars(strip_tags($this->lastvisitDate));
        $this->activation = htmlspecialchars(strip_tags($this->activation));
        $this->params = htmlspecialchars(strip_tags($this->params));
        // bind id of product  to be updated
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':block', $this->block);
        $stmt->bindParam(':sendEmail', $this->sendEmail);
        $stmt->bindParam(':registerDate', $this->registerDate);
        $stmt->bindParam(':lastvisitDate', $this->lastvisitDate);
        $stmt->bindParam(':activation', $this->activation);
        $stmt->bindParam(':params', $this->params);
        // execute the query
        if($stmt->execute()){
            $id = $this->conn->lastInsertId();
            $query = "INSERT INTO " . $this->dbprefix . "user_usergroup_map SET user_id = ". $id .", group_id = 2";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $id;
        }
        return false;
    }
}