<?php
require_once 'Database.php';

class User {
    private $conn;
    private $table = 'users';

    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $password;
    public $birth_date;
    public $gender;
    public $avatar;
    public $bio;
    public $location;
    public $created_at;
    public $is_active;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // Create new user
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                 SET first_name = :first_name,
                     last_name = :last_name,
                     email = :email,
                     password = :password,
                     birth_date = :birth_date,
                     gender = :gender";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        $this->birth_date = htmlspecialchars(strip_tags($this->birth_date));
        $this->gender = htmlspecialchars(strip_tags($this->gender));

        // Bind parameters
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':birth_date', $this->birth_date);
        $stmt->bindParam(':gender', $this->gender);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Login user
    public function login($email, $password) {
        $query = "SELECT id, first_name, last_name, email, password, avatar, bio, location 
                 FROM " . $this->table . " 
                 WHERE email = :email AND is_active = 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if(password_verify($password, $row['password'])) {
                $this->id = $row['id'];
                $this->first_name = $row['first_name'];
                $this->last_name = $row['last_name'];
                $this->email = $row['email'];
                $this->avatar = $row['avatar'];
                $this->bio = $row['bio'];
                $this->location = $row['location'];
                
                return true;
            }
        }

        return false;
    }

    // Get user by ID
    public function getUserById($id) {
        $query = "SELECT id, first_name, last_name, email, avatar, bio, location, created_at
                 FROM " . $this->table . " 
                 WHERE id = :id AND is_active = 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return false;
    }

    // Search users
    public function searchUsers($keyword, $limit = 10) {
        $query = "SELECT id, first_name, last_name, email, avatar, bio
                 FROM " . $this->table . " 
                 WHERE (first_name LIKE :keyword OR last_name LIKE :keyword OR email LIKE :keyword)
                 AND is_active = 1
                 LIMIT :limit";

        $stmt = $this->conn->prepare($query);
        $keyword = "%{$keyword}%";
        $stmt->bindParam(':keyword', $keyword);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update user profile
    public function updateProfile() {
        $query = "UPDATE " . $this->table . " 
                 SET first_name = :first_name,
                     last_name = :last_name,
                     bio = :bio,
                     location = :location,
                     avatar = :avatar,
                     updated_at = CURRENT_TIMESTAMP
                 WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->bio = htmlspecialchars(strip_tags($this->bio));
        $this->location = htmlspecialchars(strip_tags($this->location));
        $this->avatar = htmlspecialchars(strip_tags($this->avatar));

        // Bind parameters
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':bio', $this->bio);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':avatar', $this->avatar);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Check if email exists
    public function emailExists($email) {
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    // Get user's friends
    public function getFriends($user_id, $limit = 50) {
        $query = "SELECT u.id, u.first_name, u.last_name, u.avatar, u.bio
                 FROM users u
                 INNER JOIN friends f ON (
                     (f.user1_id = :user_id AND f.user2_id = u.id) OR
                     (f.user2_id = :user_id AND f.user1_id = u.id)
                 )
                 WHERE f.status = 'accepted' AND u.is_active = 1
                 LIMIT :limit";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get friend count
    public function getFriendCount($user_id) {
        $query = "SELECT COUNT(*) as count
                 FROM friends 
                 WHERE (user1_id = :user_id OR user2_id = :user_id) 
                 AND status = 'accepted'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
}
?>