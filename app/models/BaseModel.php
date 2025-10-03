<?php
require_once __DIR__ . '/../../core/Database.php';

abstract class BaseModel {
    protected PDO $pdo;

    public function __construct() {
        $this->pdo = (new Database())->getConnection();
    }
}
