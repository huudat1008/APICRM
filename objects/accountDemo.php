<?php
include_once '../config/core.php';
class Accountdemo
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
        $this->dbprefix = $database->dbprefix_demo;
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

    //Update Infor users table
    public function insertAccountDemo()
    {
        $query = "INSERT INTO " . $this->dbprefix . "accounts 
            SET user_id = :user_id, pool_id = 1, groups = 2, name = :name,
            registerDate = :registerDate, expiredTime = :expiredTime,  currentBalance = :currentBalance,
            joiningDate = :joiningDate, expiryDate = :expiryDate";
        //prepare query statement
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->registerDate = htmlspecialchars(strip_tags($this->registerDate));
        $this->expiredTime = htmlspecialchars(strip_tags($this->expiredTime));
        $this->currentBalance = htmlspecialchars(strip_tags($this->currentBalance));
        $this->joiningDate = htmlspecialchars(strip_tags($this->joiningDate));
        $this->expiryDate = htmlspecialchars(strip_tags($this->expiryDate));
        // bind id of product  to be updated
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':registerDate', $this->registerDate);
        $stmt->bindParam(':expiredTime', $this->expiredTime);
        $stmt->bindParam(':currentBalance', $this->currentBalance);
        $stmt->bindParam(':joiningDate', $this->joiningDate);
        $stmt->bindParam(':expiryDate', $this->expiryDate);
        // execute the query
        if($stmt->execute()){
            return true;
        }
        return false;
    }
    public function updateBlockUser()
    {
        $query = "UPDATE " . $this->dbprefix . "users 
            SET block = 1 where id = :id";
        //prepare query statement
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        // bind id of product  to be updated
        $stmt->bindParam(':id', $this->user_id);
        // execute the query
        if($stmt->execute()){
            return true;
        }
        return false;
    }
    public function updateUnBlockUser()
    {
        $query = "UPDATE " . $this->dbprefix . "users 
            SET block = 0 where id = :id";
        //prepare query statement
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        // bind id of product  to be updated
        $stmt->bindParam(':id', $this->user_id);
        // execute the query
        if($stmt->execute()){
            return true;
        }
        return false;
    }
}