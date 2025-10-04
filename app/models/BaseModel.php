<?php
require_once __DIR__ . '/../../core/Database.php';

interface ModelInterface {
    public function save(): bool;
}

abstract class BaseModel implements ModelInterface {
    protected Database $db;

    public function __construct() {
        $this->db = new Database();

    }
}
