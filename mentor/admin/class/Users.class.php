<?php
require_once realpath(dirname(__FILE__)) . "/Action.class.php";
class Users extends Action{
	public function __construct(){
		parent::__construct('users'); 
	}

    public function getList(?string $order = NULL, int $limit = 999999) {
        return $this->query("SELECT * FROM $this->table ORDER BY item_order ASC LIMIT $limit");
    }

    public function getData(string $email, string $password) {
        return $this->query("SELECT * FROM $this->table WHERE email='$email' AND password='$password' LIMIT 1")->Fetch();
    }
}