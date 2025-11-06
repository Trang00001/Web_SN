<?php
require_once __DIR__ . '/../../core/Database.php';

class Friendship {
    private $account1ID;
    private $account2ID;
    private $db;

    

  public function __construct($account1ID = null, $account2ID = null) {
    $this->account1ID = $account1ID;
    $this->account2ID = $account2ID;
    $this->db = new Database();
}

    // Getter & Setter
    // public function getAccount1ID() { return $this->account1ID; }
    // public function getAccount2ID() { return $this->account2ID; }

    public function setAccount1ID($account1ID) { $this->account1ID = $account1ID; }
    public function setAccount2ID($account2ID) { $this->account2ID = $account2ID; }

    // Functions
    public function removeFriend() {
        return $this->db->callProcedureExecute("sp_RemoveFriend", [$this->account1ID, $this->account2ID]);
    }

    public function getFriendList($accountID) {
        return $this->db->callProcedureSelect("sp_GetFriendList", [$accountID]);
    }

public function getSuggestedFriends($accountID) {
    return $this->db->callProcedureSelect("sp_SuggestFriends", [$accountID]);
}



}
?>

