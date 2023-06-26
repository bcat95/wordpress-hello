<?php
require_once realpath(dirname(__FILE__)) . "/Action.class.php";
class CreditsPacks extends Action{
    public function __construct(){
        parent::__construct('credits_packs'); 
    }

    public function getList(?string $order = NULL, int $limit = 999999) {
        return $this->query("SELECT * FROM $this->table ORDER BY item_order ASC LIMIT $limit");
    }

    public function getListFront() {
        return $this->query("SELECT * FROM $this->table WHERE status='1' ORDER BY item_order ASC");
    }  

}