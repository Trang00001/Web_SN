<?php
require_once __DIR__ . '/../../core/Database.php';

class Profile {
    private $profileID;
    private $accountID;
    private $fullName;
    private $gender;
    private $birthDate;
    private $hometown;
    private $avatarURL;
    private $db;

    public function __construct($accountID, $fullName = "", $gender = "", $birthDate = null, $hometown = "", $avatarURL = "", $profileID = null) {
        $this->accountID = $accountID;
        $this->fullName = $fullName;
        $this->gender = $gender;
        $this->birthDate = $birthDate;
        $this->hometown = $hometown;
        $this->avatarURL = $avatarURL;
        $this->profileID = $profileID;
        $this->db = new Database();
    }

    // Getter & Setter
    public function getProfileID() { return $this->profileID; }
    public function getAccountID() { return $this->accountID; }
    public function getFullName() { return $this->fullName; }
    public function getGender() { return $this->gender; }
    public function getBirthDate() { return $this->birthDate; }
    public function getHometown() { return $this->hometown; }
    public function getAvatarURL() { return $this->avatarURL; }

    public function setProfileID($profileID) { $this->profileID = $profileID; }
    public function setAccountID($accountID) { $this->accountID = $accountID; }
    public function setFullName($fullName) { $this->fullName = $fullName; }
    public function setGender($gender) { $this->gender = $gender; }
    public function setBirthDate($birthDate) { $this->birthDate = $birthDate; }
    public function setHometown($hometown) { $this->hometown = $hometown; }
    public function setAvatarURL($avatarURL) { $this->avatarURL = $avatarURL; }

    // Functions
    public function getProfile() {
        return $this->db->callProcedureSelect("sp_GetUserProfile", [$this->accountID]);
    }

    public function updateProfile() {
        return $this->db->callProcedureExecute("sp_UpdateUserProfile", [
            $this->accountID, $this->fullName, $this->gender, $this->birthDate, $this->hometown, $this->avatarURL
        ]);
    }
}
?>
